<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Specialties;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    // Hiển thị danh sách bác sĩ (Trang Dashboard)
    public function index(Request $request)
    {
        $specialties = Specialties::all();
        $query = Doctor::with(['user', 'specialty']);

        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        $doctors = $query->paginate(12);
        return view('patient.dashboard', compact('doctors', 'specialties'));
    }

    public function search(Request $request)
    {
        $specialties = Specialties::all();
        $search = trim($request->get('search', ''));

        $query = Doctor::with(['user', 'specialty']);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%");
                })
                ->orWhereHas('specialty', function ($query) use ($search) {
                    $query->where('name', $search);
                });
            });
        }

        $doctors = $query->paginate(12)->withQueryString();
        $searchTerm = $search;
        return view('patient.search', compact('doctors', 'searchTerm'));
    }

    // Hiển thị trang chọn suất khám (Slots)
    public function selectSlot(Doctor $doctor, Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $preference = $request->get('time_preference');

        // Lấy các lịch làm việc đã xuất bản của bác sĩ trong ngày
        $schedules = $doctor->schedules()
            ->where('work_date', $date)
            ->where('status', 'published')
            ->pluck('id');

        // Truy vấn các slot còn trống
        $query = ScheduleSlot::whereIn('schedule_id', $schedules)
            ->where('status', 'available')
            ->with('schedule')
            ->orderBy('slot_start_time');

        // Lọc theo buổi nếu có yêu cầu
        if ($preference === 'morning') {
            $query->whereTime('slot_start_time', '<', '12:00:00');
        } elseif ($preference === 'afternoon') {
            $query->whereTime('slot_start_time', '>=', '12:00:00');
        }

        $availableSlots = $query->get();

        return view('patient.Booking.select-slot', compact('doctor', 'availableSlots'));
    }

    // Xử lý lưu đặt lịch (Atomic Transaction)
    // Tên hàm này phải khớp với Route: patient.booking.confirm
    public function bookWithSlot(Request $request)
    {
        $request->validate([
            'schedule_slot_id' => 'required|exists:schedule_slots,id',
            'symptoms'         => 'required|string|max:500',
            'agree_terms'      => 'required|accepted',
        ]);

        return DB::transaction(function () use ($request) {
            // 🔒 Lock row để ngăn race condition
            $slot = ScheduleSlot::where('id', $request->schedule_slot_id)
                ->lockForUpdate()
                ->first();

            if (!$slot || $slot->status !== 'available') {
                return back()->withErrors(['slot' => 'Suất khám này vừa có người khác đặt. Vui lòng chọn giờ khác.']);
            }

            // 1. Tạo bản ghi cuộc hẹn

            $appointment = Appointment::create([
                'patient_id'      => Auth::id(),


                // ✅ GIỮ LẠI CÁC DÒNG NÀY:
                'schedule_id'     => $slot->schedule_id,
                'status'          => 'pending',
                'symptoms'        => $request->symptoms,
            ]);

            // 2. Cập nhật trạng thái Slot và gắn ID cuộc hẹn vào slot
            $slot->update([
                'status'         => 'booked',
                'appointment_id' => $appointment->id,
            ]);

            return redirect()->route('patient.appointments.index')
                ->with('success', '🎉 Đặt lịch thành công! Mã lịch hẹn: #' . $appointment->id);
        });
    }

    // Các hàm bổ trợ khác (Search, Appointments list...)
    public function myAppointments()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with(['doctor.user', 'doctor.specialty', 'schedule', 'slot'])
            ->latest()
            ->get();
        return view('patient.appointments.index', compact('appointments'));
    }
}
