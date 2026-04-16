<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
Use App\Models\Doctor;
use App\Models\Specialties;
use Illuminate\Support\Facades\DB;

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
 public function Appointment()
    {
        // Lưu ý: Tên bảng trong Database là 'specialties' (viết thường)
        $specialties = DB::table('specialties')->where('is_active', true)->get();
        return view('patient.Appointment.make_appointment', compact('specialties'));
    }

    public function getDoctor(Request $request) 
    {
        $specialtyId = $request->query('specialty_id');

        // Nếu không có specialty_id, trả về mảng rỗng để tránh lỗi
        if (!$specialtyId) {
            return response()->json([]);
        }

        $doctors = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->leftJoin('doctor_ratings', 'doctors.user_id', '=', 'doctor_ratings.doctor_id')
            // SỬA LỖI: Tên cột đúng là doctors.specialty_id và users.is_active
            ->where('doctors.specialty_id', $specialtyId)
            ->where('users.is_active', true)
            ->select(
                'users.id',
                'users.full_name',
                'users.avatar_url',
                'doctors.qualification',
                'doctors.years_of_experience',
                'doctors.consultation_fee',
                'doctors.bio',
                'specialties.name as specialty_name',
                DB::raw('IFNULL(ROUND(AVG(doctor_ratings.rating), 1), 0) as avg_rating'),
                DB::raw('COUNT(doctor_ratings.id) as total_ratings')
            )
            ->groupBy(
                'users.id', 
                'users.full_name', 
                'users.avatar_url',
                'doctors.qualification', 
                'doctors.years_of_experience',
                'doctors.consultation_fee', 
                'doctors.bio', 
                'specialties.name'
            )
            ->get();

        return response()->json($doctors);
    }
}




   

