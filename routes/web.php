<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
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

        // Quản lý Lịch làm việc (Schedules)
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/create', [AdminController::class, 'scheduleCreate'])->name('create');
            Route::post('/store', [AdminController::class, 'scheduleStore'])->name('store');
        });
    });

// 5. NHÓM DOCTOR
Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DoctorController::class, 'index'])->name('dashboard');

        // Xem lịch hẹn khám
        Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    });

// 6. NHÓM PATIENT
Route::prefix('patient')
    ->middleware(['auth', 'role:patient'])
    ->name('patient.')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('dashboard');
    });
