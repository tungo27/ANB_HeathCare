<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctor1  = DB::table('users')->where('email', 'doctor1@gmail.com')->value('id');
        $doctor2  = DB::table('users')->where('email', 'doctor2@gmail.com')->value('id');
        $patient1 = DB::table('users')->where('email', 'patient1@gmail.com')->value('id');
        $patient2 = DB::table('users')->where('email', 'patient2@gmail.com')->value('id');

        $schedule1 = DB::table('schedules')
            ->where('doctor_id', $doctor1)->where('work_date', '2026-04-10')->value('id');
        $schedule2 = DB::table('schedules')
            ->where('doctor_id', $doctor2)->where('work_date', '2026-04-10')->value('id');

        $service1 = DB::table('services')->where('name', 'Khám tổng quát')->value('id');
        $service2 = DB::table('services')->where('name', 'Khám nhi')->value('id');

        DB::table('appointments')->insert([
            [
                'patient_id'       => $patient1,
                'doctor_id'        => $doctor1,
                'schedule_id'      => $schedule1,
                'service_id'       => $service1,
                'appointment_date' => '2026-04-10',
                'appointment_time' => '08:00:00',
                'status'           => 'pending',
                'symptoms'         => 'Đau đầu, mệt mỏi',
            ],
            [
                'patient_id'       => $patient2,
                'doctor_id'        => $doctor2,
                'schedule_id'      => $schedule2,
                'service_id'       => $service2,
                'appointment_date' => '2026-04-10',
                'appointment_time' => '09:00:00',
                'status'           => 'confirmed',
                'symptoms'         => 'Sốt, ho',
            ],
        ]);
    }
}