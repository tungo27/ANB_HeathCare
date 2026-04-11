<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class DoctorController extends Controller
{
    public function index()
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

    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(StoreDoctorRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1. Tạo User và gán role trực tiếp
            $user = User::create([
                'full_name' => $request->full_name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
                'role'      => 'doctor',
            ]);

            // 2. Tạo Doctor
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
            return redirect()->route('admin.doctors.index')->with('success', 'Bác sĩ đã được thêm thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo Bác sĩ: ' . $e->getMessage());

            // Trả về kèm thông báo lỗi cụ thể để debug
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit(Doctor $doctor)
    {


        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        try {
            DB::beginTransaction();

            // 1. Cập nhật thông tin User (Bảng users)
            // Dùng $doctor->user sẽ trả về instance của User nhờ quan hệ belongsTo
            $doctor->user->update([
                'full_name'  => $request->full_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
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
            return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật Bác sĩ: ' . $e->getMessage()); //
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(Doctor $doctor)
    {
        try {
            DB::beginTransaction();

            // Lấy thông tin user liên kết
            $user = $doctor->user;

            // Xóa hồ sơ bác sĩ trước, sau đó xóa user liên kết
            $doctor->delete();
            if ($user) {
                $user->delete();
            }

            DB::commit();
            return redirect()->route('admin.doctors.index')->with('success', 'Đã xóa hồ sơ Bác sĩ thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi xóa Bác sĩ: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
