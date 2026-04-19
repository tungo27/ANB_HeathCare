<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialties;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // Lấy danh sách bác sĩ để hiển thị trên Dropdown
        $doctors = Doctor::with('user')->get();
        return view('admin.schedules.create', compact('doctors'));
    }

    public function scheduleStore(Request $request)
    {
        $request->validate([
            'doctor_id'     => 'required',
            'room'          => 'required|string|max:255',
            'work_date'     => 'required|date|after_or_equal:today',
            'shifts'        => 'required|array|min:1',
            'slot_duration' => 'required|integer|min:10',
        ]);

        $userId = $request->doctor_id;
        $workDate = $request->work_date;
        $room = $request->room;
        $slotDuration = (int) $request->slot_duration;
        $shifts = $request->shifts;

        try {
            DB::beginTransaction();

            // Tái sử dụng logic chẻ nhỏ ca theo khung giờ
            if (in_array('morning', $shifts)) {
                $this->generateSlotsForPeriod($userId, $workDate, '08:00', '12:00', $slotDuration, $room);
            }

            if (in_array('afternoon', $shifts)) {
                $this->generateSlotsForPeriod($userId, $workDate, '13:30', '17:00', $slotDuration, $room);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Lịch làm việc đã được tạo thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo lịch làm việc: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo lịch: ' . $e->getMessage());
        }
    }

    // Hàm chẻ thời gian được chuyển từ GenerateDailySchedules Command sang
    private function generateSlotsForPeriod($userId, $workDate, $startTime, $endTime, $slotDuration, $room)
    {
        $current = Carbon::parse("$workDate $startTime");
        $end = Carbon::parse("$workDate $endTime");

        while ($current < $end) {
            $slotStart = $current->copy();
            $slotEnd = $current->copy()->addMinutes($slotDuration);

            if ($slotEnd > $end) {
                break;
            }

            $strStartTime = $slotStart->format('H:i:s');
            $strEndTime = $slotEnd->format('H:i:s');

            // Kiểm tra xem giờ này đã tồn tại chưa (chống trùng lặp nếu lỡ ấn tạo 2 lần)
            $exists = Schedule::where('doctor_id', $userId)
                ->where('work_date', $workDate)
                ->where('start_time', $strStartTime)
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'doctor_id'     => $userId,
                    'room'          => $room,
                    'work_date'     => $workDate,
                    'start_time'    => $strStartTime,
                    'end_time'      => $strEndTime,
                    'slot_duration' => $slotDuration,
                    'max_patients'  => 10,
                    'status'        => 1,
                ]);
            }

            $current->addMinutes($slotDuration);
        }
    }
}
