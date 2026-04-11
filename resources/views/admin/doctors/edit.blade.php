@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bác sĩ')

{{-- Loại bỏ việc đẩy file CSS cũ nếu bạn đã chuyển sang Tailwind hoàn toàn --}}

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card Container --}}
            <div class="bg-white overflow-hidden shadow-md rounded-lg border border-gray-200">

                {{-- Form Header --}}
                <div class="px-6 py-4 bg-teal-600 border-b border-teal-700">
                    <h3 class="text-lg font-bold text-white">
                        Chỉnh sửa Bác sĩ: <span class="font-normal">{{ $doctor->user->full_name }}</span>
                    </h3>
                </div>

                <div class="p-8">
                    {{-- Alert Messages --}}
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded shadow-sm">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Update Form --}}
                    <form action="{{ route('admin.doctors.doctorUpdate', $doctor) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Họ và Tên --}}
                            <div class="col-span-1">
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và
                                    Tên</label>
                                <input type="text" name="full_name" id="full_name"
                                    value="{{ old('full_name', $doctor->user->full_name) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                @error('full_name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="col-span-1">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện
                                    thoại</label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $doctor->user->phone ?? '') }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition"
                                    placeholder="Nhập số điện thoại...">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Dùng để
                                    đăng nhập)</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $doctor->user->email) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Chuyên khoa --}}
                            <div class="col-span-1">
                                <label for="specialty_id" class="block text-sm font-medium text-gray-700 mb-1">Chuyên
                                    khoa</label>
                                <select name="specialty_id" id="specialty_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                    <option value="">-- Chọn chuyên khoa --</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}"
                                            {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Trình độ --}}
                            <div class="col-span-1">
                                <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Trình độ
                                    chuyên môn</label>
                                <input type="text" name="qualification" id="qualification"
                                    value="{{ old('qualification', $doctor->qualification) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                @error('qualification')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kinh nghiệm --}}
                            <div class="col-span-1">
                                <label for="years_of_experience" class="block text-sm font-medium text-gray-700 mb-1">Số năm
                                    kinh nghiệm</label>
                                <input type="number" name="years_of_experience" id="years_of_experience"
                                    value="{{ old('years_of_experience', $doctor->years_of_experience) }}" required
                                    min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                @error('years_of_experience')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phí khám --}}
                            <div class="col-span-1">
                                <label for="consultation_fee" class="block text-sm font-medium text-gray-700 mb-1">Phí khám
                                    (VNĐ)</label>
                                <input type="number" name="consultation_fee" id="consultation_fee"
                                    value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required
                                    min="0"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">
                                @error('consultation_fee')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tiểu sử --}}
                            <div class="col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Tiểu sử / Giới
                                    thiệu</label>
                                <textarea name="bio" id="bio" rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 transition">{{ old('bio', $doctor->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.doctors.doctorManagement') }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                Hủy
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:ring focus:ring-teal-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                Cập nhật Bác sĩ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
