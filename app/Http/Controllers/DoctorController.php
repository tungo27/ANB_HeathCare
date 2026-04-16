<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoctorController extends Controller
{
    // app/Http/Controllers/DoctorController.php

    public function index(Request $request)
    {
        // 1. Khởi tạo query lấy lịch của bác sĩ đang đăng nhập
        $query = Schedule::where('user_id', Auth::id());

        // 2. Kiểm tra tham số 'status' trên URL để lọc dữ liệu
        // status=available hoặc ?status=booked
        if ($request->has('status')) {
            if ($request->status === 'available') {
                $query->where('status', 1);
            } elseif ($request->status === 'booked') {
                $query->where('status', 2);
            }
        }

        // 3. Sắp xếp và phân trang
        // Quan trọng: Thêm withQueryString() để giữ bộ lọc khi người dùng bấm sang trang 2, 3...
        $schedules = $query->orderBy('work_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(10)
            ->withQueryString();

        // 4. Trả về view
        return view('doctor.dashboard', compact('schedules'));
    }

    public function appointments(Request $request): View
    {
        // Lấy danh sách lịch hẹn của bác sĩ đang đăng nhập
        // Giả sử bảng appointments có trường doctor_id liên kết với id của User(bác sĩ)
        $query = Appointment::where('doctor_id', Auth::id());

        // Lọc theo trạng thái nếu có
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sắp xếp lịch hẹn mới nhất lên đầu và phân trang
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('doctor.appointments', compact('appointments'));
    }
}
