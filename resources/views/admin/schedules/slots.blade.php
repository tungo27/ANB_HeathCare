<!-- resources/views/admin/schedules/slots.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">🎯 Quản lý suất khám</h5>
            <small class="text-muted">
                👨‍⚕️ {{ $schedule->doctor->user->name }} |
                📅 {{ \Carbon\Carbon::parse($schedule->work_date)->format('d/m/Y') }} |
                ⏰ {{ $schedule->start_time }} - {{ $schedule->end_time }} |
                🏥 {{ $schedule->room ?? 'Chưa chỉ định' }}
            </small>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Quay lại
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                ⚡ Hành động hàng loạt
            </button>
        </div>
    </div>

    <div class="card-body">
        {{-- 📊 Stats summary --}}
        <div class="row g-3 mb-4">
            @php
            $stats = $schedule->slots->groupBy('status')->map->count();
            $total = $schedule->slots->count();
            @endphp
            <div class="col-md-3">
                <div class="card bg-success-subtle border-success">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium">🟢 Available</span>
                            <span class="badge bg-success rounded-pill">{{ $stats['available'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary-subtle border-primary">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium">🔵 Booked</span>
                            <span class="badge bg-primary rounded-pill">{{ $stats['booked'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger-subtle border-danger">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-medium">🔴 Blocked</span>
                            <span class="badge bg-danger rounded-pill">{{ $stats['blocked'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🔍 Filter slots --}}
        <div class="mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary active" data-filter="all">Tất cả</button>
                <button type="button" class="btn btn-outline-success" data-filter="available">🟢 Available</button>
                <button type="button" class="btn btn-outline-primary" data-filter="booked">🔵 Booked</button>
                <button type="button" class="btn btn-outline-danger" data-filter="blocked">🔴 Blocked</button>
            </div>
            <div class="form-check form-switch d-inline-block ms-3">
                <input class="form-check-input" type="checkbox" id="showOnlyFree"
                    {{ request('free_only') ? 'checked' : '' }}>
                <label class="form-check-label" for="showOnlyFree">Chỉ hiển thị slot trống</label>
            </div>
        </div>

        {{-- 🎴 Grid slots --}}
        <div class="slots-grid row g-3" id="slotsContainer">
            @foreach ($schedule->slots->sortBy('slot_number') as $slot)
            <div class="col-md-4 col-lg-3 slot-item" data-status="{{ $slot->status }}"
                data-slot-id="{{ $slot->id }}">
                <div class="card h-100 slot-card {{ $slot->status }}" data-bs-toggle="tooltip"
                    title="{{ $slot->internal_note }}">

                    {{-- Header: Time --}}
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <small class="fw-semibold">
                            #{{ str_pad($slot->slot_number, 2, '0', STR_PAD_LEFT) }}
                        </small>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($slot->slot_start_time)->format('H:i') }}
                            -{{ \Carbon\Carbon::parse($slot->slot_end_time)->format('H:i') }}
                        </small>
                    </div>

                    {{-- Body: Status + Info --}}
                    <div class="card-body py-3">
                        {{-- Status badge --}}
                        <div class="mb-2">
                            @switch($slot->status)
                            @case('available')
                            <span class="badge bg-success-subtle text-success">🟢 Sẵn sàng</span>
                            @break

                            @case('booked')
                            <span class="badge bg-primary-subtle text-primary">🔵 Đã đặt</span>
                            @break

                            @case('blocked')
                            <span class="badge bg-danger-subtle text-danger">🔴 Đã block</span>
                            @break

                            @case('maintenance')
                            <span class="badge bg-warning-subtle text-warning">🟡 Bảo trì</span>
                            @break
                            @endswitch
                        </div>

                        {{-- Appointment info if booked --}}
                        @if ($slot->status === 'booked' && $slot->appointment)
                        <div class="small">
                            <div class="fw-medium text-truncate"
                                title="{{ $slot->appointment->patient->name ?? 'N/A' }}">
                                👤
                                {{ $slot->appointment->patient->name ?? 'Bệnh nhân #' . $slot->appointment->patient_id }}
                            </div>
                            @if ($slot->appointment->symptoms)
                            <div class="text-muted fst-italic" style="font-size: 0.85rem">
                                "{{ Str::limit($slot->appointment->symptoms, 40) }}"
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Internal note --}}
                        @if ($slot->internal_note && $slot->status !== 'booked')
                        <div class="small text-muted mt-1 fst-italic">
                            📝 {{ Str::limit($slot->internal_note, 50) }}
                        </div>
                        @endif
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="card-footer bg-transparent border-top-0 py-2">
                        <div class="btn-group w-100 btn-group-sm" role="group">
                            @if ($slot->status === 'available')
                            <button type="button" class="btn btn-outline-danger toggle-slot"
                                data-action="block" title="Block slot này">
                                🔒Khoá
                            </button>
                            <button type="button" class="btn btn-outline-primary toggle-slot"
                                data-action="assign" title="Gán appointment thủ công" data-bs-toggle="modal"
                                data-bs-target="#assignModal" data-slot-id="{{ $slot->id }}">
                                👤Gán bệnh nhân
                            </button>
                            @elseif($slot->status === 'blocked')
                            <button type="button" class="btn btn-outline-success toggle-slot"
                                data-action="unblock" title="Mở block">
                                🔓Blocked
                            </button>
                            @elseif($slot->status === 'booked')
                            <a href="#" class="btn btn-outline-primary" title="Xem chi tiết">
                                👁️Đã đặt
                            </a>
                            <button type="button" class="btn btn-outline-danger toggle-slot"
                                data-action="cancel" title="Hủy appointment này">
                                ❌Huỷ
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty state --}}
        @if ($schedule->slots->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <p>Chưa có slot nào trong ca này.</p>
            <form action="{{ route('admin.schedules.generate-slots', $schedule->id) }}" method="POST"
                class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    ✨ Sinh slots tự động ngay
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

{{-- 🔄 Modal: Assign appointment to slot --}}
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.schedules.slots.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="slot_id" id="modalSlotId">
                <div class="modal-header">
                    <h5 class="modal-title">👤 Gán bệnh nhân vào slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn bệnh nhân</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Chọn --</option>
                            @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->name }} ({{ $patient->phone ?? 'No phone' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Triệu chứng (tùy chọn)</label>
                        <textarea name="symptoms" class="form-control" rows="2" placeholder="Mô tả ngắn về lý do khám..."></textarea>
                    </div>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i>
                        Slot: <strong id="modalSlotTime"></strong><br>
                        Bác sĩ: {{ $schedule->doctor->user->name }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">✅ Xác nhận gán</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ⚡ Modal: Bulk actions --}}
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">⚡ Hành động hàng loạt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkForm" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    <div class="mb-3">
                        <label class="form-label">Chọn trạng thái áp dụng</label>
                        <select name="new_status" class="form-select" required>
                            <option value="available">🟢 Available (Mở tất cả)</option>
                            <option value="blocked">🔴 Blocked (Block tất cả)</option>
                            <option value="maintenance">🟡 Maintenance</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Áp dụng cho</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="all"
                                id="scopeAll" checked>
                            <label class="form-check-label" for="scopeAll">Tất cả slots</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="filtered"
                                id="scopeFiltered">
                            <label class="form-check-label" for="scopeFiltered">Chỉ slots đang lọc</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="scope" value="selected"
                                id="scopeSelected">
                            <label class="form-check-label" for="scopeSelected">Chỉ slots đã chọn</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú (tùy chọn)</label>
                        <input type="text" name="note" class="form-control"
                            placeholder="VD: Block do bác sĩ nghỉ đột xuất">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="bulkForm" class="btn btn-danger"
                    onclick="return confirm('Xác nhận áp dụng thay đổi cho các slot đã chọn?')">
                    ✅ Áp dụng
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 🔧 JS xử lý tương tác --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Toggle slot status (AJAX)
        document.querySelectorAll('.toggle-slot').forEach(btn => {
            btn.addEventListener('click', function() {
                const slotId = this.closest('.slot-item').dataset.slotId;
                const action = this.dataset.action;
                const card = this.closest('.slot-card');

                const endpoints = {
                    block: `/admin/schedules/slots/${slotId}/block`,
                    unblock: `/admin/schedules/slots/${slotId}/unblock`,
                    cancel: `/admin/schedules/slots/${slotId}/cancel-appointment`
                };

                if (!endpoints[action]) return;

                if (action === 'cancel' && !confirm(
                        'Hủy appointment này sẽ giải phóng slot và thông báo cho bệnh nhân. Tiếp tục?'
                    )) {
                    return;
                }

                fetch(endpoints[action], {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page hoặc update DOM
                            location.reload();
                        } else {
                            alert('Lỗi: ' + (data.message ||
                                'Không thể thực hiện thao tác'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi kết nối. Vui lòng thử lại.');
                    });
            });
        });

        // ✅ Filter slots by status
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove(
                    'active'));
                this.classList.add('active');

                document.querySelectorAll('.slot-item').forEach(item => {
                    if (filter === 'all' || item.dataset.status === filter) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // ✅ Show only free slots toggle
        document.getElementById('showOnlyFree')?.addEventListener('change', function() {
            const showFree = this.checked;
            document.querySelectorAll('.slot-item').forEach(item => {
                if (showFree && item.dataset.status !== 'available') {
                    item.style.display = 'none';
                } else {
                    item.style.display = '';
                }
            });
        });

        // ✅ Modal: Fill slot time when opening assign modal
        const assignModal = document.getElementById('assignModal');
        assignModal?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const slotId = button.dataset.slotId;
            const slotCard = button.closest('.slot-card');

            document.getElementById('modalSlotId').value = slotId;
            document.getElementById('modalSlotTime').textContent =
                slotCard.querySelector('.text-muted').textContent.trim();
        });
    });
</script>
@endsection