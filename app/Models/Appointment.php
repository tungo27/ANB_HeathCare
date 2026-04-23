<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'doctor_id', 'schedule_id',
        'status', 'symptoms', 'note',
    ];

    protected $casts = [
        // Nếu bảng appointments CÓ cột created_at/updated_at:
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',  
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // ✅ Thêm quan hệ tới slot cụ thể
    public function slot()
    {
        return $this->hasOne(ScheduleSlot::class, 'appointment_id');
    }
}
