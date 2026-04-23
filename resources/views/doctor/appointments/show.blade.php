@extends('layouts.app')

@section('main_content')
@include('components.header-doctor')

<div class="min-vh-100 d-flex bg-light">
    <div class="d-flex flex-column flex-grow-1">

        {{-- Header --}}
        <header class="bg-white border-bottom py-3 px-4 sticky-top">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-semibold fs-5 mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    {{ __('Chi tiết lịch hẹn') }} #{{ $appointment->id }}
                </h2>
                <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="p-4">
            <div class="container-fluid">
                <div class="row g-4">

                    {{-- Cột trái: Thông tin lịch hẹn + Hành động --}}
                    <div class="col-lg-8">

                        {{-- 📋 Thông tin cơ bản --}}
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-semibold">📋 Thông tin lịch hẹn</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">Bệnh nhân</label>
                                        <p class="fw-medium mb-0">{{ $appointment->patient->full_name ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">SĐT</label>
                                        <p class="mb-0">
                                            <a href="tel:{{ $appointment->patient->phone ?? '' }}" class="text-decoration-none">
                                                {{ $appointment->patient->phone ?? '-' }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Ngày khám</label>
                                        <p class="mb-0">
                                            {{ \Carbon\Carbon::parse($appointment->schedule->work_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Khung giờ</label>
                                        <p class="mb-0 fw-semibold text-primary">
                                            {{ $appointment->schedule->start_time ?? $appointment->schedule->time_slot }} - {{ $appointment->schedule->end_time }}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted small">Triệu chứng mô tả</label>
                                        <p class="mb-0 bg-light p-3 rounded">{{ $appointment->symptoms ?? 'Không có ghi chú' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Trạng thái</label>
                                        <div>
                                            @include('components.appointment-status-badge', ['status' => $appointment->status])
                                        </div>
                                    </div>

                                    {{-- 🔗 Link follow-up nếu có --}}
                                    @if($appointment->followUpAppointment)
                                    <div class="col-12">
                                        <label class="text-muted small">Lịch hẹn lại</label>
                                        <a href="{{ route('doctor.appointments.show', $appointment->followUpAppointment->id) }}"
                                            class="badge bg-info text-decoration-none">
                                            <i class="bi bi-arrow-repeat me-1"></i>
                                            Xem lịch hẹn lại #{{ $appointment->followUpAppointment->id }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 🩺 Kết quả chẩn đoán (Hiển thị + Edit khi completed) --}}
                        @if($appointment->diagnosis_result || $appointment->status === 'confirmed')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">🩺 Kết quả chẩn đoán</h5>
                                @if($appointment->status === 'confirmed')
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#diagnosisModal">
                                    <i class="bi bi-pencil me-1"></i> Chỉnh sửa
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($appointment->diagnosis_result)
                                <div class="bg-success-subtle border border-success-subtle p-3 rounded">
                                    {!! nl2br(e($appointment->diagnosis_result)) !!}
                                </div>
                                @else
                                <p class="text-muted mb-0">Chưa có kết quả chẩn đoán.</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- ⚡ Form hành động --}}
                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-semibold">⚡ Hành động</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="d-flex flex-wrap gap-2">
                                        @if($appointment->status === 'pending')
                                        <button type="submit" name="action" value="confirm"
                                            class="btn btn-success px-4">
                                            <i class="bi bi-check-circle me-1"></i> Xác nhận
                                        </button>
                                        @endif

                                        @if($appointment->status === 'confirmed')
                                        <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#completeModal">
                                            <i class="bi bi-clipboard-check me-1"></i> Hoàn tất khám
                                        </button>
                                        @endif

                                        <button type="button" class="btn btn-outline-danger px-4" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                            <i class="bi bi-x-circle me-1"></i> Hủy lịch
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- 🔄 Đặt lịch hẹn lại (Follow-up) --}}
                        @if($appointment->status === 'completed' && !$appointment->follow_up_appointment_id)
                        <div class="card shadow-sm border-0 border-info">
                            <div class="card-header bg-info-subtle py-3">
                                <h5 class="mb-0 fw-semibold text-info">
                                    <i class="bi bi-arrow-repeat me-2"></i>Đặt lịch hẹn lại theo dõi
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tạo lịch hẹn mới để bệnh nhân tái khám. Hệ thống sẽ tự động liên kết 2 lịch với nhau.
                                </p>
                                <button type="button" class="btn btn-info text-white px-4"
                                    data-bs-toggle="modal" data-bs-target="#followUpModal">
                                    <i class="bi bi-calendar-plus me-1"></i> Tạo lịch hẹn lại
                                </button>
                            </div>
                        </div>
                        @endif

                        {{-- 🔗 Đã có follow-up --}}
                        @if($appointment->follow_up_appointment_id)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Đã đặt lịch hẹn lại:</strong>
                                <a href="{{ route('doctor.appointments.show', $appointment->follow_up_appointment_id) }}" class="alert-link">
                                    #{{ $appointment->follow_up_appointment_id }}
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>

                    {{-- Cột phải: Thông tin bệnh nhân & Lịch sử --}}
                    <div class="col-lg-4">
                        {{-- 👤 Card bệnh nhân --}}
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-semibold">👤 Thông tin bệnh nhân</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div class="bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 80px; height: 80px;">
                                        <i class="bi bi-person fs-1 text-primary"></i>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><strong>Họ tên:</strong> {{ $appointment->patient->full_name }}</li>
                                    <li class="mb-2"><strong>Ngày sinh:</strong>
                                        {{ $appointment->patient->dob ? \Carbon\Carbon::parse($appointment->patient->dob)->format('d/m/Y') : '-' }}
                                    </li>
                                    <li class="mb-2"><strong>Giới tính:</strong> {{ $appointment->patient->gender ?? '-' }}</li>
                                    <li class="mb-2"><strong>Địa chỉ:</strong> {{ $appointment->patient->address ?? '-' }}</li>
                                    <li><strong>Email:</strong> {{ $appointment->patient->email ?? '-' }}</li>
                                </ul>
                            </div>
                        </div>

                        {{-- 🕐 Lịch sử cập nhật (FIX DATE FORMATTING) --}}
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-semibold">🕐 Lịch sử cập nhật</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">
                                        <span class="text-muted">Tạo lúc:</span><br>
                                        <strong>{{ \Carbon\Carbon::parse($appointment->created_at)->format('H:i d/m/Y') }}</strong>
                                    </li>

                                    @if($appointment->confirmed_at)
                                    <li class="mb-2">
                                        <span class="text-muted">Xác nhận lúc:</span><br>
                                        <strong>{{ \Carbon\Carbon::parse($appointment->confirmed_at)->format('H:i d/m/Y') }}</strong>
                                    </li>
                                    @endif

                                    @if($appointment->completed_at)
                                    <li class="mb-2">
                                        <span class="text-muted">Hoàn tất lúc:</span><br>
                                        <strong>{{ \Carbon\Carbon::parse($appointment->completed_at)->format('H:i d/m/Y') }}</strong>
                                    </li>
                                    @endif

                                    @if($appointment->cancelled_at)
                                    <li>
                                        <span class="text-muted">Hủy lúc:</span><br>
                                        <strong>{{ \Carbon\Carbon::parse($appointment->cancelled_at)->format('H:i d/m/Y') }}</strong>
                                        @if($appointment->cancellation_reason)
                                        <br><small class="text-danger">Lý do: {{ $appointment->cancellation_reason }}</small>
                                        @endif
                                        @if($appointment->cancelled_by)
                                        <br><small class="text-muted">Bởi: {{ $appointment->cancelledBy->name ?? 'N/A' }}</small>
                                        @endif
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- 🩺 Modal: Hoàn tất khám + Nhập kết quả --}}
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="complete">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🩺 Hoàn tất khám - #{{ $appointment->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kết quả chẩn đoán <span class="text-danger">*</span></label>
                        <textarea name="diagnosis_result" class="form-control" rows="6" required
                            placeholder="• Chẩn đoán: ...&#10;• Hướng dẫn điều trị: ...&#10;• Thuốc kê đơn: ...">{{ old('diagnosis_result', $appointment->diagnosis_result) }}</textarea>
                        <small class="text-muted">Hỗ trợ xuống dòng, sẽ hiển thị định dạng cho bệnh nhân.</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Ghi chú nội bộ</label>
                        <textarea name="note" class="form-control" rows="2"
                            placeholder="Ghi chú chỉ bác sĩ xem được...">{{ old('note', $appointment->note) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Lưu kết quả & Hoàn tất
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ❌ Modal: Hủy lịch --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="cancel">

            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">⚠️ Xác nhận hủy lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn hủy lịch hẹn này?</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lý do hủy <span class="text-danger">*</span></label>
                        <select name="cancellation_reason" class="form-select" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Bệnh nhân yêu cầu">Bệnh nhân yêu cầu</option>
                            <option value="Bác sĩ bận đột xuất">Bác sĩ bận đột xuất</option>
                            <option value="Trùng lịch">Trùng lịch khám</option>
                            <option value="Lý do khác">Lý do khác</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Xác nhận hủy
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 🔄 Modal: Đặt lịch hẹn lại (Follow-up) --}}
<div class="modal fade" id="followUpModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('doctor.appointments.follow-up', $appointment->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-plus me-2"></i>Đặt lịch hẹn lại
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Chọn khung giờ rảnh của bạn trong 7 ngày tới để đặt lịch tái khám cho bệnh nhân
                        <strong>{{ $appointment->patient->full_name }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn khung giờ <span class="text-danger">*</span></label>
                        <select name="schedule_slot_id" class="form-select" required>
                            <option value="">-- Chọn khung giờ --</option>
                            @forelse($availableSlots as $slot)
                            <option value="{{ $slot['id'] }}" data-datetime="{{ $slot['datetime'] }}">
                                {{ $slot['label'] }}
                            </option>
                            @empty
                            <option disabled>Không có khung giờ rảnh trong 7 ngày tới</option>
                            @endforelse
                        </select>
                        <small class="text-muted">Chỉ hiển thị slot chưa có lịch hẹn.</small>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Ghi chú cho lần khám sau</label>
                        <textarea name="follow_up_note" class="form-control" rows="3"
                            placeholder="Ví dụ: Tái khám đánh giá hiệu quả thuốc, mang theo kết quả xét nghiệm...">{{ old('follow_up_note') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-calendar-check me-1"></i> Tạo lịch hẹn lại
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 📝 Modal: Chỉnh sửa kết quả chẩn đoán (nếu cần update sau khi complete) --}}
<div class="modal fade" id="diagnosisModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="complete">
            <input type="hidden" name="force_update" value="1">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ Chỉnh sửa kết quả chẩn đoán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kết quả chẩn đoán</label>
                        <textarea name="diagnosis_result" class="form-control" rows="6" required>{{ old('diagnosis_result', $appointment->diagnosis_result) }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Ghi chú nội bộ</label>
                        <textarea name="note" class="form-control" rows="2">{{ old('note', $appointment->note) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Cập nhật
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Auto-hide alert
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        // ✅ Hiển thị datetime khi chọn slot trong follow-up modal
        const slotSelect = document.querySelector('select[name="schedule_slot_id"]');
        if (slotSelect) {
            slotSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const datetime = selected.dataset.datetime;
                if (datetime) {
                    const formatted = new Date(datetime).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    let tooltip = document.getElementById('slot-datetime-tip');
                    if (!tooltip) {
                        tooltip = document.createElement('small');
                        tooltip.id = 'slot-datetime-tip';
                        tooltip.className = 'text-info d-block mt-1';
                        this.parentNode.appendChild(tooltip);
                    }
                    tooltip.textContent = `📅 Bạn chọn: ${formatted}`;
                }
            });
        }
    });
</script>
@endpush