<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

class GenerateDailySchedules extends Command
{
    // Bạn có thể truyền số ngày tương lai muốn tạo. VD: php artisan schedules:generate --days=1
    protected $signature = 'schedules:generate {--days=1 : Số ngày tương lai để tạo lịch} {--user= : ID của bác sĩ (user_id) cần tạo riêng}';
    protected $description = 'Tự động tạo lịch làm việc cho tất cả bác sĩ theo ca quy định';

    public function handle()
    {
        $days = $this->option('days');
        $doctors = Doctor::all();
        $userId = $this->option('user');

        // Nếu có truyền --user=... thì chỉ lấy bác sĩ đó, ngược lại thì lấy TẤT CẢ
        if ($userId) {
            $doctors = Doctor::where('user_id', $userId)->get();
        } else {
            $doctors = Doctor::all();
        }
        $slotDuration = 30; // 30 phút mỗi ca

        if ($doctors->isEmpty()) {
            $this->warn('Không có bác sĩ nào trong hệ thống!');
            return;
        }

        foreach ($doctors as $doctor) {
            // Bỏ qua các bác sĩ bị lỗi data (user_id = 0 hoặc rỗng) do model cũ tạo ra
            if (empty($doctor->user_id) || $doctor->user_id == 0) {
                $this->warn("Bỏ qua bác sĩ bị lỗi dữ liệu (user_id = 0).");
                continue;
            }

            for ($i = 0; $i < $days; $i++) {
                $workDate = Carbon::today()->addDays($i)->format('Y-m-d');

                // Ca sáng: 8:00 - 12:00
                $this->generateSlotsForPeriod($doctor->user_id, $workDate, '08:00', '12:00', $slotDuration, 'Phòng Khám Nội');

                // Ca chiều: 13:30 - 17:00
                $this->generateSlotsForPeriod($doctor->user_id, $workDate, '13:30', '17:00', $slotDuration, 'Phòng Khám Nội');
            }
        }

        $this->info("Đã tạo lịch làm việc tự động thành công cho $days ngày tới.");
    }

    private function generateSlotsForPeriod($userId, $workDate, $startTime, $endTime, $slotDuration, $room)
    {
        $current = Carbon::parse("$workDate $startTime");
        $end = Carbon::parse("$workDate $endTime");

        while ($current < $end) {
            $slotStart = $current->copy();
            $slotEnd = $current->copy()->addMinutes($slotDuration);

            if ($slotEnd > $end) {
                break;
            }

            $strStartTime = $slotStart->format('H:i:s');
            $strEndTime = $slotEnd->format('H:i:s');

            // Kiểm tra xem giờ này đã tồn tại chưa (chống trùng lặp nếu lỡ chạy lệnh 2 lần)
            $exists = Schedule::where('doctor_id', $userId)
                ->where('work_date', $workDate)
                ->where('start_time', $strStartTime)
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'doctor_id' => $userId,
                    'room' => $room,
                    'work_date' => $workDate,
                    'start_time' => $strStartTime,
                    'end_time' => $strEndTime,
                    'slot_duration' => $slotDuration,
                    'max_patients' => 10,
                    'status' => 1,
                ]);
            }

            $current->addMinutes($slotDuration);
        }
    }
}
