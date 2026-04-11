@extends('layouts.admin')

@section('title', 'Thêm mới Bác sĩ')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            {{-- Breadcrumb / Header --}}
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 leading-tight">
                    {{ __('Thêm mới Bác sĩ') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Tạo tài khoản và hồ sơ chuyên môn cho bác sĩ mới. Khi tạo bằng giao
                    diện
                    admin thì mật khẩu bác sĩ mặc định là
                    <b>password123</b>
                </p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="ID-16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin hồ sơ Bác sĩ
                    </h3>

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.doctors.doctorStore') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            {{-- Họ và Tên --}}
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Họ và
                                    Tên</label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                @error('full_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Tài khoản
                                    đăng nhập)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện
                                    thoại</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    placeholder="Ví dụ: 0912345678"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Chuyên khoa --}}
                            <div>
                                <label for="specialty_id" class="block text-sm font-medium text-gray-700 mb-1">Chuyên
                                    khoa</label>
                                <select name="specialty_id" id="specialty_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                    <option value="">-- Chọn chuyên khoa --</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}"
                                            {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Trình độ --}}
                            <div>
                                <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Trình độ
                                    chuyên môn</label>
                                <input type="text" name="qualification" id="qualification"
                                    value="{{ old('qualification') }}" required placeholder="Ví dụ: Thạc sĩ, Bác sĩ CKI"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                @error('qualification')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kinh nghiệm --}}
                            <div>
                                <label for="years_of_experience" class="block text-sm font-medium text-gray-700 mb-1">Số năm
                                    kinh nghiệm</label>
                                <input type="number" name="years_of_experience" id="years_of_experience"
                                    value="{{ old('years_of_experience', 0) }}" required min="0"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                @error('years_of_experience')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phí khám --}}
                            <div class="md:col-span-2">
                                <label for="consultation_fee" class="block text-sm font-medium text-gray-700 mb-1">Phí khám
                                    (VNĐ)</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₫</span>
                                    </div>
                                    <input type="number" name="consultation_fee" id="consultation_fee"
                                        value="{{ old('consultation_fee', 0) }}" required min="0" step="1000"
                                        class="w-full pl-7 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200">
                                </div>
                                @error('consultation_fee')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tiểu sử --}}
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Tiểu sử / Giới
                                    thiệu</label>
                                <textarea name="bio" id="bio" rows="4"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition duration-200"
                                    placeholder="Tóm tắt quá trình công tác và kỹ năng..."></textarea>
                                @error('bio')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Nút hành động --}}
                        <div class="mt-10 flex items-center justify-end space-x-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.doctors.doctorManagement') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition duration-150">
                                Hủy và quay lại
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-emerald-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Lưu Bác sĩ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
