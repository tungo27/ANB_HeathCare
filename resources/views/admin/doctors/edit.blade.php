@extends('layouts.admin') {{-- Gọi khung layout chung --}}

@section('title', 'Chỉnh sửa Bác sĩ')

@push('styles')
    {{-- Chỉ tải CSS này cho trang chỉnh sửa --}}
    <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">
@endpush

@section('content')
    <div class="py-12">
        <div class="admin-container">
            <h3>Chỉnh sửa Bác sĩ: {{ $doctor->user->full_name }}</h3>

            @if (session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="full_name" class="form-label">Họ và Tên</label>
                    <input type="text" name="full_name" id="full_name"
                        value="{{ old('full_name', $doctor->user->full_name) }}" required class="form-control">
                    @error('full_name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email (Dùng để đăng nhập)</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $doctor->user->email) }}"
                        required class="form-control">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="specialty_id" class="form-label">Chuyên khoa</label>
                    <select name="specialty_id" id="specialty_id" required class="form-control">
                        <option value="">-- Chọn chuyên khoa --</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialty_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="qualification" class="form-label">Trình độ chuyên môn</label>
                    <input type="text" name="qualification" id="qualification"
                        value="{{ old('qualification', $doctor->qualification) }}" required class="form-control">
                    @error('qualification')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="years_of_experience" class="form-label">Số năm kinh nghiệm</label>
                    <input type="number" name="years_of_experience" id="years_of_experience"
                        value="{{ old('years_of_experience', $doctor->years_of_experience) }}" required min="0"
                        class="form-control">
                    @error('years_of_experience')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="consultation_fee" class="form-label">Phí khám (VNĐ)</label>
                    <input type="number" name="consultation_fee" id="consultation_fee"
                        value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required min="0"
                        class="form-control">
                    @error('consultation_fee')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Tiểu sử / Giới thiệu</label>
                    <textarea name="bio" id="bio" rows="4" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
                    @error('bio')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.doctors.index') }}" class="btn-secondary">Hủy</a>
                    <button type="submit" class="btn-primary">Cập nhật Bác sĩ</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/doctor.js') }}"></script>
@endpush
