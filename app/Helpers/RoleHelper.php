<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    public static function redirectByRole(): string
    {
        return match(Auth::user()->role) {
            'admin'   => route('admin.dashboard'),
            'doctor'  => route('doctor.dashboard'),
            'patient' => route('patient.dashboard'),
            default   => route('login'),
        };
    }
}