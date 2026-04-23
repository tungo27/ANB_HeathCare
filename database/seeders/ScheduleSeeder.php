<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Lấy danh sách bác sĩ đã tồn tại (từ bảng doctors)
        $doctors = DB::table('doctors')->get(['user_id']);
        
        if ($doctors->count() < 2) {
            $this->command->error("❌ Cần ít nhất 2 bác sĩ trong bảng doctors để tạo lịch!");
            $this->command->warn("💡 Hãy chạy: php artisan db:seed --class=DoctorSeeder trước");
            return;
        }

        $schedules = [];
        $rooms = ['A101', 'A102', 'B201', 'B205', 'C301', 'C305', 'D401'];
        
        // ✅ Tạo 3 lịch cho mỗi bác sĩ (vào các ngày khác nhau)
        foreach ($doctors as $index => $doctor) {
            $baseDate = now()->addDays($index % 7); // Phân bổ lịch trong tuần
            
            // Lịch sáng: 08:00 - 08:30
            $schedules[] = [
                'doctor_id'  => $doctor->user_id,
                'room'       => $rooms[$index % count($rooms)],
                'work_date'  => $baseDate->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time'   => '08:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Lịch giữa ngày: 10:00 - 10:30
            $schedules[] = [
                'doctor_id'  => $doctor->user_id,
                'room'       => $rooms[($index + 1) % count($rooms)],
                'work_date'  => $baseDate->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time'   => '10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Lịch chiều: 14:00 - 14:30
            $schedules[] = [
                'doctor_id'  => $doctor->user_id,
                'room'       => $rooms[($index + 2) % count($rooms)],
                'work_date'  => $baseDate->addDay()->format('Y-m-d'),
                'start_time' => '14:00:00',
                'end_time'   => '14:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ✅ Insert với ignoreDuplicates để tránh lỗi khi chạy lại
        DB::table('schedules')->upsert(
            $schedules,
            ['doctor_id', 'work_date', 'start_time'], // Unique key
            ['room', 'end_time', 'updated_at']        // Fields to update if duplicate
        );

        $this->command->info("✅ ScheduleSeeder: Đã tạo " . count($schedules) . " khung giờ cho " . $doctors->count() . " bác sĩ");
        
        // ✅ Cache schedule IDs cho AppointmentSeeder dùng
        $scheduleIds = DB::table('schedules')->pluck('id')->toArray();
        $this->command->getOutput()->writeln("<comment>[CACHE] schedule_ids:</comment> " . json_encode(array_slice($scheduleIds, 0, 20)) . "...");
    }
}