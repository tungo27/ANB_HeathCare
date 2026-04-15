<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
Use App\Models\Doctor;


class PatientController extends Controller
{
    public function index(Request $request): View
    {
        // Lấy tất cả người dùng có vai trò là bác sĩ (giả sử bạn có cột role)
        // Nếu chưa có dữ liệu thật, bạn có thể tạm thời dùng: User::all()
        // Trong Controller
        $doctors = User::where('role', 'doctor')->paginate(12); // Chỉ lấy 12 người mỗi trang
// Thêm dòng này vào trước lệnh return view để debug
    $doctors = Doctor::with('Specialty')->get();
    $doctors->each(function ($doctor) {
        $doctor->full_name = $doctor->user->full_name; // Giả sử bạn có cột full_name trong bảng users
    });
// dd(Doctor::with('Specialty')->get()->pluck('Specialty', 'full_name'));
        // Truyền biến $doctors sang view bằng hàm compact
        return view('patient.dashboard', compact('doctors'));
    }

    // Ví dụ logic tạo khung giờ trong Controller
// public function getAvailableSlots($doctorId, $date) {
//     $morningSlots = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00'];
//     $afternoonSlots = ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
//     $eveningSlots = ['18:00', '18:30']; // Chỉ có 2 lần khám

//     return array_merge($morningSlots, $afternoonSlots, $eveningSlots);
// }

public function showBooking($id)
{
    // Tìm thông tin bác sĩ dựa trên ID truyền sang
    $doctor = Doctor::with(['user', 'Specialty'])->findOrFail($id);
    $timeSlots = [
        'Sáng' => ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00'],
        'Chiều' => ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30'],
        'Tối' => ['18:00', '18:30']
    ];
    
    // Trả về file booking.blade.php mà bạn vừa đặt tên lúc nãy
    return view('patient.booking', compact('doctor', 'timeSlots'));
}
}
