@extends('layouts.app') {{-- Hoặc tên layout admin chính của bạn --}}

@section('main_content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h4 class="mb-0 text-dark fw-semibold">Thiết lập lịch làm việc cho Bác sĩ</h4>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.schedules.store') }}" method="POST">
                            @csrf

                            {{-- Chọn Bác sĩ --}}
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label fw-medium">Chọn Bác sĩ <span
                                        class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id"
                                    class="form-select @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">-- Lựa chọn Bác sĩ --</option>
                                    @foreach ($doctors as $doctor)
                                        {{-- doctor->user_id là khóa liên kết bảng users với doctors của bạn --}}
                                        <option value="{{ $doctor->user_id }}"
                                            {{ old('doctor_id') == $doctor->user_id ? 'selected' : '' }}>
                                            BS. {{ $doctor->user->full_name ?? ($doctor->user->name ?? 'Không rõ') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phòng khám --}}
                            <div class="mb-3">
                                <label for="room" class="form-label fw-medium">Phòng khám <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="room" id="room"
                                    class="form-control @error('room') is-invalid @enderror"
                                    value="{{ old('room', 'Phòng Khám Nội') }}" required placeholder="VD: Phòng 101">
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ngày làm việc --}}
                            <div class="mb-3">
                                <label for="work_date" class="form-label fw-medium">Ngày làm việc <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="work_date" id="work_date"
                                    class="form-control @error('work_date') is-invalid @enderror"
                                    value="{{ old('work_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                    min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                                @error('work_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ca làm việc --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium d-block">Ca làm việc <span
                                        class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="shifts[]" id="shift_morning"
                                        value="morning"
                                        {{ (is_array(old('shifts')) && in_array('morning', old('shifts'))) || !old('shifts') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shift_morning">
                                        Ca Sáng (08:00 - 12:00)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="shifts[]" id="shift_afternoon"
                                        value="afternoon"
                                        {{ is_array(old('shifts')) && in_array('afternoon', old('shifts')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shift_afternoon">
                                        Ca Chiều (13:30 - 17:00)
                                    </label>
                                </div>
                                @error('shifts')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Thời lượng mỗi ca (phút) --}}
                            <div class="mb-4">
                                <label for="slot_duration" class="form-label fw-medium">Thời lượng mỗi lượt khám (phút)
                                    <span class="text-danger">*</span></label>
                                <input type="number" name="slot_duration" id="slot_duration"
                                    class="form-control @error('slot_duration') is-invalid @enderror"
                                    value="{{ old('slot_duration', 30) }}" min="10" step="5" required>
                                <div class="form-text">Mỗi lượt khám tương ứng với 1 slot. Mặc định là 30 phút.</div>
                                @error('slot_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nút Submit --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">Hủy</a>
                                <button type="submit" class="btn btn-primary px-4">Tạo Lịch</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
