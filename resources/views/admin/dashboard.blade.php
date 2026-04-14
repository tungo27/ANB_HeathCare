@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Xin chào, {{ Auth::user()->full_name }}</small>
    </div>
    <!-- <button class="btn btn-dark px-4">+ Thêm mới</button> -->
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-3 p-3" style="background: #34d399;">
            <p class="text-secondary small mb-1">Tổng bác sĩ</p>
            <h2 class="fw-bold text-white mb-1">{{ $totalDoctors }}</h2>
            <span class="text-success small">+3 tháng này</span>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-3 p-3" style="background: #34d399;">
            <p class="text-secondary small mb-1">Bệnh nhân hôm nay</p>
            <h2 class="fw-bold text-white mb-1">{{ $todayPatients }}</h2>
            <span class="text-success small">+12 so với hôm qua</span>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 rounded-3 p-3" style="background: #34d399;">
            <p class="text-secondary small mb-1">Lịch chờ duyệt</p>
            <h2 class="fw-bold text-white mb-1">{{ $pendingAppointments }}</h2>
            <span class="small" style="color: #ef4444;">Cần xử lý</span>
        </div>
    </div>
</div>

{{-- Recent Doctors Table --}}
<div class="card border-0 rounded-3" style="background: #fbbf24 ;">
    <div class="card-header bg-transparent border-bottom border-secondary py-3">
        <h6 class="mb-0 fw-medium text-white">Bác sĩ gần đây</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-white table-hover mb-0 align-middle">
            <thead>
                <tr style="font-size: 11px; letter-spacing: .05em;">
                    <th class="px-4 py-2 fw-medium text-uppercase text-secondary border-0">Họ tên</th>
                    <th class="px-4 py-2 fw-medium text-uppercase text-secondary border-0">Chuyên khoa</th>
                    <th class="px-4 py-2 fw-medium text-uppercase text-secondary border-0">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentDoctors as $doctor)
                <tr>
                    <td class="px-4 py-3 fw-medium text-dark">{{ $doctor->user->full_name }}</td>
                    <td class="px-4 py-3 text-muted">{{ $doctor->specialty->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($doctor->user->is_active)
                        <span class="badge rounded-pill text-bg-success px-3 py-2">Hoạt động</span>
                        @else
                        <span class="badge rounded-pill text-bg-secondary px-3 py-2">Ngừng HĐ</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection