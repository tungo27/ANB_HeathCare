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


        return view('doctor.appointments', compact('appointments'));
    }
}
