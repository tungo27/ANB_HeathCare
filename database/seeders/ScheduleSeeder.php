<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
public function run(): void
{
    // Lấy danh sách user_id (đây là khóa chính của bảng doctors)
    $doctorIds = DB::table('doctors')->pluck('user_id')->toArray();
    

    if (count($doctorIds) < 2) {
        $this->command->error("Cần ít nhất 2 bác sĩ trong bảng doctors!");
        return;
    }

    DB::table('schedules')->insert([
        [
            'doctor_id'     => $doctorIds[0], // Bây giờ $doctorIds chứa user_id hợp lệ
            'room'          => '101',
            'work_date'     => '2026-04-10',
            'start_time'    => '08:00:00',
            'end_time'      => '12:00:00',
            'slot_duration' => 30,
            'max_patients'  => 10,
        ],
        [
            'doctor_id'     => $doctorIds[0],
            'room'          => '102',
            'work_date'     => '2026-04-11',
            'start_time'    => '13:00:00',
            'end_time'      => '17:00:00',
            'slot_duration' => 30,
            'max_patients'  => 10,
        ],
        [
            'doctor_id'     => $doctorIds[1],
            'room'          => '201',
            'work_date'     => '2026-04-10',
            'start_time'    => '09:00:00',
            'end_time'      => '15:00:00',
            'slot_duration' => 30,
            'max_patients'  => 12,
        ],
    ]);
}
}