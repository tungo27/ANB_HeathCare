<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Fix 1: Chuẩn hóa key specialty (case-insensitive + trim)
        $specialties = DB::table('specialties')
            ->pluck('id', 'name')
            ->mapWithKeys(fn($id, $name) => [mb_strtolower(trim($name)) => (int) $id]);

        $doctorsData = [
            [
                'email' => 'doctor.noikhoa1@gmail.com',
                'full_name' => 'Nguyễn Thị Hương',
                'phone' => '0901000001',
                'specialty' => 'Nội khoa',
                'qualification' => 'Bác sĩ chuyên khoa II',
                'years_of_experience' => 15,
                'consultation_fee' => 300000,
                'bio' => 'Chuyên khám tim mạch và nội tổng quát.',
            ],
            [
                'email' => 'doctor.noikhoa2@gmail.com',
                'full_name' => 'Trần Văn Minh',
                'phone' => '0901000002',
                'specialty' => 'Nội khoa',
                'qualification' => 'Bác sĩ chuyên khoa I',
                'years_of_experience' => 8,
                'consultation_fee' => 250000,
                'bio' => 'Chuyên khám tiêu hóa và nội tổng quát.',
            ],
            [
                'email' => 'doctor.nhikhoa1@gmail.com',
                'full_name' => 'Lê Thị Mai',
                'phone' => '0901000003',
                'specialty' => 'Nhi khoa',
                'qualification' => 'Tiến sĩ Y khoa',
                'years_of_experience' => 12,
                'consultation_fee' => 280000,
                'bio' => 'Chuyên khám bệnh trẻ em và sơ sinh.',
            ],
            [
                'email' => 'doctor.nhikhoa2@gmail.com',
                'full_name' => 'Phạm Quốc Bảo',
                'phone' => '0901000004',
                'specialty' => 'Nhi khoa',
                'qualification' => 'Bác sĩ chuyên khoa I',
                'years_of_experience' => 6,
                'consultation_fee' => 220000,
                'bio' => 'Chuyên nhi tổng quát và dinh dưỡng trẻ em.',
            ],
            [
                'email' => 'doctor.sankhoa1@gmail.com',
                'full_name' => 'Võ Thị Lan',
                'phone' => '0901000005',
                'specialty' => 'Sản khoa',
                'qualification' => 'Bác sĩ chuyên khoa II',
                'years_of_experience' => 18,
                'consultation_fee' => 350000,
                'bio' => 'Chuyên theo dõi thai kỳ và sinh sản.',
            ],
            [
                'email' => 'doctor.dalieu1@gmail.com',
                'full_name' => 'Đỗ Thị Thanh',
                'phone' => '0901000006',
                'specialty' => 'Da liễu',
                'qualification' => 'Bác sĩ chuyên khoa I',
                'years_of_experience' => 14,
                'consultation_fee' => 280000,
                'bio' => 'Chuyên điều trị mụn, nám và các bệnh da liễu.',
            ],
            [
                'email' => 'doctor.dalieu2@gmail.com',
                'full_name' => 'Hoàng Văn Tùng',
                'phone' => '0901000007',
                'specialty' => 'Da liễu',
                'qualification' => 'Thạc sĩ Y khoa',
                'years_of_experience' => 9,
                'consultation_fee' => 240000,
                'bio' => 'Chuyên dị ứng da và viêm da cơ địa.',
            ],
            [
                'email' => 'doctor.ranghammat1@gmail.com',
                'full_name' => 'Nguyễn Văn Khoa',
                'phone' => '0901000008',
                'specialty' => 'Răng Hàm Mặt',
                'qualification' => 'Bác sĩ Răng Hàm Mặt',
                'years_of_experience' => 10,
                'consultation_fee' => 200000,
                'bio' => 'Chuyên niềng răng và implant nha khoa.',
            ],
            [
                'email' => 'doctor.changthinh1@gmail.com',
                'full_name' => 'Lý Minh Tuấn',
                'phone' => '0901000009',
                'specialty' => 'Chấn thương chỉnh hình',
                'qualification' => 'Bác sĩ chuyên khoa II',
                'years_of_experience' => 20,
                'consultation_fee' => 400000,
                'bio' => 'Chuyên phẫu thuật chỉnh hình và phục hồi chức năng.',
            ],
            [
                'email' => 'doctor.nhankhoa1@gmail.com',
                'full_name' => 'Trần Thị Bích',
                'phone' => '0901000010',
                'specialty' => 'Nhãn Khoa',
                'qualification' => 'Bác sĩ chuyên khoa I',
                'years_of_experience' => 7,
                'consultation_fee' => 230000,
                'bio' => 'Chuyên khám mắt, tật khúc xạ và đục thủy tinh thể.',
            ],
        ];

        $insertedCount = 0;
        $skippedCount = 0;

        foreach ($doctorsData as $data) {
            // ✅ Fix 2: So sánh specialty case-insensitive
            $specialtyKey = mb_strtolower(trim($data['specialty']));
            
            if (!isset($specialties[$specialtyKey])) {
                $this->command->warn("❌ Không tìm thấy chuyên khoa: '{$data['specialty']}' — bỏ qua.");
                $skippedCount++;
                continue;
            }

            // ✅ Fix 3: Kiểm tra trùng email trước khi insert
            if (DB::table('users')->where('email', $data['email'])->exists()) {
                $this->command->warn("⚠️ Email đã tồn tại: {$data['email']} — bỏ qua.");
                $skippedCount++;
                continue;
            }

            // ✅ Insert user trước
            $userId = DB::table('users')->insertGetId([
                'email'      => $data['email'],
                'password'   => Hash::make('password123'),
                'role'       => 'doctor',
                'full_name'  => $data['full_name'],
                'phone'      => $data['phone'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ✅ Insert doctor với user_id vừa tạo
            DB::table('doctors')->insert([
                'user_id'             => $userId,
                'specialty_id'        => $specialties[$specialtyKey],
                'qualification'       => $data['qualification'],
                'years_of_experience' => $data['years_of_experience'],
                'consultation_fee'    => $data['consultation_fee'],
                'bio'                 => $data['bio'],
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
            
            $insertedCount++;
        }

        // ✅ Fix 4: Báo cáo chính xác số record thực tế
        $this->command->info("✅ DoctorSeeder: Đã tạo {$insertedCount} bác sĩ | Bỏ qua {$skippedCount}");
        
        // ✅ Lưu danh sách user_id của bác sĩ vào cache để Seeder khác dùng
        $doctorUserIds = DB::table('doctors')->pluck('user_id')->toArray();
        $this->command->getOutput()->writeln("<comment>[CACHE] doctor_user_ids:</comment> " . json_encode($doctorUserIds));
    }
}