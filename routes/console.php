<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tự động sinh lịch cho 7 ngày tới vào lúc 0h00 hàng ngày
Schedule::command('schedules:generate --days=1')->dailyAt('00:00');
