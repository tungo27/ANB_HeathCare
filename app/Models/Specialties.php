<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialties extends Model
{
    protected $fillable = ['name', 'description'];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
