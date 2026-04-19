<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    protected $fillable = ['doctor_id', 'shift_id', 'work_date', 'status'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
