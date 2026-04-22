<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ScheduleSlot extends Model
{
    protected $casts = [
        'slot_start_time' => 'datetime:H:i',
        'slot_end_time' => 'datetime:H:i',
    ];
    
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
    
    // ✅ Helper: Book slot
    public function book(Appointment $appointment): bool
    {
        if ($this->status !== 'available') return false;
        
        return DB::transaction(function () use ($appointment) {
            $this->update([
                'status' => 'booked',
                'appointment_id' => $appointment->id,
            ]);
            return true;
        });
    }
    
    // ✅ Helper: Hủy booking
    public function release(): bool
    {
        return $this->update([
            'status' => 'available',
            'appointment_id' => null,
        ]);
    }
}
