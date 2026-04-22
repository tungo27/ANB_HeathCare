<!-- resources/views/doctor/appointments/detail.blade.php -->
@extends('layouts.app')

@section('main_content')
@include('components.header-doctor')

<div class="container-fluid py-4">
    {{-- 📍 Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('doctor.schedule') }}">Lịch làm việc</a></li>
            <li class="breadcrumb-item active">Chi tiết ca</li>
        </ol>
    </nav>
    
    {{-- 📋 Schedule header --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">📅 {{ \Carbon\Carbon::parse($schedule->work_date)->format('d/m/Y') }}</h4>
                    <p class="text-muted mb-0">
                        ⏰ {{ $schedule->start_time }} - {{ $schedule->end_time }} | 
                        🏥 {{ $schedule->room ?? 'Phòng khám chung' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-{{ $schedule->status === 'published' ? 'success' : 'secondary' }} fs-6">
                        {{ $schedule->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- 🎯 Slots list --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📋 Danh sách suất khám ({{ $schedule->slots->count() }} slots)</h5>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary" data-filter="all">Tất cả</button>
                <button class="btn btn-outline-primary" data-filter="booked">Đã đặt</button>
                <button class="btn btn-outline-success" data-filter="completed">Đã khám</button>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Slot</th>
                            <th>Thời gian</th>
                            <th>Bệnh nhân</th>
                            <th>Triệu chứng</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedule->slots->sortBy('slot_number') as $slot)
                            <tr data-status="{{ $slot->appointment?->status ?? $slot->status }}">
                                <td class="fw-bold">#{{ str_pad($slot->slot_number, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}
                                    -{{ \Carbon\Carbon::parse($slot->slot_end_time)->format('H:i') }}
                                </td>
                                <td>
                                    @if($slot->appointment?->patient)
                                        <div class="fw-medium">{{ $slot->appointment->patient->name }}</div>
                                        <small class="text-muted">{{ $slot->appointment->patient->phone ?? '' }}</small>
                                    @else
                                        <span class="text-muted fst-italic">Chưa có bệnh nhân</span>
                                    @endif
                                </td>
                                <td>
                                    {{ Str::limit($slot->appointment?->symptoms, 30) ?? '-' }}
                                </td>
                                <td>
                                    @if($slot->status === 'available')
                                        <span class="badge bg-success-subtle text-success">🟢 Trống</span>
                                    @elseif($slot->appointment?->status === 'completed')
                                        <span class="badge bg-success">✅ Đã khám</span>
                                    @elseif($slot->appointment?->status === 'confirmed')
                                        <span class="badge bg-primary">📋 Chờ khám</span>
                                    @elseif($slot->status === 'blocked')
                                        <span class="badge bg-danger">🔴 Block</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $slot->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($slot->appointment && $slot->appointment->status === 'confirmed')
                                        <button class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#examModal{{ $slot->appointment->id }}">
                                            🩺 Khám
                                        </button>
                                    @elseif($slot->appointment?->status === 'completed')
                                        <a href="{{ route('doctor.appointments.show', $slot->appointment->id) }}" 
                                           class="btn btn-sm btn-outline-secondary">
                                            👁️ Xem
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            
                            {{-- 🩺 Modal: Nhập kết quả khám --}}
                            @if($slot->appointment && $slot->appointment->status === 'confirmed')
                            <div class="modal fade" id="examModal{{ $slot->appointment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('doctor.appointments.complete', $slot->appointment->id) }}" 
                                              method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">🩺 Nhập kết quả khám</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Bệnh nhân:</strong> {{ $slot->appointment->patient->name }}</p>
                                                <p><strong>Triệu chứng:</strong> {{ $slot->appointment->symptoms }}</p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Kết quả chẩn đoán</label>
                                                    <textarea name="diagnosis_result" class="form-control" rows="3" required
                                                              placeholder="Ghi chẩn đoán, chỉ định điều trị...">{{ old('diagnosis_result') }}</textarea>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Ghi chú thêm (tùy chọn)</label>
                                                    <input type="text" name="note" class="form-control" 
                                                           value="{{ old('note') }}" 
                                                           placeholder="VD: Hẹn tái khám sau 1 tuần">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-success">✅ Hoàn tất khám</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 🔧 Filter slots --}}
<script>
document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active', 'btn-outline-primary'));
        this.classList.add('active', 'btn-outline-primary');
        
        document.querySelectorAll('tbody tr').forEach(row => {
            const status = row.dataset.status;
            if (filter === 'all' || 
                (filter === 'booked' && ['confirmed', 'pending'].includes(status)) ||
                (filter === 'completed' && status === 'completed')) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection