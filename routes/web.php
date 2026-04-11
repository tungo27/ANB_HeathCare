<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialytiesController;

// 1. Điều hướng gốc (Root Redirect)
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

// 2. Auth Routes (Breeze/Jetstream)
require __DIR__ . '/auth.php';

// 3. Profile chung cho tất cả User đã đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. NHÓM ADMIN (Đã kết hợp)
Route::prefix('admin')
    ->middleware(['auth', 'role:admin']) // Chỉ admin mới vào được toàn bộ nhóm này
    ->name('admin.')
    ->group(function () {

        // Trang chủ Admin
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');


        // Quản lý Bác sĩ (Doctors)
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', [AdminController::class, 'doctorManagement'])->name('doctorManagement');
            Route::get('/create', [AdminController::class, 'doctorCreate'])->name('doctorCreate');
            Route::post('/store', [AdminController::class, 'doctorStore'])->name('doctorStore');
            Route::get('/{doctor}/edit', [AdminController::class, 'doctorEdit'])->name('doctorEdit');
            Route::put('/{doctor}', [AdminController::class, 'doctorUpdate'])->name('doctorUpdate');
            Route::delete('/{doctor}', [AdminController::class, 'doctorDestroy'])->name('doctorDestroy');
        });
    });

// 5. NHÓM DOCTOR
Route::prefix('doctor')
    ->middleware(['auth', 'role:doctor'])
    ->name('doctor.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    });

// 6. NHÓM PATIENT
Route::prefix('patient')
    ->middleware(['auth', 'role:patient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('dashboard');
    });
