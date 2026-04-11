<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class
        ]);

        $middleware->redirectUsersTo(function () {
        if (!Auth::check()) {
            return route('login');
        }
        return match(Auth::user()->role) {
            'admin'   => route('admin.dashboard'),
            'doctor'  => route('doctor.dashboard'),
            'patient' => route('patient.dashboard'),
            default   => route('login'),
        };
    });

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
