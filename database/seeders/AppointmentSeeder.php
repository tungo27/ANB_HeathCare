<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ 1. Kiểm tra dữ liệu nền
        $patients = DB::table('users')->where('role', 'patient')->get(['id']);
        if ($patients->isEmpty()) {
            $this->command->error("❌ Chưa có bệnh nhân trong bảng users!");
            return;
        }

        $schedules = DB::table('schedules')
            ->join('doctors', 'schedules.doctor_id', '=', 'doctors.user_id')
            ->select('schedules.id', 'schedules.doctor_id')
            ->get();
            
        if ($schedules->isEmpty()) {
            $this->command->error("❌ Chưa có lịch khám trong bảng schedules!");
            return;
        }

        // ✅ 2. Dữ liệu mẫu
        $statuses = ['confirmed', 'confirmed', 'confirmed', 'completed', 'cancelled', 'rejected', 'no_show', 'rescheduled'];
        $cancellationReasons = [
            'cancelled' => ['Bệnh nhân bận đột xuất', 'Đã khám ở nơi khác'],
            'rejected'  => ['Bác sĩ có hội nghị đột xuất', 'Trùng lịch phẫu thuật'],
            'no_show'   => ['Không liên lạc được', 'Đến trễ quá 30 phút'],
        ];
        $symptoms = [
            'Đau đầu, chóng mặt', 'Sốt cao 39 độ', 'Đau bụng vùng thượng vị',
            'Ho khan kéo dài', 'Phát ban ngứa toàn thân', 'Đau khớp gối khi vận động',
            'Mờ mắt, nhìn đôi', 'Răng đau nhức khi ăn', 'Khám thai định kỳ tuần 24',
            'Trẻ sốt phát ban, quấy khóc'
        ];
        $diagnoses = [
            'Viêm họng cấp - Kê đơn kháng sinh',
            'Tăng huyết áp độ 1 - Theo dõi và điều chỉnh lối sống',
            'Viêm dạ dày - Nội soi và dùng thuốc bảo vệ niêm mạc',
            'Viêm phế quản - Kê thuốc giảm ho',
            'Viêm da cơ địa - Kem bôi và tránh dị nguyên',
        ];

        $appointments = [];
        $usedScheduleIds = [];

        // ✅ 3. Vòng lặp tạo dữ liệu
        for ($i = 0; $i < 15; $i++) {
            $availableSchedules = $schedules->filter(fn($s) => !in_array($s->id, $usedScheduleIds));
            if ($availableSchedules->isEmpty()) break;
            
            $schedule = $availableSchedules->random();
            $usedScheduleIds[] = $schedule->id;
            $patient = $patients->random();
            $status = $statuses[array_rand($statuses)];
            
            $now = Carbon::now();
            $createdAt = $now->copy()->subDays(rand(0, 10));

            // ✅ KHỞI TẠO MẢNG VỚI TẤT CẢ CỘT CÓ TRONG MIGRATION
            // Các cột nullable được gán null mặc định để đảm bảo count() luôn bằng nhau
            $appointment = [
                'patient_id'          => $patient->id,
                'schedule_id'         => $schedule->id,
                'status'              => $status,
                'symptoms'            => $symptoms[array_rand($symptoms)],
                'diagnosis_result'    => null, // ✅ Có trong migration
                'cancellation_reason' => null, // ✅ Có trong migration
                'cancelled_at'        => null, // ✅ Có trong migration
                'rescheduled_from_id' => null, // ✅ Có trong migration
                // ❌ KHÔNG thêm 'note' vì migration không có cột này
                'created_at'          => $createdAt,
                'updated_at'          => $now,
            ];
            
            // ✅ GÁN GIÁ TRỊ CÓ ĐIỀU KIỆN (Override giá trị null ở trên)
            if (in_array($status, ['cancelled', 'rejected', 'no_show'])) {
                $appointment['cancellation_reason'] = $cancellationReasons[$status][array_rand($cancellationReasons[$status])];
                $appointment['cancelled_at'] = $now->copy()->subHours(rand(1, 48));
            }
            
            if ($status === 'completed') {
                $appointment['diagnosis_result'] = $diagnoses[array_rand($diagnoses)];
            }
            
            $appointments[] = $appointment;
        }

        // ✅ 4. Insert
        if (!empty($appointments)) {
            DB::table('appointments')->insert($appointments);
            $this->command->info("✅ Đã tạo " . count($appointments) . " appointments thành công.");
        }
    }
}