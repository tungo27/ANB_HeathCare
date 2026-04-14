<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        // Lấy tất cả người dùng có vai trò là bác sĩ (giả sử bạn có cột role)
        // Nếu chưa có dữ liệu thật, bạn có thể tạm thời dùng: User::all()
        // Trong Controller
        $doctors = User::where('role', 'doctor')->paginate(12); // Chỉ lấy 12 người mỗi trang

        // Truyền biến $doctors sang view bằng hàm compact
        return view('patient.dashboard', compact('doctors'));
    }
}
