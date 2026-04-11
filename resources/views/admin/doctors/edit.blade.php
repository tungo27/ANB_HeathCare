@extends('layouts.admin')

@section('title', 'Chỉnh sửa Bác sĩ')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary py-3">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa Bác sĩ: {{ $doctor->user->full_name }}
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label fw-bold">Họ và Tên</label>
                                    <input type="text" name="full_name" id="full_name"
                                        value="{{ old('full_name', $doctor->user->full_name) }}"
                                        class="form-control @error('full_name') is-invalid @enderror" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email (Đăng nhập)</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $doctor->user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $doctor->user->phone) }}"
                                        class="form-control @error('phone') is-invalid @enderror" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="specialty_id" class="form-label fw-bold">Chuyên khoa</label>
                                    <select name="specialty_id" id="specialty_id"
                                        class="form-select @error('specialty_id') is-invalid @enderror" required>
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

                                <div class="col-md-6">
                                    <label for="qualification" class="form-label fw-bold">Trình độ chuyên môn</label>
                                    <input type="text" name="qualification" id="qualification"
                                        value="{{ old('qualification', $doctor->qualification) }}"
                                        class="form-control @error('qualification') is-invalid @enderror" required>
                                    @error('qualification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="years_of_experience" class="form-label fw-bold">Số năm kinh nghiệm</label>
                                    <div class="input-group">
                                        <input type="number" name="years_of_experience" id="years_of_experience"
                                            value="{{ old('years_of_experience', $doctor->years_of_experience) }}"
                                            min="0"
                                            class="form-control @error('years_of_experience') is-invalid @enderror"
                                            required>
                                        <span class="input-group-text">năm</span>
                                    </div>
                                    @error('years_of_experience')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="consultation_fee" class="form-label fw-bold">Phí khám (VNĐ)</label>
                                    <div class="input-group">
                                        <input type="number" name="consultation_fee" id="consultation_fee"
                                            value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0"
                                            class="form-control @error('consultation_fee') is-invalid @enderror" required>
                                        <span class="input-group-text">₫</span>
                                    </div>
                                    @error('consultation_fee')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="bio" class="form-label fw-bold">Tiểu sử / Giới thiệu</label>
                                    <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $doctor->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary px-4">Hủy</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Cập nhật Bác sĩ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
