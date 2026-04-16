<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Nội khoa',               'description' => 'Khám nội khoa',           'is_active' => true],
            ['name' => 'Nhi khoa',               'description' => 'Khám nhi khoa',            'is_active' => true],
            ['name' => 'Sản khoa',               'description' => 'Khám sản phụ khoa',        'is_active' => true],
            ['name' => 'Da liễu',                'description' => 'Khám da liễu',             'is_active' => true],
            ['name' => 'Răng Hàm Mặt',          'description' => 'Khám răng hàm mặt',        'is_active' => true],
            ['name' => 'Chấn thương chỉnh hình', 'description' => 'Khám xương khớp',          'is_active' => true],
            ['name' => 'Nhãn Khoa',              'description' => 'Khám mắt',                 'is_active' => true],
        ];
        DB::table('specialties')->insert($specialties);
    }
}
