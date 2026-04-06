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
            ['email'=>'patient1@gmail.com', 'password'=>Hash::make('123456'), 'role'=>'patient', 'full_name'=>'Nguyễn Văn A', 'phone'=>'0123456789'],
            ['email'=>'patient2@gmail.com', 'password'=>Hash::make('123456'), 'role'=>'patient', 'full_name'=>'Trần Thị B', 'phone'=>'0987654321'],
            ['email'=>'doctor1@gmail.com', 'password'=>Hash::make('123456'), 'role'=>'doctor', 'full_name'=>'Dr. Trần B', 'phone'=>'0912345678'],
            ['email'=>'doctor2@gmail.com', 'password'=>Hash::make('123456'), 'role'=>'doctor', 'full_name'=>'Dr. Lê C', 'phone'=>'0981122334'],
            ['email'=>'admin1@gmail.com', 'password'=>Hash::make('123456'), 'role'=>'admin', 'full_name'=>'Admin C', 'phone' => '0865364452'],
        ];

        DB::table('users')->insert($users);
    }
}