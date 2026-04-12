<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

Route::get('/', function () {
    if (Auth::check()) {
        return match(Auth::user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'doctor'  => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default   => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    });

// Doctor
Route::prefix('doctor')
    ->middleware(['auth', 'role:doctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('dashboard');
    });

// Patient
Route::prefix('patient')
    ->middleware(['auth', 'role:patient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('dashboard');
    });
