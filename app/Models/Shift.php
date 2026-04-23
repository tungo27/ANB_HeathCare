<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    // Disables default created_at and updated_at expectations during Seeding
    public $timestamps = false;

    protected $fillable = ['name', 'start_time', 'end_time'];

    public function assignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }
}
