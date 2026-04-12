<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
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
                $query->where('is_available', 1);
            } elseif ($request->status === 'booked') {
                $query->where('is_available', 0);
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
}
