@extends('layouts.admin')

@section('title', 'Thêm mới Bác sĩ')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary py-3">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="bi bi-person-plus-fill me-2"></i>Thêm mới Bác sĩ
                        </h5>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <h3 class="h5 fw-bold mb-4 border-bottom pb-2 text-secondary">Thông tin hồ sơ Bác sĩ</h3>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.doctors.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                {{-- Họ và Tên --}}
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label fw-bold">Họ và Tên</label>
                                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                                        class="form-control @error('full_name') is-invalid @enderror" required
                                        placeholder="Nguyễn Văn A">
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email (Tài khoản đăng nhập)</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror" required
                                        placeholder="doctor@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Số điện thoại --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="form-control @error('phone') is-invalid @enderror" required
                                        placeholder="090xxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Chuyên khoa --}}
                                <div class="col-md-6">
                                    <label for="specialty_id" class="form-label fw-bold">Chuyên khoa</label>
                                    <select name="specialty_id" id="specialty_id"
                                        class="form-select @error('specialty_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn chuyên khoa --</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialty_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mật khẩu --}}
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">Mật khẩu</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Xác nhận mật khẩu --}}
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required>
                                </div>

                                {{-- Trình độ --}}
                                <div class="col-md-6">
                                    <label for="qualification" class="form-label fw-bold">Trình độ chuyên môn</label>
                                    <input type="text" name="qualification" id="qualification"
                                        value="{{ old('qualification') }}"
                                        class="form-control @error('qualification') is-invalid @enderror" required
                                        placeholder="Thạc sĩ, Bác sĩ CKI...">
                                    @error('qualification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kinh nghiệm --}}
                                <div class="col-md-3">
                                    <label for="years_of_experience" class="form-label fw-bold">Kinh nghiệm</label>
                                    <div class="input-group">
                                        <input type="number" name="years_of_experience" id="years_of_experience"
                                            value="{{ old('years_of_experience', 0) }}" min="0"
                                            class="form-control @error('years_of_experience') is-invalid @enderror"
                                            required>
                                        <span class="input-group-text">năm</span>
                                    </div>
                                    @error('years_of_experience')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phí khám --}}
                                <div class="col-md-3">
                                    <label for="consultation_fee" class="form-label fw-bold">Phí khám</label>
                                    <div class="input-group">
                                        <input type="number" name="consultation_fee" id="consultation_fee"
                                            value="{{ old('consultation_fee', 0) }}" min="0" step="1000"
                                            class="form-control @error('consultation_fee') is-invalid @enderror" required>
                                        <span class="input-group-text">₫</span>
                                    </div>
                                    @error('consultation_fee')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tiểu sử --}}
                                <div class="col-12">
                                    <label for="bio" class="form-label fw-bold">Tiểu sử / Giới thiệu</label>
                                    <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror"
                                        placeholder="Mô tả tóm tắt về quá trình công tác và kỹ năng của bác sĩ...">{{ old('bio') }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.doctors.index') }}"
                                    class="btn btn-outline-secondary px-4 fw-bold">
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                    <i class="bi bi-save me-2"></i>Lưu Bác sĩ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
