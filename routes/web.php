<?php

use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {
    // Chuyên khoa (Specialty) CRUD bằng GET/POST
    Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('specialties/create', [SpecialtyController::class, 'create'])->name('specialties.create');
    Route::post('specialties/store', [SpecialtyController::class, 'store'])->name('specialties.store');
    Route::get('specialties/{specialty}/edit', [SpecialtyController::class, 'edit'])->name('specialties.edit');
    Route::put('specialties/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
    Route::delete('specialties/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');

    // Bác sĩ (Doctor) CRUD
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('doctors/edit/{doctor}', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
});
