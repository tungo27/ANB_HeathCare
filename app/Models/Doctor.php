<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = ['user_id', 'specialty_id', 'qualification', 'years_of_experience', 'consultation_fee', 'bio'];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Specialty(): BelongsTo
    {
        return $this->belongsTo(Specialties::class, 'specialty_id');
    }
}
