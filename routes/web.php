<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

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

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/assign-shift', [AdminController::class, 'assignShift'])->name('assign_shift');
        // Quản lý Lịch làm việc (Schedules)
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/create', [AdminController::class, 'scheduleCreate'])->name('create');
            Route::post('/store', [AdminController::class, 'scheduleStore'])->name('store');
        });
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', [AdminController::class, 'doctorManagement'])->name('doctorManagement');
            Route::get('/create', [AdminController::class, 'doctorCreate'])->name('doctorCreate');
            Route::post('/store', [AdminController::class, 'doctorStore'])->name('doctorStore');
            Route::get('/{doctor}/edit', [AdminController::class, 'doctorEdit'])->name('doctorEdit');
            Route::put('/{doctor}', [AdminController::class, 'doctorUpdate'])->name('doctorUpdate');
            Route::delete('/{doctor}', [AdminController::class, 'doctorDestroy'])->name('doctorDestroy');
        });
    });

    // Doctor Routes
    Route::prefix('doctor')->middleware('role:doctor')->name('doctor.')->group(function () {
        Route::get('/', [DoctorController::class, 'dashboard'])->name('dashboard');
        Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
        Route::post('/accept-shift/{id}', [DoctorController::class, 'acceptShift'])->name('accept_shift');
        Route::post('/reject-shift/{id}', [DoctorController::class, 'rejectShift'])->name('reject_shift');
        Route::get('/show', [DoctorController::class, 'showAppointment'])->name('appointments.show');
    });

    // Patient Routes
    Route::prefix('patient')->middleware('role:patient')->name('patient.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('dashboard');
        Route::get('/booking/{id}', [PatientController::class, 'showBooking'])->name('booking');
        Route::get('/search', [PatientController::class, 'search'])->name('search');
        Route::get('/get-slots', [PatientController::class, 'getSlots'])->name('get_slots');
        Route::post('/book', [PatientController::class, 'book'])->name('book');
    });
});
