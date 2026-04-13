<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'doctor_id', 'schedule_id', 'service_id',
        'appointment_date', 'appointment_time',
        'status', 'symptoms', 'note',
    ];
}
