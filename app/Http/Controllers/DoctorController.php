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
        $doctors = Doctor::All();
        // with(['user', 'specialty'])->latest()->paginate(10);
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

            // Bước 1: Tạo record User mới
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make('password123'),
            ]);

            // Bước 2: Gán quyền (Cần đảm bảo package Spatie Permission đã được setup)
            $user->assignRole('Doctor');

            // Bước 3: Tạo record Doctor map với $user->id vừa tạo
            Doctor::create([
                'user_id'      => $user->id,
                'specialty_id' => $request->specialty_id,
                'phone'        => $request->phone,
                'bio'          => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.doctors.index')->with('success', 'Bác sĩ đã được thêm thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo Bác sĩ: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo bác sĩ. Vui lòng kiểm tra lại!');
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

            // Cập nhật thông tin User
            $doctor->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Cập nhật thông tin Doctor
            $doctor->update([
                'specialty_id' => $request->specialty_id,
                'phone'        => $request->phone,
                'bio'          => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật hồ sơ bác sĩ thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật Bác sĩ: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Lỗi cập nhật. Vui lòng thử lại!');
        }
    }

    public function destroy(Doctor $doctor)
    {
        // Logic theo yêu cầu: Chỉ xóa record Doctor (Soft/Hard delete tùy thuộc migration)
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Đã xóa hồ sơ Bác sĩ thành công.');
    }
}
