<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShiftAssignment;
use App\Models\Doctor;
use App\Models\Shift;
use Carbon\Carbon;

class GenerateDailySchedules extends Command
{
    protected $signature = 'app:generate-daily-schedules';
    protected $description = 'Automatically assign shifts to doctors for the next day if needed';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $doctors = Doctor::all();
        $morningShift = Shift::where('name', 'Morning')->first();

        foreach ($doctors as $doctor) {
            $exists = ShiftAssignment::where('doctor_id', $doctor->user_id)
                ->where('work_date', $tomorrow)
                ->exists();

            if (!$exists && $morningShift) {
                ShiftAssignment::create([
                    'doctor_id' => $doctor->user_id,
                    'shift_id' => $morningShift->id,
                    'work_date' => $tomorrow,
                    'status' => 'pending',
                ]);
            }
        }

        $this->info('Daily shifts generated for ' . $tomorrow);
    }
}
