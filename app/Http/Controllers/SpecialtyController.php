<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::latest()->paginate(10);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('admin.specialties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Chuyên khoa đã được tạo thành công.');
    }

    public function edit(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')->with('success', 'Cập nhật chuyên khoa thành công.');
    }

    public function destroy(Specialty $specialty)
    {
        if ($specialty->doctors()->count() > 0) {
            return back()->with('error', 'Không thể xóa chuyên khoa đang có bác sĩ hoạt động.');
        }

        $specialty->delete();
        return redirect()->route('admin.specialties.index')->with('success', 'Đã xóa chuyên khoa.');
    }
}
