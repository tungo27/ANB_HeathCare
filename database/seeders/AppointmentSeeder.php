<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ID người dùng (Lưu ý: tham chiếu đến bảng users hoặc bảng patients/doctors tùy DB của bạn)
        $doctor1  = DB::table('users')->where('email', 'doctor1@gmail.com')->value('id');
        $doctor2  = DB::table('users')->where('email', 'doctor2@gmail.com')->value('id');
        $patient1 = DB::table('users')->where('email', 'patient1@gmail.com')->value('id');
        $patient2 = DB::table('users')->where('email', 'patient2@gmail.com')->value('id');

        // Lấy Schedule và Service
        $schedule1 = DB::table('schedules')->where('doctor_id', $doctor1)->first();
        $schedule2 = DB::table('schedules')->where('doctor_id', $doctor2)->first();
        $service1  = DB::table('services')->first(); // Lấy đại diện 1 dịch vụ

        if (!$doctor1 || !$patient1 || !$schedule1) {
            $this->command->error("Thiếu dữ liệu nền (Users/Schedules). Hãy chạy các Seeder khác trước!");
            return;
        }

        DB::table('appointments')->insert([
            [
                'patient_id'       => $patient1,
                'doctor_id'        => $doctor1,
                'schedule_id'      => $schedule1->id,
                'service_id'       => $service1->id ?? null,
                'appointment_date' => $schedule1->work_date,
                'appointment_time' => '08:00:00',
                'status'           => 'pending',
                'symptoms'         => 'Đau đầu, mệt mỏi',
                // THÊM: Giữ chỗ trong 15 phút kể từ lúc chạy seeder
                'expires_at'       => Carbon::now()->addMinutes(15), 
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'patient_id'       => $patient2,
                'doctor_id'        => $doctor2,
                'schedule_id'      => $schedule2->id,
                'service_id'       => $service1->id ?? null,
                'appointment_date' => $schedule2->work_date,
                'appointment_time' => '09:00:00',
                'status'           => 'confirmed',
                'symptoms'         => 'Sốt, ho',
                // THÊM: Confirmed thì không cần expires_at
                'expires_at'       => null, 
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}