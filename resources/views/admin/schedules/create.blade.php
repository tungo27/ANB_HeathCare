<!-- resources/views/admin/schedules/form.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">{{ $schedule->id ? '✏️ Sửa ca' : '➕ Tạo ca mới' }}</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ $schedule->id ? route('admin.schedules.update', $schedule->id) : route('admin.schedules.store') }}" 
              method="POST" id="scheduleForm">
            @csrf
            @if($schedule->id) @method('PUT') @endif
            
            <div class="row g-4">
                {{-- 👨‍⚕️ Bác sĩ --}}
                <div class="col-md-6">
                    <label class="form-label">Bác sĩ <span class="text-danger">*</span></label>
                    <select name="doctor_id" class="form-select" required 
                            {{ $schedule->id ? 'disabled' : '' }}>
                        <option value="">Chọn bác sĩ</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->user_id }}" 
                                    {{ old('doctor_id', $schedule->doctor_id) == $doctor->user_id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty->name ?? 'Chưa phân loại' }}
                            </option>
                        @endforeach
                    </select>
                    @if($schedule->id)
                        <input type="hidden" name="doctor_id" value="{{ $schedule->doctor_id }}">
                        <small class="text-muted">Không thể thay đổi bác sĩ sau khi tạo ca</small>
                    @endif
                </div>
                
                {{-- 📅 Ngày làm việc --}}
                <div class="col-md-6">
                    <label class="form-label">Ngày làm việc <span class="text-danger">*</span></label>
                    <input type="date" name="work_date" class="form-control" 
                           value="{{ old('work_date', $schedule->work_date ?? date('Y-m-d')) }}" required>
                </div>
                
                {{-- ⏰ Giờ làm việc --}}
                <div class="col-md-3">
                    <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" 
                           value="{{ old('start_time', $schedule->start_time ?? '08:00') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" 
                           value="{{ old('end_time', $schedule->end_time ?? '12:00') }}" required>
                </div>
                
                {{-- 🏥 Phòng khám --}}
                <div class="col-md-3">
                    <label class="form-label">Phòng khám</label>
                    <input type="text" name="room" class="form-control" maxlength="20"
                           value="{{ old('room', $schedule->room) }}" placeholder="VD: Phòng 101">
                </div>
                
                {{-- 📊 Trạng thái --}}
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status', $schedule->status) == 'draft' ? 'selected' : '' }}>
                            📝 Draft (Chưa công khai)
                        </option>
                        <option value="published" {{ old('status', $schedule->status) == 'published' ? 'selected' : '' }}>
                            ✅ Published (Cho phép đặt lịch)
                        </option>
                        <option value="closed" {{ old('status', $schedule->status) == 'closed' ? 'selected' : '' }}>
                            🔒 Closed (Đóng, không nhận thêm)
                        </option>
                    </select>
                </div>
            </div>
            
            <hr class="my-4">
            
            {{-- ⚙️ Cấu hình sinh slot tự động --}}
            <h6 class="fw-semibold mb-3">⚙️ Cấu hình chia suất khám</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Thời lượng mỗi suất (phút)</label>
                    <select name="slot_duration" class="form-select">
                        @foreach([15,20,30,45,60] as $min)
                            <option value="{{ $min }}" 
                                    {{ old('slot_duration', 30) == $min ? 'selected' : '' }}>
                                {{ $min }} phút
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Thời gian nghỉ giữa suất (phút)</label>
                    <input type="number" name="break_time" class="form-control" min="0" max="30"
                           value="{{ old('break_time', 5) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Block giờ nghỉ trưa</label>
                    <div class="input-group">
                        <input type="time" name="lunch_start" class="form-control" 
                               value="{{ old('lunch_start', '11:30') }}">
                        <span class="input-group-text">→</span>
                        <input type="time" name="lunch_end" class="form-control" 
                               value="{{ old('lunch_end', '12:00') }}">
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle me-2"></i>
                <div>
                    <strong>Preview:</strong> Với cấu hình trên, hệ thống sẽ tự sinh 
                    <strong id="slotPreview">~8 slots</strong> cho ca này.
                    <button type="button" class="btn btn-link btn-sm p-0 ms-2" 
                            onclick="calculateSlotsPreview()">Tính lại</button>
                </div>
            </div>
            
            {{-- 🚫 Block times thủ công (JSON input) --}}
            <div class="mb-3">
                <label class="form-label">Block giờ cố định thêm (tùy chọn)</label>
                <textarea name="blocked_times" class="form-control" rows="2" 
                          placeholder='VD: ["14:00-14:30", "16:00-16:15"]'>{{ old('blocked_times', $schedule->blocked_times) }}</textarea>
                <small class="text-muted">Định dạng JSON array: ["start-end", ...]. Giờ format HH:mm</small>
            </div>
            
            {{-- 🔘 Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">
                    ↩ Hủy
                </a>
                <button type="submit" name="action" value="save" class="btn btn-primary">
                    💾 Lưu ca
                </button>
                <button type="submit" name="action" value="save_and_generate" class="btn btn-success">
                    ✨ Lưu & Sinh slots ngay
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 🔧 JS tính preview slots --}}
<script>
function calculateSlotsPreview() {
    const start = document.querySelector('[name="start_time"]').value;
    const end = document.querySelector('[name="end_time"]').value;
    const duration = parseInt(document.querySelector('[name="slot_duration"]').value);
    const breakTime = parseInt(document.querySelector('[name="break_time"]').value || 0);
    
    if (!start || !end) return;
    
    const [startH, startM] = start.split(':').map(Number);
    const [endH, endM] = end.split(':').map(Number);
    
    let current = startH * 60 + startM;
    const finish = endH * 60 + endM;
    let count = 0;
    
    while (current + duration <= finish) {
        // Skip lunch break (simple check)
        const lunchStart = 11*60+30, lunchEnd = 12*60;
        if (!(current < lunchEnd && current + duration > lunchStart)) {
            count++;
        }
        current += duration + breakTime;
    }
    
    document.getElementById('slotPreview').textContent = `~${count} slots`;
}

// Auto calculate on load & change
document.addEventListener('DOMContentLoaded', calculateSlotsPreview);
document.querySelectorAll('[name="start_time"], [name="end_time"], [name="slot_duration"], [name="break_time"]')
    .forEach(el => el.addEventListener('change', calculateSlotsPreview));
</script>
@endsection