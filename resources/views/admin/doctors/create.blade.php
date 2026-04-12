@extends('layouts.admin')

@section('title', 'Thêm mới Bác sĩ')

@section('content')
<div class="py-5 bg-light min-vh-100">
    <div class="container" style="max-width: 960px;">
        {{-- Breadcrumb / Header --}}
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1">
                {{ __('Thêm mới Bác sĩ') }}
            </h2>
            <p class="text-muted small">
                Tạo tài khoản và hồ sơ chuyên môn cho bác sĩ mới. Khi tạo bằng giao diện admin thì mật khẩu bác sĩ mặc định là
                <span class="fw-bold">password123</span>
            </p>
        </div>

        {{-- Form Card --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4 p-md-5">
                <h3 class="card-title h5 fw-bold text-secondary mb-4 d-flex align-items-center">
                    <svg class="me-2 text-success" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Thông tin hồ sơ Bác sĩ
                </h3>

                @if (session('error'))
                    <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.doctors.doctorStore') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        {{-- Họ và Tên --}}
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-medium text-dark small">Họ và Tên</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                class="form-control @error('full_name') is-invalid @enderror shadow-none py-2" placeholder="Nhập họ tên bác sĩ">
                            @error('full_name')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium text-dark small">Email (Tài khoản đăng nhập)</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="form-control @error('email') is-invalid @enderror shadow-none py-2" placeholder="example@email.com">
                            @error('email')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Số điện thoại --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-medium text-dark small">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="form-control @error('phone') is-invalid @enderror shadow-none py-2" placeholder="Ví dụ: 0912345678">
                            @error('phone')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chuyên khoa --}}
                        <div class="col-md-6">
                            <label for="specialty_id" class="form-label fw-medium text-dark small">Chuyên khoa</label>
                            <select name="specialty_id" id="specialty_id" required
                                class="form-select @error('specialty_id') is-invalid @enderror shadow-none py-2">
                                <option value="">-- Chọn chuyên khoa --</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialty_id')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Trình độ --}}
                        <div class="col-md-6">
                            <label for="qualification" class="form-label fw-medium text-dark small">Trình độ chuyên môn</label>
                            <input type="text" name="qualification" id="qualification" value="{{ old('qualification') }}" required
                                class="form-control @error('qualification') is-invalid @enderror shadow-none py-2" placeholder="Ví dụ: Thạc sĩ, Bác sĩ CKI">
                            @error('qualification')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kinh nghiệm --}}
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label fw-medium text-dark small">Số năm kinh nghiệm</label>
                            <input type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience', 0) }}" required min="0"
                                class="form-control @error('years_of_experience') is-invalid @enderror shadow-none py-2">
                            @error('years_of_experience')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phí khám --}}
                        <div class="col-12">
                            <label for="consultation_fee" class="form-label fw-medium text-dark small">Phí khám (VNĐ)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">₫</span>
                                <input type="number" name="consultation_fee" id="consultation_fee" value="{{ old('consultation_fee', 0) }}" required min="0" step="1000"
                                    class="form-control border-start-0 @error('consultation_fee') is-invalid @enderror shadow-none py-2">
                            </div>
                            @error('consultation_fee')
                                <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tiểu sử --}}
                        <div class="col-12">
                            <label for="bio" class="form-label fw-medium text-dark small">Tiểu sử / Giới thiệu</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="form-control @error('bio') is-invalid @enderror shadow-none" placeholder="Tóm tắt quá trình công tác và kỹ năng..."></textarea>
                            @error('bio')
                                <div class="invalid-feedback fw-medium small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nút hành động --}}
                    <div class="mt-5 pt-4 border-top d-flex align-items-center justify-content-end gap-3">
                        <a href="{{ route('admin.doctors.doctorManagement') }}" class="btn btn-link text-decoration-none text-secondary fw-semibold small">
                            Hủy và quay lại
                        </a>

                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold text-uppercase small shadow-sm">
                            <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
