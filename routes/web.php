<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import 3 controllers chính
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

// Trang chủ redirect theo role
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

// Profile routes (chung cho tất cả)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================
// ADMIN ROUTES
// =====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/assign-shift', [AdminController::class, 'assignShift'])->name('assign_shift');

    // 👨‍⚕️ Quản lý Bác sĩ
    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [AdminController::class, 'doctorManagement'])->name('doctorManagement');
        Route::get('/create', [AdminController::class, 'doctorCreate'])->name('doctorCreate');
        Route::post('/store', [AdminController::class, 'doctorStore'])->name('doctorStore');
        Route::get('/{doctor}/edit', [AdminController::class, 'doctorEdit'])->name('doctorEdit');
        Route::put('/{doctor}', [AdminController::class, 'doctorUpdate'])->name('doctorUpdate');
        Route::delete('/{doctor}', [AdminController::class, 'doctorDestroy'])->name('doctorDestroy');
    });

    // 📋 Quản lý Ca làm việc (Schedules)
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [AdminController::class, 'scheduleIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'scheduleCreate'])->name('create');
        Route::post('/', [AdminController::class, 'scheduleStore'])->name('store');
        Route::get('/{schedule}/edit', [AdminController::class, 'scheduleEdit'])->name('edit');
        Route::put('/{schedule}', [AdminController::class, 'scheduleUpdate'])->name('update');
        Route::delete('/{schedule}', [AdminController::class, 'scheduleDestroy'])->name('destroy');


        // ⭐ Custom routes cho Schedule Slots
        Route::get('/{schedule}/slots', [AdminController::class, 'showSlots'])->name('slots');
        Route::post('/slots/assign', [AdminController::class, 'assignSlot'])->name('slots.assign');
        Route::post('/{schedule}/generate-slots', [AdminController::class, 'generateSlots'])->name('generate-slots');
        Route::post('/slots/{slot}/toggle', [AdminController::class, 'toggleSlotStatus'])->name('slots.toggle');

        Route::post('/slots/{slot}/block', [AdminController::class, 'blockSlot'])->name('slots.block');
        Route::post('/slots/{slot}/unblock', [AdminController::class, 'unblockSlot'])->name('slots.unblock');
        Route::post('/slots/{slot}/cancel-appointment', [AdminController::class, 'cancelSlotAppointment'])->name('slots.cancel-appointment');
    });

    // 📊 Báo cáo
    Route::get('/reports/slots', [AdminController::class, 'reportSlots'])->name('reports.slots');
});

// =====================================================
// DOCTOR ROUTES 
// =====================================================
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {

    // Dashboard + Appointments list
    Route::get('/', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/{appointment}', [DoctorController::class, 'showAppointment'])->name('appointments.show');
    Route::put('/appointments/{appointment}/accept', [DoctorController::class, 'acceptAppointment'])->name('appointments.accept');
    Route::put('/appointments/{appointment}/reject', [DoctorController::class, 'rejectAppointment'])->name('appointments.reject');
    Route::patch('/appointments/{appointment}/status', [DoctorController::class, 'updateStatus'])->name('appointments.update-status');
    Route::post('/appointments/{appointment}/follow-up', [DoctorController::class, 'createFollowUp'])->name('appointments.follow-up');

    // Shift assignments
    Route::post('/accept-shift/{id}', [DoctorController::class, 'acceptShift'])->name('accept_shift');
    Route::post('/reject-shift/{id}', [DoctorController::class, 'rejectShift'])->name('reject_shift');

    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/api/events', [DoctorController::class, 'calendarEvents'])->name('api.events');
        Route::get('/{schedule}', [DoctorController::class, 'detail'])->name('detail');
    });

    // 🩺 Xử lý khám bệnh (complete/cancel appointment)
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::put('/{appointment}/complete', [DoctorController::class, 'complete'])->name('complete');
        Route::put('/{appointment}/cancel', [DoctorController::class, 'cancel'])->name('cancel');
    });
});

// =====================================================
// 👤 PATIENT ROUTES
// =====================================================
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {


    Route::get('/', [PatientController::class, 'index'])->name('dashboard');
    Route::get('/search', [PatientController::class, 'search'])->name('search');

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/doctors', [PatientController::class, 'index'])->name('doctors');
        Route::get('/doctors/{doctor}/slots', [PatientController::class, 'selectSlot'])->name('select-slot');
        Route::post('/confirm', [PatientController::class, 'bookWithSlot'])->name('confirm');
    });

    // Quản lý lịch hẹn
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [PatientController::class, 'myAppointments'])->name('index');
        Route::get('/{appointment}', [PatientController::class, 'showAppointment'])->name('show');
        Route::put('/{appointment}/cancel', [PatientController::class, 'cancelAppointment'])->name('cancel');
        Route::put('/{appointment}/reschedule', [PatientController::class, 'rescheduleAppointment'])->name('reschedule');
    });
});
