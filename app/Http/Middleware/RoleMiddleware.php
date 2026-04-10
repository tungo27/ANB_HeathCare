<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if ($userRole !== $role) {
            return match ($userRole) {
                'admin'   => redirect()->route('admin.dashboard'),
                'doctor'  => redirect()->route('doctor.dashboard'),
                'patient' => redirect()->route('patient.dashboard'),
                default   => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}
