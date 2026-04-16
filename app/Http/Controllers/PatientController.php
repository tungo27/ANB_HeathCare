<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        // Sử dụng Eloquent để lấy danh sách bác sĩ kèm thông tin chi tiết
        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->paginate(12);
            
        return view('patient.dashboard', compact('doctors'));
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