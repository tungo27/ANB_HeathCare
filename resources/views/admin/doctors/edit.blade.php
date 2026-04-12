@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bác sĩ')

@section('content')
    <div class="py-5 bg-light min-vh-100">
        <div class="container" style="max-width: 900px;">
            {{-- Card Container --}}
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

                {{-- Form Header --}}
                <div class="card-header py-3" style="background-color: #0d9488; border-bottom: 1px solid #0f766e;">
                    <h3 class="h5 mb-0 fw-bold text-white">
                        Chỉnh sửa Bác sĩ: <span class="fw-normal">{{ $doctor->user->full_name }}</span>
                    </h3>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- Alert Messages --}}
                    @if (session('error'))
                        <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm mb-4">
                            <ul class="list-unstyled mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Update Form --}}
                    <form action="{{ route('admin.doctors.doctorUpdate', $doctor) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Họ và Tên --}}
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-semibold text-secondary small">Họ và Tên</label>
                                <input type="text" name="full_name" id="full_name"
                                    value="{{ old('full_name', $doctor->user->full_name) }}" required
                                    class="form-control @error('full_name') is-invalid @enderror shadow-none py-2">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-secondary small">Số điện
                                    thoại</label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $doctor->user->phone ?? '') }}" required
                                    class="form-control @error('phone') is-invalid @enderror shadow-none py-2"
                                    placeholder="Nhập số điện thoại...">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold text-secondary small">Email (Dùng để
                                    đăng nhập)</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $doctor->user->email) }}" required
                                    class="form-control @error('email') is-invalid @enderror shadow-none py-2">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Chuyên khoa --}}
                            <div class="col-md-6">
                                <label for="specialty_id" class="form-label fw-semibold text-secondary small">Chuyên
                                    khoa</label>
                                <select name="specialty_id" id="specialty_id" required
                                    class="form-select @error('specialty_id') is-invalid @enderror shadow-none py-2">
                                    <option value="">-- Chọn chuyên khoa --</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}"
                                            {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Trình độ --}}
                            <div class="col-md-6">
                                <label for="qualification" class="form-label fw-semibold text-secondary small">Trình độ
                                    chuyên môn</label>
                                <input type="text" name="qualification" id="qualification"
                                    value="{{ old('qualification', $doctor->qualification) }}" required
                                    class="form-control @error('qualification') is-invalid @enderror shadow-none py-2">
                                @error('qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kinh nghiệm --}}
                            <div class="col-md-6">
                                <label for="years_of_experience" class="form-label fw-semibold text-secondary small">Số năm
                                    kinh nghiệm</label>
                                <input type="number" name="years_of_experience" id="years_of_experience"
                                    value="{{ old('years_of_experience', $doctor->years_of_experience) }}" required
                                    min="0"
                                    class="form-control @error('years_of_experience') is-invalid @enderror shadow-none py-2">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phí khám --}}
                            <div class="col-md-6">
                                <label for="consultation_fee" class="form-label fw-semibold text-secondary small">Phí khám
                                    (VNĐ)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted small">₫</span>
                                    <input type="number" name="consultation_fee" id="consultation_fee"
                                        value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required
                                        min="0"
                                        class="form-control border-start-0 @error('consultation_fee') is-invalid @enderror shadow-none py-2">
                                </div>
                                @error('consultation_fee')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tiểu sử --}}
                            <div class="col-12">
                                <label for="bio" class="form-label fw-semibold text-secondary small">Tiểu sử / Giới
                                    thiệu</label>
                                <textarea name="bio" id="bio" rows="4"
                                    class="form-control @error('bio') is-invalid @enderror shadow-none">{{ old('bio', $doctor->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex align-items-center justify-content-end gap-3 pt-4 mt-5 border-top">
                            <a href="{{ route('admin.doctors.doctorManagement') }}"
                                class="btn btn-outline-secondary px-4 fw-semibold text-uppercase small shadow-sm">
                                Hủy
                            </a>
                            <button type="submit" class="btn text-white px-4 fw-bold text-uppercase small shadow-sm"
                                style="background-color: #0d9488;">
                                Cập nhật Bác sĩ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
