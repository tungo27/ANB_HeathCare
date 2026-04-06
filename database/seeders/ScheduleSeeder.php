<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctor1 = DB::table('users')->where('email', 'doctor1@gmail.com')->value('id');
        $doctor2 = DB::table('users')->where('email', 'doctor2@gmail.com')->value('id');

        DB::table('schedules')->insert([
            [
                'doctor_id'    => $doctor1,
                'room'         => '101',
                'work_date'    => '2026-04-10',
                'start_time'   => '08:00:00',
                'end_time'     => '12:00:00',
                'slot_duration'=> 30,
                'max_patients' => 10,
            ],
            [
                'doctor_id'    => $doctor1,
                'room'         => '102',
                'work_date'    => '2026-04-11',
                'start_time'   => '13:00:00',
                'end_time'     => '17:00:00',
                'slot_duration'=> 30,
                'max_patients' => 10,
            ],
            [
                'doctor_id'    => $doctor2,
                'room'         => '201',
                'work_date'    => '2026-04-10',
                'start_time'   => '09:00:00',
                'end_time'     => '15:00:00',
                'slot_duration'=> 30,
                'max_patients' => 12,
            ],
        ]);
    }
}