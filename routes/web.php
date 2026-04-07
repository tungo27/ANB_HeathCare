<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register', [UserController::class, 'Getregister']);        
Route::post('/register', [UserController::class, 'Register']);    
Route::get('/login', [UserController::class, 'Showlogin']);   
Route::post('/login', [UserController::class, 'login']); 