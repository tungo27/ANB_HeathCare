<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patient1 = DB::table('users')->where('email', 'patient1@gmail.com')->value('id');
        $patient2 = DB::table('users')->where('email', 'patient2@gmail.com')->value('id');

        DB::table('patients')->insert([
            [
                'user_id'                 => $patient1,
                'gender'                  => 'male',
                'date_of_birth'           => '1990-01-01',
                'address'                 => 'Đà Nẵng',
                'emergency_contact_name'  => 'Nguyễn Văn X',
                'emergency_contact_phone' => '0123456789',
            ],
            [
                'user_id'                 => $patient2,
                'gender'                  => 'female',
                'date_of_birth'           => '1995-05-05',
                'address'                 => 'Hà Nội',
                'emergency_contact_name'  => 'Trần Thị Y',
                'emergency_contact_phone' => '0987654321',
            ],
        ]);
    }
}