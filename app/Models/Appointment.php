<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'schedule_id',
        'status',
        'symptoms',
        'note',
        'diagnosis_result',
        'cancellation_reason',
        'rescheduled_from_id',
        'follow_up_appointment_id',  // ✅ Thêm field follow-up
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancelled_by',
    ];

    protected $casts = [
        // Nếu bảng appointments CÓ cột created_at/updated_at:
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function originalAppointment()
    {
        return $this->belongsTo(Appointment::class, 'rescheduled_from_id');
    }

    /**
     * Appointment follow-up (khi appointment này có lịch hẹn lại)
     */
    public function followUpAppointment()
    {
        return $this->hasOne(Appointment::class, 'rescheduled_from_id');
    }

    /**
     * User đã hủy appointment (nếu có)
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

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
