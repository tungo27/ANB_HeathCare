<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name'=>'Nội khoa', 'description'=>'Khám nội khoa'],
            ['name'=>'Ngoại khoa', 'description'=>'Khám ngoại khoa'],
            ['name'=>'Da liễu', 'description'=>'Khám da liễu'],
            ['name'=>'Nhi khoa', 'description'=>'Khám nhi'],
        ];
        DB::table('specialties')->insert($specialties);
    }
}