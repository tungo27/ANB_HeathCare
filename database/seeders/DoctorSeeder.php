<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $specialtyId = DB::table('specialties')->value('id');

        $doctor1 = DB::table('users')->where('email', 'doctor1@gmail.com')->value('id');
        $doctor2 = DB::table('users')->where('email', 'doctor2@gmail.com')->value('id');

        DB::table('doctors')->insert([
            [
                'user_id'             => $doctor1,
                'specialty_id'        => $specialtyId,
                'qualification'       => 'Bác sĩ chuyên khoa I',
                'years_of_experience' => 5,
                'consultation_fee'    => 500000,
                'bio'                 => 'Chuyên khám bệnh nội tổng quát.',
            ],
            [
                'user_id'             => $doctor2,
                'specialty_id'        => $specialtyId,
                'qualification'       => 'Bác sĩ chuyên khoa II',
                'years_of_experience' => 8,
                'consultation_fee'    => 700000,
                'bio'                 => 'Chuyên khám nhi và da liễu.',
            ],
        ]);
    }
}