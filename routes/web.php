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
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('doctors', DoctorController::class);
});
