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
                    
                    {{-- Cột trái: Thông tin lịch hẹn --}}
                    <div class="col-lg-8">
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
                                            {{ $appointment->schedule->time_slot ?? $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }}
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
                                    @if($appointment->status === 'completed' && $appointment->diagnosis_result)
                                    <div class="col-12">
                                        <label class="text-muted small">Kết quả chẩn đoán</label>
                                        <div class="bg-success-subtle border border-success-subtle p-3 rounded">
                                            {!! nl2br(e($appointment->diagnosis_result)) !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Form hành động --}}
                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                        <div class="card shadow-sm border-0">
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
                                            <i class="bi bi-check-circle me-1"></i> Xác nhận khám
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
                    </div>

                    {{-- Cột phải: Thông tin bệnh nhân & Lịch sử --}}
                    <div class="col-lg-4">
                        {{-- Card bệnh nhân --}}
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

                        {{-- Card lịch sử trạng thái --}}
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-semibold">🕐 Lịch sử cập nhật</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">
                                        <span class="text-muted">Tạo lúc:</span><br>
                                        <strong>{{ $appointment->created_at->format('H:i d/m/Y') }}</strong>
                                    </li>
                                    @if($appointment->confirmed_at)
                                    <li class="mb-2">
                                        <span class="text-muted">Xác nhận lúc:</span><br>
                                        <strong>{{ $appointment->confirmed_at->format('H:i d/m/Y') }}</strong>
                                    </li>
                                    @endif
                                    @if($appointment->completed_at)
                                    <li class="mb-2">
                                        <span class="text-muted">Hoàn tất lúc:</span><br>
                                        <strong>{{ $appointment->completed_at->format('H:i d/m/Y') }}</strong>
                                    </li>
                                    @endif
                                    @if($appointment->cancelled_at)
                                    <li>
                                        <span class="text-muted">Hủy lúc:</span><br>
                                        <strong>{{ $appointment->cancelled_at->format('H:i d/m/Y') }}</strong>
                                        @if($appointment->cancellation_reason)
                                        <br><small class="text-danger">Lý do: {{ $appointment->cancellation_reason }}</small>
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

{{-- Modal: Hoàn tất khám --}}
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="complete">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🩺 Hoàn tất khám - #{{ $appointment->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kết quả chẩn đoán <span class="text-danger">*</span></label>
                        <textarea name="diagnosis_result" class="form-control" rows="5" required 
                            placeholder="Ghi kết quả khám, hướng dẫn điều trị...">{{ old('diagnosis_result') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Ghi chú thêm</label>
                        <textarea name="note" class="form-control" rows="2" 
                            placeholder="Ghi chú nội bộ (không hiển thị cho bệnh nhân)">{{ old('note') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Lưu & Hoàn tất
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Hủy lịch --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('doctor.appointments.update-status', $appointment->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="cancel">
            
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">⚠️ Xác nhận hủy lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn hủy lịch hẹn này? Hành động không thể hoàn tác.</p>
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
@endsection

@push('scripts')
<script>
// Auto-hide alert after 5s
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endpush