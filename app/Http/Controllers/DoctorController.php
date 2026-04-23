<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShiftAssignment;
use App\Models\Schedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;
        $assignments = $doctor->shiftAssignments()->with('shift')->where('status', 'pending')->get();
        $appointments = Appointment::whereHas('schedule', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->user_id);
        })
            ->with(['patient', 'schedule.doctor']) // ✅ Load thêm doctor từ schedule nếu cần
            ->get();

        return view('doctor.dashboard', compact('assignments', 'appointments'));
    }

    public function acceptShift($id)
    {
        $assignment = ShiftAssignment::findOrFail($id);

        DB::transaction(function () use ($assignment) {
            $assignment->update(['status' => 'accepted']);

            $shift = $assignment->shift;
            $start = Carbon::parse($assignment->work_date . ' ' . $shift->start_time);
            $end = Carbon::parse($assignment->work_date . ' ' . $shift->end_time);

            while ($start->copy()->addMinutes(30)->lte($end)) {
                Schedule::create([
                    'doctor_id' => $assignment->doctor_id,
                    'work_date' => $assignment->work_date,
                    'start_time' => $start->format('H:i:s'),
                    'end_time' => $start->copy()->addMinutes(30)->format('H:i:s'),
                    'is_available' => 1, // Free
                ]);
                $start->addMinutes(30);
            }
        });

        return back()->with('success', 'Shift accepted and slots generated.');
    }

    public function rejectShift($id)
    {
        $assignment = ShiftAssignment::findOrFail($id);
        $assignment->update(['status' => 'rejected']);
        return back()->with('info', 'Shift rejected.');
    }

    public function appointments()
    {
        $query = Appointment::whereHas('schedule', function ($query) {
            $query->where('doctor_id', Auth::id());
        });
        // ✅ Thêm lọc theo status nếu có param
        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        $appointments = $query->with(['patient', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('doctor.appointments.appointments', compact('appointments'));
    }


     public function index()
    {
        return view('doctor.schedule');
    }

     // 📡 API trả về events cho FullCalendar
    public function calendarEvents()
    {
        $doctorId = Auth::user()->doctor->user_id;
        $schedules = Schedule::where('doctor_id', $doctorId)
            ->where('status', 'published')
            ->get(['id', 'work_date', 'start_time', 'end_time']);

        $events = $schedules->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => "Ca khám: {$s->start_time} - {$s->end_time}",
                'start' => "{$s->work_date}T{$s->start_time}",
                'end' => "{$s->work_date}T{$s->end_time}",
                'backgroundColor' => '#0d6efd',
                'url' => route('doctor.schedule.detail', $s->id),
            ];
        });

        return response()->json($events);
    }

     // 📋 Chi tiết ca + danh sách slots
    public function detail(Schedule $schedule)
    {
        // Kiểm tra quyền: chỉ bác sĩ được phân ca mới xem được
        if ($schedule->doctor_id !== Auth::user()->doctor->user_id) {
            abort(403, 'Bạn không có quyền xem ca này.');
        }

        $schedule->load(['slots.appointment.patient', 'slots.appointment']);
        return view('doctor.appointments.detail', compact('schedule'));
    }

      // 🩺 Hoàn tất khám & lưu kết quả
    public function complete(Request $request, Appointment $appointment)
    {
        // Chỉ bác sĩ phụ trách mới được hoàn tất
        if ($appointment->schedule->doctor_id !== Auth::user()->doctor->user_id) {
            abort(403);
        }

        $request->validate([
            'diagnosis_result' => 'required|string|max:1000',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $appointment) {
            $appointment->update([
                'status' => 'completed',
                'diagnosis_result' => $request->diagnosis_result,
                // 'note' => $request->note, // Nếu có cột note
            ]);
        });

        return back()->with('success', '✅ Đã lưu kết quả khám và hoàn tất lịch hẹn.');
    }


    // ❌ Hủy/Từ chối lịch hẹn (nếu cần)
    public function cancel(Request $request, Appointment $appointment)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $appointment) {
            $appointment->update([
                'status' => 'rejected', // hoặc 'cancelled' tùy nghiệp vụ
                'cancellation_reason' => $request->reason,
                'cancelled_at' => now(),
            ]);

            // Giải phóng slot
            $appointment->slot()->update(['status' => 'available', 'appointment_id' => null]);
        });

        return back()->with('success', 'Lịch hẹn đã được hủy.');
    }

      public function showAppointment(Appointment $appointment)
    {
        // 🔐 Kiểm tra quyền: Chỉ bác sĩ được phân công mới xem được
        if ($appointment->schedule->doctor_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem lịch hẹn này.');
        }

        // 📦 Load relationships cần thiết
        $appointment->load(['patient', 'schedule','slot']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Cập nhật trạng thái lịch hẹn (confirm/complete/cancel)
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // 🔐 Validate quyền
        if ($appointment->schedule->doctor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:confirm,complete,cancel,reschedule',
            'diagnosis_result' => 'nullable|required_if:action,complete|string|max:2000',
            'note' => 'nullable|string|max:500',
            'cancellation_reason' => 'nullable|required_if:action,cancel|string|max:255',
            'new_datetime' => 'nullable|required_if:action,reschedule|date|after:now',
        ]);

        DB::transaction(function () use ($validated, $appointment) {
            match ($validated['action']) {
                'confirm' => $appointment->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]),

                'complete' => $appointment->update([
                    'status' => 'completed',
                    'diagnosis_result' => $validated['diagnosis_result'],
                    'note' => $validated['note'] ?? null,
                    'completed_at' => now(),
                ]),

                'cancel' => $appointment->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id(),
                ]),

                'reschedule' => $appointment->update([
                    'status' => 'rescheduled',
                    'note' => "Yêu cầu đổi lịch: {$validated['new_datetime']}",
                ]),
            };
        });

        return back()->with('success', match ($validated['action']) {
            'confirm' => '✅ Đã xác nhận lịch hẹn',
            'complete' => '✅ Đã hoàn tất khám và lưu kết quả',
            'cancel' => '❌ Đã hủy lịch hẹn',
            'reschedule' => '🔄 Đã yêu cầu đổi lịch',
        });
    }
}
