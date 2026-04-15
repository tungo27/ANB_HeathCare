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
// dd(Doctor::with('specialty')->get()->pluck('specialty', 'full_name'));
        // Truyền biến $doctors sang view bằng hàm compact
        return view('patient.dashboard', compact('doctors'));
    }

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
public function search(Request $request)
{
    // 1. Lấy từ khóa từ ô input 'search' mà bạn đã đặt name ở file Blade
    $searchTerm = $request->input('search');

    // 2. Khởi tạo query lấy bác sĩ cùng với thông tin User và Chuyên khoa (để tránh lỗi N+1)
    $query = Doctor::with(['user', 'Specialty']);

    // 3. Logic tìm kiếm: Nếu người dùng có nhập từ khóa
    if ($searchTerm) {
        $query->where(function($q) use ($searchTerm) {
            // Tìm trong bảng users (cột name hoặc full_name)
            $q->whereHas('user', function($userQuery) use ($searchTerm) {
                $userQuery->where('full_name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('full_name', 'LIKE', "%{$searchTerm}%");
            })
            // HOẶC tìm trong bảng specialties (cột name của chuyên khoa)
            ->orWhereHas('Specialty', function($specQuery) use ($searchTerm) {
                $specQuery->where('name', 'LIKE', "%{$searchTerm}%");
            });
        });
    }

    // 4. Lấy kết quả cuối cùng
    $doctors = $query->get();

    // 5. Trả về view (nhớ truyền biến $doctors và $searchTerm sang nhé)
    return view('patient.search', compact('doctors', 'searchTerm'));
}
}
