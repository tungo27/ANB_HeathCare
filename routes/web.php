<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CloudinaryTestController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/cloudinary-test', [CloudinaryTestController::class, 'index'])->name('cloudinary.test');
Route::post('/cloudinary-test/image', [CloudinaryTestController::class, 'uploadImage'])->name('cloudinary.image');
Route::post('/cloudinary-test/video', [CloudinaryTestController::class, 'uploadVideo'])->name('cloudinary.video');
Route::get('/cloudinary-test/images', [CloudinaryTestController::class, 'getImages'])->name('cloudinary.images');