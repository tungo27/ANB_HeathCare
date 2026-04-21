<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSlotSeeder extends Seeder
{
    // ⚙️ Cấu hình mặc định cho slot
    private const DEFAULT_SLOT_DURATION = 30; // phút
    private const DEFAULT_BREAK_TIME = 5;     // phút nghỉ giữa các slot
    private const BLOCKED_LUNCH_START = '11:30'; // Giờ nghỉ trưa bắt đầu
    private const BLOCKED_LUNCH_END = '12:00';   // Giờ nghỉ trưa kết thúc

    public function run(): void
    {
        $this->command->info('🔄 Đang tạo schedule slots...');

        // ✅ Bước 1: Kiểm tra dữ liệu nền
        $schedules = DB::table('schedules')->get();
        if ($schedules->isEmpty()) {
            $this->command->error('❌ Chưa có dữ liệu trong bảng schedules!');
            $this->command->warn('💡 Hãy chạy ScheduleSeeder trước: php artisan db:seed --class=ScheduleSeeder');
            return;
        }

        $appointments = DB::table('appointments')
            ->whereIn('status', ['confirmed', 'completed'])
            ->get(['id', 'schedule_id']);
        
        $groupedAppointments = $appointments->groupBy('schedule_id');

        $totalSlotsCreated = 0;
        $slotsToInsert = [];

        // ✅ Bước 2: Duyệt từng schedule để tạo slots
        foreach ($schedules as $schedule) {
            $slots = $this->generateSlotsForSchedule($schedule, $groupedAppointments);
            
            if (!empty($slots)) {
                $slotsToInsert = array_merge($slotsToInsert, $slots);
                $totalSlotsCreated += count($slots);
                
                // ✅ Batch insert mỗi 100 slots để tối ưu performance
                if (count($slotsToInsert) >= 100) {
                    DB::table('schedule_slots')->insert($slotsToInsert);
                    $this->command->info("   ✅ Đã insert " . count($slotsToInsert) . " slots...");
                    $slotsToInsert = [];
                }
            }
        }

        // ✅ Insert nốt số slots còn lại
        if (!empty($slotsToInsert)) {
            DB::table('schedule_slots')->insert($slotsToInsert);
        }

        // ✅ Báo cáo kết quả
        $this->command->info("✅ ScheduleSlotSeeder: Đã tạo {$totalSlotsCreated} slots cho {$schedules->count()} ca làm việc.");
        
        // Thống kê theo status
        $this->showStatusReport();
    }

    /**
     * Tạo danh sách slots cho một schedule cụ thể
     */
    private function generateSlotsForSchedule($schedule, $groupedAppointments): array
    {
        $slots = [];
        $slotNumber = 1;
        
        // Parse thời gian làm việc
        $currentTime = Carbon::parse("{$schedule->work_date} {$schedule->start_time}");
        $endTime = Carbon::parse("{$schedule->work_date} {$schedule->end_time}");
        
        $slotDuration = self::DEFAULT_SLOT_DURATION;
        $breakTime = self::DEFAULT_BREAK_TIME;
        
        // Lấy appointments đã có trong schedule này (để gán vào slot booked)
        $scheduleAppointments = $groupedAppointments->get($schedule->id, collect());
        $appointmentQueue = $scheduleAppointments->shuffle()->values();
        $appointmentIndex = 0;

        while ($currentTime->copy()->addMinutes($slotDuration)->lte($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($slotDuration);
            
            // 🚫 Skip nếu trùng giờ nghỉ trưa cố định
            if ($this->isLunchBreak($currentTime, $slotEnd)) {
                $slots[] = $this->createSlotData(
                    $schedule->id,
                    $slotNumber,
                    $currentTime,
                    $slotEnd,
                    'blocked',
                    null,
                    '⏰ Giờ nghỉ trưa cố định'
                );
                $slotNumber++;
                $currentTime = $slotEnd;
                continue;
            }
            
            // 🎲 Quyết định trạng thái slot (có trọng số)
            $status = $this->determineSlotStatus($slotNumber, $schedule);
            $appointmentId = null;
            $internalNote = null;
            
            if ($status === 'booked' && $appointmentIndex < $appointmentQueue->count()) {
                // Gán appointment có sẵn vào slot
                $appointmentId = $appointmentQueue[$appointmentIndex]->id;
                $appointmentIndex++;
            } elseif ($status === 'blocked') {
                $internalNote = $this->getRandomBlockReason();
            } elseif ($status === 'maintenance') {
                $internalNote = '🔧 Bảo trì hệ thống / Buffer khẩn cấp';
            }
            
            $slots[] = $this->createSlotData(
                $schedule->id,
                $slotNumber,
                $currentTime,
                $slotEnd,
                $status,
                $appointmentId,
                $internalNote
            );
            
            $slotNumber++;
            $currentTime = $slotEnd->addMinutes($breakTime);
        }
        
        return $slots;
    }

    /**
     * Tạo mảng dữ liệu cho 1 slot
     */
    private function createSlotData(
        int $scheduleId,
        int $slotNumber,
        Carbon $startTime,
        Carbon $endTime,
        string $status,
        ?int $appointmentId,
        ?string $internalNote
    ): array {
        return [
            'schedule_id' => $scheduleId,
            'slot_number' => $slotNumber,
            'slot_start_time' => $startTime->format('H:i:s'),
            'slot_end_time' => $endTime->format('H:i:s'),
            'status' => $status,
            'appointment_id' => $appointmentId,
            'internal_note' => $internalNote,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Kiểm tra có trùng giờ nghỉ trưa không
     */
    private function isLunchBreak(Carbon $start, Carbon $end): bool
    {
        $lunchStart = Carbon::parse($start->format('Y-m-d') . ' ' . self::BLOCKED_LUNCH_START);
        $lunchEnd = Carbon::parse($start->format('Y-m-d') . ' ' . self::BLOCKED_LUNCH_END);
        
        // Kiểm tra overlap
        return $start->lt($lunchEnd) && $end->gt($lunchStart);
    }

    /**
     * Quyết định trạng thái slot dựa trên xác suất có trọng số
     */
    private function determineSlotStatus(int $slotNumber, $schedule): string
    {
        $rand = mt_rand(1, 100);
        
        // 📊 Phân bố xác suất thực tế:
        // - 60% available: Đa số slot còn trống để patient đặt
        // - 25% booked: Một số slot đã có người đặt
        // - 10% blocked: Bác sĩ block cho việc cá nhân/họp
        // - 5% maintenance: Buffer cho ca khẩn
        
        if ($rand <= 60) {
            return 'available';
        } elseif ($rand <= 85) {
            return 'booked';
        } elseif ($rand <= 95) {
            return 'blocked';
        } else {
            return 'maintenance';
        }
    }

    /**
     * Lấy lý do block ngẫu nhiên (tiếng Việt)
     */
    private function getRandomBlockReason(): string
    {
        $reasons = [
            '🩺 Bác sĩ có hội nghị chuyên môn',
            '🍽️ Giờ nghỉ cá nhân',
            '🚨 Buffer cho ca khẩn cấp',
            '📋 Họp nội bộ khoa',
            '🔋 Nghỉ giữa ca',
            '⚕️ Khám bệnh nhân nội trú',
        ];
        
        return $reasons[array_rand($reasons)];
    }

    /**
     * Hiển thị báo cáo thống kê sau khi seed
     */
    private function showStatusReport(): void
    {
        $report = DB::table('schedule_slots')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();
            
        $this->command->info("\n📊 Thống kê slots theo trạng thái:");
        foreach ($report as $item) {
            $icon = match($item->status) {
                'available' => '🟢',
                'booked' => '🔵',
                'blocked' => '🔴',
                'maintenance' => '🟡',
                default => '⚪',
            };
            $this->command->line("   {$icon} {$item->status}: {$item->count} slots");
        }
    }
}