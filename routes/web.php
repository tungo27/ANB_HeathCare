<?php

use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

// Bạn có thể bọc block này trong middleware auth hoặc admin tuỳ thuộc cài đặt của bạn.
// Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
//     Route::resource('specialties', SpecialtyController::class);
//     Route::resource('doctors', DoctorController::class);
// });

// Đã loại bỏ middleware 'auth' để tiện cho Unit Test
Route::prefix('admin')->name('admin.')->group(function () {
    // Chuyên khoa (Specialty) CRUD bằng GET/POST
    Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('specialties/create', [SpecialtyController::class, 'create'])->name('specialties.create');
    Route::post('specialties/store', [SpecialtyController::class, 'store'])->name('specialties.store');
    Route::get('specialties/edit/{specialty}', [SpecialtyController::class, 'edit'])->name('specialties.edit');
    Route::post('specialties/update/{specialty}', [SpecialtyController::class, 'update'])->name('specialties.update');
    Route::post('specialties/destroy/{specialty}', [SpecialtyController::class, 'destroy'])->name('specialties.destroy');

    // Bác sĩ (Doctor) CRUD bằng GET/POST
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('doctors/edit/{doctor}', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::post('doctors/update/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::post('doctors/destroy/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
});
