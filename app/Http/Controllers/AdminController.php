<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialties;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.dashboard', [
            'totalDoctors'        => Doctor::count(),
            // 'todayPatients'       => Appointment::whereDate('appointment_date', today())->distinct('patient_id')->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'recentDoctors'       => Doctor::with(['user', 'specialty'])->latest('user_id')->take(10)->get(),
        ]);
    }

    public function doctorManagement()
    {
        $doctors = Doctor::query()
            // Join để có thể sắp xếp theo bảng users
            ->join('users', 'doctors.user_id', '=', 'users.id')
            // CHỈ định lấy id và các cột của doctors để không bị id của users đè lên
            ->select('doctors.*')
            // Load các mối quan hệ để hiển thị tên/email/chuyên khoa
            ->with(['user', 'specialty'])
            // Sắp xếp theo ngày tạo bên bảng users
            ->orderBy('users.created_at', 'desc')
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function doctorCreate()
    {
        $specialties = Specialties::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    // Tạm thời đổi StoreDoctorRequest thành Request để xem có phải lỗi do Validate ẩn không
    public function doctorStore(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Tạo User (Lưu ý: Đổi 'name' thành 'full_name' nếu DB của bạn dùng full_name)
            $user = User::create([
                'full_name' => $request->full_name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make('minhtu111'),
                'role'      => 'doctor',
            ]);


            // 3. Tạo Doctor
            // Vì bạn dùng $primaryKey = 'user_id' và $incrementing = false
            // Chúng ta truyền trực tiếp user_id vào
            Doctor::create([
                'user_id'             => $user->id,
                'specialty_id'        => $request->specialty_id,
                'qualification'       => $request->qualification,
                'years_of_experience' => $request->years_of_experience,
                'consultation_fee'    => $request->consultation_fee,
                'bio'                 => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.doctors.doctorManagement')->with('success', 'Bác sĩ đã được thêm thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo Bác sĩ: ' . $e->getMessage());

            // TẠM THỜI IN THẲNG LỖI RA MÀN HÌNH TRÌNH DUYỆT ĐỂ KIỂM TRA:
            dd('Lỗi Database khi lưu:', $e->getMessage(), 'Dữ liệu gửi lên:', $request->all());

            // return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function doctorEdit(Doctor $doctor)
    {


        $specialties = Specialties::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function doctorUpdate(UpdateDoctorRequest $request, Doctor $doctor)
    {
        try {
            DB::beginTransaction();

            // 1. Cập nhật thông tin User (Bảng users)
            // Dùng $doctor->user sẽ trả về instance của User nhờ quan hệ belongsTo
            $doctor->user->update([
                'full_name'  => $request->full_name,
                'phone'      => $request->phone,
                'email' => $request->email,
            ]);

            // 2. Cập nhật thông tin Doctor (Bảng doctors)
            // Khi đã khai báo $primaryKey là user_id, hàm này sẽ chạy đúng
            $doctor->update([
                'specialty_id'        => $request->specialty_id,
                'qualification'       => $request->qualification,
                'years_of_experience' => $request->years_of_experience,
                'consultation_fee'    => $request->consultation_fee,
                'bio'                 => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.doctors.doctorManagement')->with('success', 'Cập nhật thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật Bác sĩ: ' . $e->getMessage()); //
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function doctorDestroy(Doctor $doctor)
    {
        // Logic theo yêu cầu: Chỉ xóa record Doctor (Soft/Hard delete tùy thuộc migration)
        $doctor->delete();

        return redirect()->route('admin.doctors.doctorManagement')->with('success', 'Đã xóa hồ sơ Bác sĩ thành công.');
    }

    public function scheduleCreate()
    {
        $doctors = Doctor::with(['user', 'specialty'])
            ->where('is_active', true)
            ->get();
        $schedule = new Schedule();
        return view('admin.schedules.create', compact('doctors', 'schedule'));
    }

    public function scheduleStore(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,user_id',
            'work_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:20',
            'status' => 'required|in:draft,published,closed',
            'slot_duration' => 'required|integer|in:15,20,30,45,60',
            'break_time' => 'required|integer|min:0|max:30',
            'blocked_times' => 'nullable|json',
        ]);

        DB::beginTransaction();
        try {
            $schedule = Schedule::create([
                'doctor_id' => $validated['doctor_id'],
                'work_date' => $validated['work_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'room' => $validated['room'],
                'status' => $validated['status'],
                'slot_duration_minutes' => $validated['slot_duration'],
                'break_minutes' => $validated['break_time'],
                'blocked_times' => $validated['blocked_times'],
            ]);

            // Tự động sinh slots nếu admin chọn "Lưu & Sinh slots ngay"
            if ($request->action === 'save_and_generate') {
                $this->generateSlots($schedule);
            }

            DB::commit();
            return redirect()->route('admin.schedules.slots', $schedule->id)
                ->with('success', 'Tạo ca thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi hệ thống: ' . $e->getMessage()])->withInput();
        }
    }

    public function showSlots(Schedule $schedule)
    {
        $schedule->load(['slots.appointment.patient', 'doctor.user']);

        // ✅ Pre-calculate is_past cho từng slot
        $now = now();
        $schedule->slots->each(function ($slot) use ($now, $schedule) {
            // ✅ Xử lý slot_end_time có thể là time hoặc datetime
            $endTimeStr = $slot->slot_end_time;

            // Nếu slot_end_time đã là datetime, chỉ lấy phần time
            if (strpos($endTimeStr, ' ') !== false) {
                // '2026-04-23 08:30:00' → '08:30:00'
                $endTimeStr = explode(' ', $endTimeStr)[1];
            }

            // Lấy work_date (chỉ phần ngày)
            $workDate = \Carbon\Carbon::parse($schedule->work_date)->format('Y-m-d');

            // Ghép lại: '2026-04-24 08:30:00'
            $slotEndTime = \Carbon\Carbon::parse("{$workDate} {$endTimeStr}");

            $slot->is_past = $slotEndTime->isPast();
            $slot->is_editable = !$slot->is_past && $slot->status !== 'maintenance';
        });

        $schedule->slots = $schedule->slots->sortBy('slot_number');

        $patients = \App\Models\User::where('role', 'patient')
            ->select('id', 'full_name as name', 'phone')
            ->orderBy('full_name')
            ->get();

        return view('admin.schedules.slots', compact('schedule', 'patients'));
    }



    // phần Schedule Slot mới
    public function generateSlots(Schedule $schedule)
    {
        $slots = [];

        // ✅ TRÍCH XUẤT CHỈ PHẦN NGÀY (Y-m-d)
        $dateStr = Carbon::parse($schedule->work_date)->format('Y-m-d');

        // Ghép ngày với giờ start/end chính xác
        $currentTime = Carbon::parse("{$dateStr} {$schedule->start_time}");
        $endTime = Carbon::parse("{$dateStr} {$schedule->end_time}");

        $slotNumber = 1;

        while ($currentTime->copy()->addMinutes(30)->lte($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes(30);

            if (!$this->isTimeBlocked($schedule, $currentTime, $slotEnd)) {
                $slots[] = [
                    'schedule_id'     => $schedule->id,
                    'slot_number'     => $slotNumber,
                    'slot_start_time' => $currentTime->format('H:i:s'),
                    'slot_end_time'   => $slotEnd->format('H:i:s'),
                    'status'          => 'available',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
                $slotNumber++;
            }

            // Tăng thời gian: 30 phút khám + 5 phút nghỉ
            $currentTime = $slotEnd->addMinutes(5);
        }

        if (!empty($slots)) {
            ScheduleSlot::insert($slots);
        }
    }

    private function isTimeBlocked(Schedule $schedule, Carbon $start, Carbon $end): bool
    {
        // Logic check if doctor has blocked this specific time range
        return false;
    }

    public function scheduleIndex(Request $request)
    {
        $query = Schedule::with(['doctor.user', 'slots']);

        if ($request->filled('doctor_id')) $query->where('doctor_id', $request->doctor_id);
        if ($request->filled('date')) $query->where('work_date', $request->date);
        if ($request->filled('status')) $query->where('status', $request->status);

        $schedules = $query->orderBy('work_date', 'desc')->paginate(15);
        $doctors = Doctor::with('user')->where('is_active', true)->get();

        return view('admin.schedules.index', compact('schedules', 'doctors'));
    }

    public function toggleSlotStatus(Request $request, ScheduleSlot $slot)
    {
        $request->validate(['action' => 'required|in:block,unblock,maintenance']);

        $newStatus = match ($request->action) {
            'block' => 'blocked',
            'unblock' => 'available',
            'maintenance' => 'maintenance',
        };

        // Không cho đổi trạng thái nếu slot đã booked
        if ($slot->status === 'booked' && $newStatus !== 'booked') {
            return response()->json(['success' => false, 'message' => 'Không thể thay đổi slot đã có lịch hẹn.']);
        }

        $slot->update([
            'status' => $newStatus,
            'internal_note' => $request->note ?? ($slot->status === 'blocked' ? 'Block thủ công' : null),
        ]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function bulkSlotAction(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'new_status' => 'required|in:available,blocked,maintenance',
            'scope' => 'required|in:all,editable,filtered,selected',
            'slot_ids' => 'nullable|array',
            'note' => 'nullable|string|max:255',
        ]);

        $query = ScheduleSlot::where('schedule_id', $validated['schedule_id']);

        // Filter theo scope
        if ($validated['scope'] === 'selected' && !empty($validated['slot_ids'])) {
            $query->whereIn('id', $validated['slot_ids']);
        } elseif ($validated['scope'] === 'editable') {
            // ✅ Chỉ update slots chưa kết thúc
            $now = now();
            $query->whereRaw("CONCAT(
            (SELECT work_date FROM schedules WHERE id = schedule_slots.schedule_id), 
            ' ', 
            slot_end_time
        ) > ?", [$now->format('Y-m-d H:i:s')]);
        } elseif ($validated['scope'] === 'filtered') {
            if ($request->filled('filter_status')) {
                $query->where('status', $request->filter_status);
            }
        }

        // ✅ Không update slots đã booked để tránh hủy lịch
        $affected = $query->where('status', '!=', 'booked')->update([
            'status' => $validated['new_status'],
            'internal_note' => $validated['note']
                ? DB::raw("CONCAT(IFNULL(internal_note, ''), '\n[Bulk: " . addslashes($validated['note']) . " - " . now()->format('d/m H:i') . "]')")
                : null,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', "Đã cập nhật {$affected} slots.");
    }


    // 🔧 Helper kiểm tra overlap thời gian
    private function isOverlapping($start, $end, $blockStart, $blockEnd): bool
    {
        $bs = Carbon::parse("{$start->format('Y-m-d')} {$blockStart}");
        $be = Carbon::parse("{$start->format('Y-m-d')} {$blockEnd}");
        return $start->lt($be) && $end->gt($bs);
    }

    // app/Http/Controllers/AdminController.php

    /**
     * Gán thủ công bệnh nhân vào một slot trống
     * POST /admin/slots/assign
     */
    public function assignSlot(Request $request)
    {
        $validated = $request->validate([
            'slot_id'      => 'required|exists:schedule_slots,id',
            'patient_id'   => 'required|exists:users,id',
            'symptoms'     => 'nullable|string|max:500',
        ], [
            'slot_id.exists' => 'Suất khám không tồn tại.',
            'patient_id.exists' => 'Bệnh nhân không tồn tại.',
        ]);

        DB::beginTransaction();
        try {
            // 🔒 Lock row để tránh race condition
            $slot = ScheduleSlot::where('id', $validated['slot_id'])
                ->lockForUpdate()
                ->first();

            // Kiểm tra slot còn trống không
            if (!$slot || $slot->status !== 'available') {
                DB::rollBack();
                return back()->withErrors(['slot' => 'Suất khám này vừa được đặt hoặc đã khóa.'])->withInput();
            }

            // Kiểm tra bệnh nhân có role='patient' không
            $patient = User::find($validated['patient_id']);
            if (!$patient || $patient->role !== 'patient') {
                DB::rollBack();
                return back()->withErrors(['patient' => 'Người được chọn không phải là bệnh nhân.'])->withInput();
            }

            // 1. Tạo Appointment
            $appointment = Appointment::create([
                'patient_id'   => $patient->id,
                'schedule_id'  => $slot->schedule_id,
                'status'       => 'confirmed',
                'symptoms'     => $validated['symptoms'] ?? null,
                // 'note' => $validated['note'] ?? null, // Nếu có cột note
            ]);

            // 2. Cập nhật slot thành booked
            $slot->update([
                'status' => 'booked',
                'appointment_id' => $appointment->id,
            ]);

            DB::commit();
            return back()->with('success', '✅ Đã gán bệnh nhân vào suất khám thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Assign slot error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Lỗi hệ thống: ' . $e->getMessage()])->withInput();
        }
    }

    public function blockSlot(Request $request, $slotId)
    {
        try {
            $slot = ScheduleSlot::with('schedule')->findOrFail($slotId);

            $isPast = $this->checkIsSlotPast($slot);

            if ($isPast) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi slot đã kết thúc.'
                ], 403);
            }

            // ✅ CHECK 2: Chỉ block slot đang available
            if ($slot->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => "Slot không ở trạng thái available (hiện tại: {$slot->status})"
                ], 400);
            }

            $slot->status = 'blocked';
            $slot->internal_note = ($slot->internal_note ?? '') . "\n[Blocked by admin: " . now()->format('d/m H:i') . ']';
            if (Auth::check()) {
                $slot->updated_by = Auth::id();
            }
            $slot->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã block slot thành công',
                'slot' => ['id' => $slot->id, 'status' => $slot->status]
            ]);
        } catch (\Exception $e) {
            Log::error('[BLOCK] Error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function unblockSlot(Request $request, $slotId)
    {
        try {
            $slot = ScheduleSlot::with('schedule')->findOrFail($slotId);

            // ✅ Tính is_past thủ công
            $isPast = $this->checkIsSlotPast($slot);

            if ($isPast) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot đã kết thúc, không thể mở lại.'
                ], 403);
            }

            // ✅ CHECK 2: Chỉ unblock slot đang blocked
            if ($slot->status !== 'blocked') {
                return response()->json([
                    'success' => false,
                    'message' => "Slot không ở trạng thái blocked (hiện tại: {$slot->status})"
                ], 400);
            }

            $slot->status = 'available';
            $slot->internal_note = ($slot->internal_note ?? '') . "\n[Unblocked by admin: " . now()->format('d/m H:i') . ']';
            if (Auth::check()) {
                $slot->updated_by = Auth::id();
            }
            $slot->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã mở block slot',
                'slot' => ['id' => $slot->id, 'status' => $slot->status]
            ]);
        } catch (\Exception $e) {
            Log::error('[UNBLOCK] Error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * ❌ Hủy appointment trong slot
     */
    public function cancelSlotAppointment(Request $request, $slotId)
    {
        try {
            $slot = ScheduleSlot::with('appointment')->findOrFail($slotId);

            // ✅ Tính is_past thủ công
            $isPast = $this->checkIsSlotPast($slot);

            if ($isPast) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy appointment của slot đã kết thúc.'
                ], 403);
            }

            if ($slot->status !== 'booked' || !$slot->appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot này không có appointment để hủy'
                ], 400);
            }

            $appointment = $slot->appointment;

            DB::transaction(function () use ($slot, $appointment) {
                // 1. Hủy appointment
                $appointment->status = 'cancelled';
                $appointment->cancellation_reason = 'Hủy bởi admin';
                $appointment->cancelled_at = now();
                if (Auth::check() && Schema::hasColumn('appointments', 'cancelled_by')) {
                    $appointment->cancelled_by = Auth::id();
                }
                $appointment->save();

                // 2. Giải phóng slot
                $slot->status = 'available';
                $slot->appointment_id = null;
                $slot->internal_note = ($slot->internal_note ?? '') . "\n[Cancelled: " . now()->format('d/m H:i') . ']';
                if (Auth::check() && Schema::hasColumn('schedule_slots', 'updated_by')) {
                    $slot->updated_by = Auth::id();
                }
                $slot->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Đã hủy appointment và giải phóng slot',
                'slot' => ['id' => $slot->id, 'status' => $slot->status],
                'appointment' => ['id' => $appointment->id, 'status' => $appointment->status]
            ]);
        } catch (\Exception $e) {
            Log::error('[CANCEL] Error', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    // ✅ Helper method kiểm tra slot đã qua
    private function checkIsSlotPast(ScheduleSlot $slot): bool
    {
        if (!$slot->schedule) {
            return false;
        }

        try {
            // Extract date từ work_date
            $workDateStr = $slot->schedule->work_date;
            if (strpos($workDateStr, ' ') !== false) {
                $workDateStr = explode(' ', $workDateStr)[0];
            }

            // Extract time từ slot_end_time
            $endTimeStr = $slot->slot_end_time;
            if (strpos($endTimeStr, ' ') !== false) {
                $endTimeStr = explode(' ', $endTimeStr)[1];
            }

            $slotEndTime = Carbon::parse("{$workDateStr} {$endTimeStr}");
            return $slotEndTime->isPast();
        } catch (\Exception $e) {
            Log::error('checkIsSlotPast error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
