<!-- resources/views/patient/booking/select-slot.blade.php -->
@extends('layouts.app')

@section('main_content')
<div class="container py-4">
    {{-- 📍 Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('patient.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('patient.doctors.show', $doctor->user_id) }}">
                {{ $doctor->user->name }}
            </a></li>
            <li class="breadcrumb-item active">Đặt lịch khám</li>
        </ol>
    </nav>
    
    {{-- 👨‍⚕️ Doctor info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px">
                        <i class="bi bi-person-badge fs-1 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-7">
                    <h4 class="mb-1">{{ $doctor->user->name }}</h4>
                    <p class="text-muted mb-1">
                        {{ $doctor->specialty->name ?? 'Chưa phân loại chuyên khoa' }}
                        @if($doctor->years_of_experience)
                            • {{ $doctor->years_of_experience }} năm kinh nghiệm
                        @endif
                    </p>
                    @if($doctor->consultation_fee > 0)
                        <span class="badge bg-info text-dark">
                            💰 Phí khám: {{ number_format($doctor->consultation_fee, 0, ',', '.') }} ₫
                        </span>
                    @endif
                </div>
                <div class="col-md-3 text-md-end">
                    <a href="{{ route('patient.doctors.show', $doctor->user_id) }}" 
                       class="btn btn-outline-secondary">← Chọn bác sĩ khác</a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- 📅 Date selector --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Chọn ngày khám</label>
                    <input type="date" name="date" class="form-control" 
                           value="{{ request('date', date('Y-m-d')) }}"
                           min="{{ date('Y-m-d') }}"
                           max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                           onchange="this.form.submit()">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Khung giờ ưu tiên</label>
                    <select name="time_preference" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="morning" {{ request('time_preference')=='morning'?'selected':'' }}>☀️ Sáng (8h-12h)</option>
                        <option value="afternoon" {{ request('time_preference')=='afternoon'?'selected':'' }}>🌤️ Chiều (13h-17h)</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    {{-- 🎯 Available slots --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                🎯 Suất khám trống ngày {{ \Carbon\Carbon::parse(request('date', date('Y-m-d')))->format('d/m/Y') }}
            </h5>
        </div>
        
        <div class="card-body">
            @if($availableSlots->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Không có suất khám trống</h5>
                    <p class="text-muted">
                        Vui lòng chọn ngày khác hoặc liên hệ hotline để được hỗ trợ đặt lịch ưu tiên.
                    </p>
                    <a href="tel:1900xxxx" class="btn btn-outline-primary">
                        📞 Gọi hotline: 1900 xxxx
                    </a>
                </div>
            @else
                <div class="row g-3">
                    @foreach($availableSlots as $slot)
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100 border-success slot-card" data-slot-id="{{ $slot->id }}">
                                <div class="card-body text-center py-4">
                                    <div class="fs-4 fw-bold text-success mb-2">
                                        {{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}
                                    </div>
                                    <div class="text-muted small">
                                        → {{ \Carbon\Carbon::parse($slot->slot_end_time)->format('H:i') }}
                                    </div>
                                    @if($slot->schedule->room)
                                        <div class="mt-2">
                                            <small class="badge bg-light text-dark">
                                                🏥 {{ $slot->schedule->room }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent border-top-0 text-center pb-3">
                                    <button type="button" 
                                            class="btn btn-success w-100 select-slot-btn"
                                            data-slot-id="{{ $slot->id }}"
                                            data-schedule-id="{{ $slot->schedule_id }}"
                                            data-time="{{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}">
                                        ✅ Chọn suất này
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- 🔄 Modal: Confirm booking --}}
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('patient.appointments.store') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="schedule_slot_id" id="modalSlotId">
                
                <div class="modal-header">
                    <h5 class="modal-title">✅ Xác nhận đặt lịch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Thông tin lịch hẹn:</strong><br>
                        👨‍⚕️ {{ $doctor->user->name }}<br>
                        📅 <span id="modalDate"></span><br>
                        ⏰ <span id="modalTime"></span><br>
                        🏥 <span id="modalRoom"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Triệu chứng / Lý do khám <span class="text-danger">*</span></label>
                        <textarea name="symptoms" class="form-control" rows="3" required
                                  placeholder="Mô tả ngắn gọn triệu chứng bạn đang gặp phải...">{{ old('symptoms') }}</textarea>
                        <small class="text-muted">Thông tin này giúp bác sĩ chuẩn bị tốt hơn cho buổi khám.</small>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="agree_terms" required id="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">
                            Tôi đồng ý với <a href="#" target="_blank">điều khoản đặt lịch</a> và chính sách hủy/hoãn.
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        💳 Xác nhận đặt lịch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🔧 JS handling --}}
<script>
document.querySelectorAll('.select-slot-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const slotId = this.dataset.slotId;
        const scheduleId = this.dataset.scheduleId;
        const time = this.dataset.time;
        const date = '{{ request("date", date("Y-m-d")) }}';
        
        // Fill modal
        document.getElementById('modalSlotId').value = slotId;
        document.getElementById('modalDate').textContent = 
            new Date(date).toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('modalTime').textContent = time;
        document.getElementById('modalRoom').textContent = 
            this.closest('.card').querySelector('.badge')?.textContent.trim() || 'Phòng khám chung';
        
        // Show modal
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    });
});

// Prevent double submit
document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Đang xử lý...';
});
</script>

<style>
.slot-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.slot-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}
</style>
@endsection