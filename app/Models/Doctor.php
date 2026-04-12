<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'specialty_id', 'qualification', 'years_of_experience', 'consultation_fee', 'bio'];

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Specialties(): BelongsTo
    {
        return $this->belongsTo(Specialties::class, 'specialty_id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'user_id');
    }
}
