<!-- resources/views/admin/schedules/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📋 Quản lý ca làm việc</h5>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tạo ca mới
        </a>
    </div>

    <div class="card-body">
        {{-- 🔍 Filter --}}
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Bác sĩ</label>
                <select name="doctor_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->user_id }}"
                        {{ request('doctor_id') == $doctor->user_id ? 'selected' : '' }}>
                        {{ $doctor->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày</label>
                <input type="date" name="date" class="form-control"
                    value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
                    <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">🔍 Lọc</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary w-100">↺ Reset</a>
            </div>
        </form>

        {{-- 📊 Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle table-nowrap" style="min-width: 1000px;">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 150px;">Bác sĩ</th>
                        <th style="min-width: 100px;">Ngày</th>
                        <th style="min-width: 120px;">Giờ</th>
                        <th style="min-width: 80px;">Phòng</th>
                        <th style="min-width: 120px;">Slots</th>
                        <th class="text-center" style="min-width: 150px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <td>
                            <div class="text-truncate" title="{{ $schedule->doctor->user->full_name ?? 'N/A' }}">
                                {{ $schedule->doctor->user->full_name ?? $schedule->doctor->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td>
                            <div class="text-nowrap">
                                {{ \Carbon\Carbon::parse($schedule->work_date)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $schedule->start_time }}<br>
                                <span class="text-dark">{{ $schedule->end_time }}</span>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $schedule->room ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                            $stats = $schedule->slots->groupBy('status')->map->count();
                            @endphp
                            <div class="d-flex flex-column gap-1">
                                <small class="text-success">
                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                                    {{ $stats['available']??0 }} trống
                                </small>
                                <small class="text-primary">
                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                                    {{ $stats['booked']??0 }} đã đặt
                                </small>
                                <small class="text-danger">
                                    <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                                    {{ $stats['blocked']??0 }} block
                                </small>
                            </div>
                        </td>
                        <td class="text-center align-middle" style="min-width: 180px; white-space: nowrap;">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Thao tác">
                                <a href="{{ route('admin.schedules.slots', $schedule->id) }}"
                                    class="btn btn-outline-primary d-flex align-items-center gap-1 px-2"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Quản lý slots">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                    <span class="d-none d-xl-inline">Slots</span>
                                </a>
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                    class="btn btn-outline-secondary d-flex align-items-center gap-1 px-2"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa ca">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="d-none d-xl-inline">Sửa</span>
                                </a>
                                <button type="button"
                                    class="btn btn-outline-danger d-flex align-items-center gap-1 px-2"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $schedule->id }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa ca">
                                    <i class="bi bi-trash3-fill"></i>
                                    <span class="d-none d-xl-inline">Xóa</span>
                                </button>
                            </div>

                            {{-- Modal xác nhận xóa (Cấu trúc chuẩn BS5) --}}
                            <div class="modal fade" id="deleteModal{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content shadow-lg border-0">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title text-danger fw-bold">⚠️ Xác nhận xóa</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-2">
                                            <p class="mb-2">Bạn có chắc chắn muốn xóa ca này?</p>
                                            <ul class="text-muted small mb-0 ps-3">
                                                <li>Xóa toàn bộ suất khám (slots)</li>
                                                <li>Hủy các lịch hẹn đã đặt</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Hủy</button>
                                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3">Xác nhận xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                <p class="mb-2">Chưa có ca làm việc nào</p>
                                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tạo ca đầu tiên
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($schedules->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Hiển thị {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }}
                của {{ $schedules->total() }} ca
            </small>
            {{ $schedules->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Initialize tooltips --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection