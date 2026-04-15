<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
Use App\Models\Doctor;
use App\Models\Specialties;


class PatientController extends Controller
{
public function index(Request $request): View
{
    $specialties = Specialties::all(); 
    $query = Doctor::with(['user', 'Specialty']);
    if ($request->has('specialty')) {
        $query->where('specialty_id', $request->specialty);
    }
    $doctors = $query->paginate(12);

    return view('patient.dashboard', compact('doctors', 'specialties'));
}
public function showBooking($id)
{
    $doctor = Doctor::with(['user', 'Specialty'])->findOrFail($id);
    $timeSlots = [
        'Sáng' => ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00'],
        'Chiều' => ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30'],
        'Tối' => ['18:00', '18:30']
    ];
    return view('patient.booking', compact('doctor', 'timeSlots'));
}
public function search(Request $request)
{
    $searchTerm = $request->input('search');
    $query = Doctor::with(['user', 'Specialty']);
    if ($searchTerm) {

    $words = explode(' ', $searchTerm);

    $query->where(function($q) use ($searchTerm, $words) {
        $q->whereHas('user', function($userQuery) use ($searchTerm) {
            $userQuery->where('full_name', 'LIKE', "%{$searchTerm}%");
        })
        ->orWhereHas('Specialty', function($specQuery) use ($searchTerm) {
            $specQuery->where('name', 'LIKE', "%{$searchTerm}%");
        });
        foreach ($words as $word) {
            if (mb_strlen($word) > 1) { 
                $q->orWhereHas('Specialty', function($specQuery) use ($word) {
                    $specQuery->where('name', 'LIKE', "%{$word}%");
                });
            }
        }
    });
}
    $doctors = $query->get();
    return view('patient.search', compact('doctors', 'searchTerm'));
}
}
