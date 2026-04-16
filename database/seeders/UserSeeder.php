<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Patients
            [
                'email'      => 'patient1@gmail.com',
                'password'   => Hash::make('123456'),
                'role'       => 'patient',
                'full_name'  => 'Nguyễn Văn A',
                'phone'      => '0123456789',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email'      => 'patient2@gmail.com',
                'password'   => Hash::make('123456'),
                'role'       => 'patient',
                'full_name'  => 'Trần Thị B',
                'phone'      => '0987654321',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Admin
            [
                'email'      => 'admin1@gmail.com',
                'password'   => Hash::make('123456'),
                'role'       => 'admin',
                'full_name'  => 'Admin C',
                'phone'      => '0865364452',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}