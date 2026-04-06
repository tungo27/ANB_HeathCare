<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            ['name'=>'Khám tổng quát', 'price'=>150000, 'duration_minutes'=>30],
            ['name'=>'Xét nghiệm máu', 'price'=>300000, 'duration_minutes'=>45],
            ['name'=>'Khám da liễu', 'price'=>200000, 'duration_minutes'=>30],
            ['name'=>'Khám nhi', 'price'=>180000, 'duration_minutes'=>25],
        ]);
    }
}