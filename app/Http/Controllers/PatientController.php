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
    public function index(Request $request)
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
        return view('patient.booking', compact('doctor'));
    }

    public function getSlots(Request $request)
    {
        $slots = Schedule::where('doctor_id', $request->doctor_id)
            ->where('work_date', $request->date)
            ->where('is_available', 1)
            ->get();

        return response()->json($slots);
    }

    public function book(Request $request)
    {
        $scheduleId = $request->schedule_id;

        try {
            DB::transaction(function () use ($scheduleId, $request) {
                $schedule = Schedule::where('id', $scheduleId)
                    ->lockForUpdate()
                    ->first();

                if (!$schedule || $schedule->is_available != 1) {
                    throw new \Exception('Slot is no longer available.');
                }

                Appointment::create([
                    'patient_id' => Auth::id(),
                    'doctor_id' => $schedule->doctor_id,
                    'schedule_id' => $schedule->id,
                    'status' => 'confirmed',
                    'symptoms' => $request->symptoms,
                ]);

                $schedule->update(['is_available' => 2]); // Booked
            });

            return response()->json(['success' => true, 'message' => 'Appointment booked successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Doctor::with(['user', 'Specialty']);

        if ($searchTerm) {
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', "%{$searchTerm}%");
            })->orWhereHas('Specialty', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $doctors = $query->get();
        return view('patient.search', compact('doctors', 'searchTerm'));
    }


    // bệnh nhân đặt lịch với schedule slots
    public function scheduleStore(Request $request)
    {
        $request->validate([
            'schedule_slot_id' => 'required|exists:schedule_slots,id',
            'symptoms' => 'nullable|string|max:500',
        ]);

        $slot = ScheduleSlot::with('schedule')->findOrFail($request->schedule_slot_id);

        // ✅ Kiểm tra slot còn trống (double-check để tránh race condition)
        if ($slot->status !== 'available') {
            return back()->withErrors(['slot' => 'Suất khám này vừa được đặt. Vui lòng chọn suất khác.']);
        }

        // ✅ Tạo appointment
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'schedule_id' => $slot->schedule_id,
            'status' => 'confirmed',
            'symptoms' => $request->symptoms,
        ]);

        // ✅ Book slot (atomic operation)
        if (!$slot->book($appointment)) {
            $appointment->delete(); // Rollback nếu book fail
            return back()->withErrors(['slot' => 'Đặt lịch thất bại. Vui lòng thử lại.']);
        }

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Đặt lịch thành công!');
    }

    public function selectSlot(Doctor $doctor, Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $preference = $request->get('time_preference'); // morning/afternoon

        // Lấy tất cả schedules của bác sĩ vào ngày chọn
        $schedules = $doctor->schedules()
            ->where('work_date', $date)
            // ->whereIn('status', ['published'])
            ->get();

        // Lấy available slots từ các schedules đó
        $availableSlots = ScheduleSlot::whereIn('schedule_id', $schedules->pluck('id'))
            ->where('status', 'available')
            ->with('schedule')
            ->orderBy('slot_start_time')
            ->get();

        // Filter theo khung giờ nếu có
        if ($preference === 'morning') {
            $availableSlots = $availableSlots->filter(fn($s) => $s->slot_start_time < '12:00:00');
        } elseif ($preference === 'afternoon') {
            $availableSlots = $availableSlots->filter(fn($s) => $s->slot_start_time >= '13:00:00');
        }

        return view('patient.booking', compact('doctor', 'availableSlots'));
    }

      // ✅ Xác nhận đặt lịch (Atomic Transaction)
    public function store(Request $request)
    {
        $request->validate([
            'schedule_slot_id' => 'required|exists:schedule_slots,id',
            'symptoms' => 'required|string|max:500',
            'agree_terms' => 'required|accepted',
        ]);

        DB::beginTransaction();
        try {
            // 🔒 Lock row để tránh race condition (2 người cùng đặt 1 slot)
            $slot = ScheduleSlot::where('id', $request->schedule_slot_id)
                ->lockForUpdate()
                ->first();

            if (!$slot || $slot->status !== 'available') {
                DB::rollBack();
                return back()->withErrors(['slot' => 'Suất khám này vừa được đặt hoặc đã khóa.'])->withInput();
            }

            // 1. Tạo Appointment
            $appointment = Appointment::create([
                'patient_id' => Auth::id(),
                'schedule_id' => $slot->schedule_id,
                'status' => 'confirmed',
                'symptoms' => $request->symptoms,
            ]);

            // 2. Gán slot thành booked
            $slot->update([
                'status' => 'booked',
                'appointment_id' => $appointment->id,
            ]);

            DB::commit();
            
            // TODO: Gửi email/SMS xác nhận đặt lịch
           return redirect()->route('patient.appointments.show', $appointment->id)
                ->with('success', '🎉 Đặt lịch thành công! Vui lòng đến đúng giờ.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi hệ thống khi đặt lịch. Vui lòng thử lại.'])->withInput();
        }
    }
}
