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

    public function store()
    // public function store(StoreDoctorRequest $request)
    {
        // try {
        //     DB::beginTransaction();

        //     // Bước 1: Tạo record User mới
        //     $user = User::create([
        //         'name'     => $request->name,
        //         'email'    => $request->email,
        //         'password' => Hash::make('password123'),
        //     ]);

        //     // Bước 2: Gán quyền (Cần đảm bảo package Spatie Permission đã được setup)
        //     $user->assignRole('Doctor');

        //     // Bước 3: Tạo record Doctor map với $user->id vừa tạo
        //     Doctor::create([
        //         'user_id'      => $user->id,
        //         'specialty_id' => $request->specialty_id,
        //         'qualification'       => $request->qualification,
        //         'years_of_experience' => $request->years_of_experience,
        //         'consultation_fee'    => $request->consultation_fee,
        //         'bio'          => $request->bio,
        //     ]);

        //     DB::commit();
        //     return redirect()->route('admin.doctors.index')->with('success', 'Bác sĩ đã được thêm thành công.');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::error('Lỗi khi tạo Bác sĩ: ' . $e->getMessage());

        //     return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo bác sĩ. Vui lòng kiểm tra lại!');
        // }
    }

    public function edit()
    // public function edit(Doctor $doctor)
    {
        // $specialties = Specialty::all();
        // return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update()
    // public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        // try {
        //     DB::beginTransaction();

        //     // Cập nhật thông tin User
        //     $doctor->user->update([
        //         'name'  => $request->name,
        //         'email' => $request->email,
        //     ]);

        //     // Cập nhật thông tin Doctor
        //     $doctor->update([
        //         'specialty_id' => $request->specialty_id,
        //         'qualification'       => $request->qualification,
        //         'years_of_experience' => $request->years_of_experience,
        //         'consultation_fee'    => $request->consultation_fee,
        //         'bio'          => $request->bio,
        //     ]);

        //     DB::commit();
        //     return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật hồ sơ bác sĩ thành công.');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::error('Lỗi khi cập nhật Bác sĩ: ' . $e->getMessage());
        //     return back()->withInput()->with('error', 'Lỗi cập nhật. Vui lòng thử lại!');
        // }
    }

    // public function destroy(Doctor $doctor)
    public function destroy()
    {
        // // Logic theo yêu cầu: Chỉ xóa record Doctor (Soft/Hard delete tùy thuộc migration)
        // $doctor->delete();

        // return redirect()->route('admin.doctors.index')->with('success', 'Đã xóa hồ sơ Bác sĩ thành công.');
    }
}
