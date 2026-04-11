<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. Điều hướng gốc (Root Redirection)
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'doctor'  => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default   => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// 2. Authentication Routes (Breeze/Jetstream)
require __DIR__ . '/auth.php';

// 3. Profile chung cho tất cả người dùng đã login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Khu vực ADMIN (Gộp Dashboard của bạn bạn và CRUD của bạn)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin']) // Bảo mật: Chỉ admin mới vào được
    ->name('admin.')
    ->group(function () {

        // Dashboard chính
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // Quản lý Chuyên khoa (Specialty)
        Route::controller(SpecialtyController::class)->prefix('specialties')->name('specialties.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{specialty}/edit', 'edit')->name('edit');
            Route::put('/{specialty}', 'update')->name('update');
            Route::delete('/{specialty}', 'destroy')->name('destroy');
        });

        // Quản lý Bác sĩ (Doctor CRUD trong Admin)
        Route::controller(DoctorController::class)->prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{doctor}', 'edit')->name('edit');
            Route::put('/{doctor}', 'update')->name('update');
            Route::delete('/{doctor}', 'destroy')->name('destroy');
        });
    });

// 5. Khu vực DOCTOR (Dành riêng cho bác sĩ xem Dashboard của họ)
Route::prefix('doctor')
    ->middleware(['auth', 'role:doctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('dashboard');
    });

// 6. Khu vực PATIENT
Route::prefix('patient')
    ->middleware(['auth', 'role:patient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('dashboard');
    });
