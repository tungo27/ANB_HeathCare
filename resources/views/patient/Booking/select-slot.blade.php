@extends('layouts.app')

@section('main_content')
    <div class="container py-4">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('patient.dashboard') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('patient.booking.doctors') }}">Danh sách bác sĩ</a></li>
                <li class="breadcrumb-item active">Chọn suất khám</li>
            </ol>
        </nav>

        {{-- Thẻ thông tin bác sĩ --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 64px; height: 64px;">
                            <i class="bi bi-person-vcard fs-2"></i>
                        </div>
                    </div>
                    <div class="ms-4">
                        <h4 class="mb-1">Bác sĩ: {{ $doctor->user->full_name }}</h4>
                        <p class="text-muted mb-0">
                            <span class="badge bg-light text-primary border">{{ $doctor->specialty?->name ?? 'N/A' }}</span>
                            @if ($doctor->years_of_experience)
                                <span class="ms-2 small"><i class="bi bi-clock-history"></i>
                                    {{ $doctor->years_of_experience }} năm kinh nghiệm</span>
                            @endif
                        </p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('patient.booking.doctors') }}" class="btn btn-outline-secondary btn-sm">Thay đổi
                            bác sĩ</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bộ lọc ngày --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chọn ngày khám</label>
                        <input type="date" name="date" class="form-control"
                            value="{{ request('date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lọc theo buổi</label>
                        <select name="time_preference" class="form-select" onchange="this.form.submit()">
                            <option value="">Tất cả các giờ</option>
                            <option value="morning" {{ request('time_preference') == 'morning' ? 'selected' : '' }}>Sáng
                                (trước 12h)</option>
                            <option value="afternoon" {{ request('time_preference') == 'afternoon' ? 'selected' : '' }}>
                                Chiều (sau 12h)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danh sách suất khám --}}
        <div class="row g-3">
            @forelse($availableSlots as $slot)
                <div class="col-6 col-md-4 col-lg-2">
                    <button type="button" class="btn btn-outline-success w-100 py-3 select-slot-btn"
                        data-slot-id="{{ $slot->id }}"
                        data-time="{{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}"
                        data-room="{{ $slot->schedule->room ?? 'Phòng khám chung' }}"
                        data-date="{{ \Carbon\Carbon::parse(request('date', date('Y-m-d')))->format('d/m/Y') }}">
                        <span
                            class="d-block fw-bold fs-5">{{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}</span>
                        <small class="text-muted">Phòng: {{ $slot->schedule->room ?? '---' }}</small>
                    </button>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-calendar2-x fs-1"></i>
                        <p class="mt-2">Rất tiếc, không có suất khám nào trống trong ngày này.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Modal xác nhận đặt lịch --}}
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('patient.booking.confirm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_slot_id" id="modalSlotId">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Xác nhận lịch hẹn</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="row small text-muted mb-2">
                                <div class="col-4">Bác sĩ:</div>
                                <div class="col-8 fw-bold text-dark">{{ $doctor->user->name }}</div>
                            </div>
                            <div class="row small text-muted mb-2">
                                <div class="col-4">Ngày khám:</div>
                                <div class="col-8 fw-bold text-dark" id="displayDate"></div>
                            </div>
                            <div class="row small text-muted mb-2">
                                <div class="col-4">Giờ khám:</div>
                                <div class="col-8 fw-bold text-dark" id="displayTime"></div>
                            </div>
                            <div class="row small text-muted">
                                <div class="col-4">Phòng khám:</div>
                                <div class="col-8 fw-bold text-dark" id="displayRoom"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Triệu chứng/Lý do khám <span
                                    class="text-danger">*</span></label>
                            <textarea name="symptoms" class="form-control" rows="3" required
                                placeholder="Ví dụ: Đau họng kéo dài, sốt nhẹ..."></textarea>
                        </div>

                        <div class="form-check small">
                            <input class="form-check-input" type="checkbox" name="agree_terms" id="agree" required>
                            <label class="form-check-label" for="agree">
                                Tôi cam đoan thông tin trên là chính xác và sẽ đến đúng giờ.
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success px-4">Xác nhận đặt lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));

            document.querySelectorAll('.select-slot-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Lấy dữ liệu từ thuộc tính data-
                    const data = this.dataset;

                    // Đổ dữ liệu vào modal
                    document.getElementById('modalSlotId').value = data.slotId;
                    document.getElementById('displayDate').textContent = data.date;
                    document.getElementById('displayTime').textContent = data.time;
                    document.getElementById('displayRoom').textContent = data.room;

                    // Mở modal
                    modal.show();
                });
            });
        });
    </script>
@endsection
