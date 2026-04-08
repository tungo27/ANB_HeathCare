@extends('layouts.admin') {{-- Gọi khung layout chung --}}

@section('title', 'Thêm mới Bác sĩ')

@push('styles')
    {{-- Tải CSS riêng cho module doctor --}}
    <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">
@endpush

@section('content')
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Thêm mới Bác sĩ') }}
            </h2>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-container bg-white p-8 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-6 border-b pb-2">Thông tin hồ sơ Bác sĩ</h3>

                @if (session('error'))
                    <div class="alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.doctors.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Họ và Tên --}}
                        <div class="form-group">
                            <label for="name" class="form-label block font-medium text-gray-700">Họ và Tên</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('name')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label block font-medium text-gray-700">Email (Tài khoản đăng
                                nhập)</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @error('email')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Chuyên khoa --}}
                        <div class="form-group">
                            <label for="specialty_id" class="form-label block font-medium text-gray-700">Chuyên khoa</label>
                            <select name="specialty_id" id="specialty_id" required
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Chọn chuyên khoa --</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->id }}"
                                        {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialty_id')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Trình độ --}}
                        <div class="form-group">
                            <label for="qualification" class="form-label block font-medium text-gray-700">Trình độ chuyên
                                môn</label>
                            <input type="text" name="qualification" id="qualification"
                                value="{{ old('qualification') }}" required
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @error('qualification')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kinh nghiệm --}}
                        <div class="form-group">
                            <label for="years_of_experience" class="form-label block font-medium text-gray-700">Số năm kinh
                                nghiệm</label>
                            <input type="number" name="years_of_experience" id="years_of_experience"
                                value="{{ old('years_of_experience', 0) }}" required min="0"
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @error('years_of_experience')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phí khám --}}
                        <div class="form-group">
                            <label for="consultation_fee" class="form-label block font-medium text-gray-700">Phí khám
                                (VNĐ)</label>
                            <input type="number" name="consultation_fee" id="consultation_fee"
                                value="{{ old('consultation_fee', 0) }}" required min="0" step="1000"
                                class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            @error('consultation_fee')
                                <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tiểu sử --}}
                    <div class="form-group mt-6">
                        <label for="bio" class="form-label block font-medium text-gray-700">Tiểu sử / Giới
                            thiệu</label>
                        <textarea name="bio" id="bio" rows="4"
                            class="form-control w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('bio') }}</textarea>
                        @error('bio')
                            <p class="form-error text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nút hành động --}}
                    <div class="form-actions mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.doctors.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 transition ease-in-out duration-150">
                            Hủy
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition ease-in-out duration-150">
                            Lưu Bác sĩ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/doctor.js') }}"></script>
@endpush
