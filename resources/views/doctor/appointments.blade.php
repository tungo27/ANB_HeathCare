@extends('layouts.app')

@section('main_content')
@include('components.header-doctor')
<div class="min-vh-100 d-flex">
    <div class="d-flex flex-column flex-grow-1">
        {{-- Header --}}
        <header class="bg-white border-bottom py-3 px-4">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Danh sách lịch hẹn khám') }}
            </h2>
        </header>

        {{-- Main Content --}}
        <main class="p-4">
            <div class="container-fluid">
                <div class="card shadow-sm p-4 border-0">

                    {{-- Tab lọc trạng thái --}}
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a href="{{ route('doctor.appointments') }}"
                                class="nav-link {{ !request('status') || request('status') == 'all' ? 'active fw-bold' : 'text-secondary' }}">
                                Tất cả
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('doctor.appointments', ['status' => 'pending']) }}"
                                class="nav-link {{ request('status') == 'pending' ? 'active fw-bold text-secondary' : 'text-secondary' }}">
                                <i class="bi bi-clock"></i> Chờ duyệt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('doctor.appointments', ['status' => 'confirmed']) }}"
                                class="nav-link {{ request('status') == 'confirmed' ? 'active fw-bold text-warning' : 'text-secondary' }}">
                                <i class="bi bi-calendar-check"></i> Chờ khám
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('doctor.appointments', ['status' => 'completed']) }}"
                                class="nav-link {{ request('status') == 'completed' ? 'active fw-bold text-success' : 'text-secondary' }}">
                                <i class="bi bi-check-circle"></i> Đã khám
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('doctor.appointments', ['status' => 'rejected']) }}"
                                class="nav-link {{ request('status') == 'rejected' ? 'active fw-bold text-danger' : 'text-secondary' }}">
                                <i class="bi bi-x-circle"></i> Đã hủy
                            </a>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Bệnh nhân</th>
                                    <th>Ngày/Giờ</th>
                                    <th>Triệu chứng</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $item)
                                <tr>
                                    {{-- 1. Bệnh nhân --}}
                                    <td>
                                        <div class="fw-medium text-dark">{{ $item->patient->full_name ?? 'Không rõ' }}</div>
                                    </td>

                                    {{-- 2. Ngày/Giờ --}}
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->schedule->work_date)->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $item->slot ? \Carbon\Carbon::parse($item->slot->slot_start_time)->format('H:i') : 'Chưa cập nhật' }}</small>
                                    </td>

                                    {{-- 3. Triệu chứng --}}
                                    <td>{{ $item->symptoms ? Str::limit($item->symptoms, 30) : '-' }}</td>

                                    {{-- 4. Trạng thái (Đã bổ sung đầy đủ theo Migration) --}}
                                    <td>
                                        @switch($item->status)
                                        @case('pending')
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3">Chờ duyệt</span>
                                        @break
                                        @case('completed')
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3">Đã khám</span>
                                        @break
                                        @case('confirmed')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Chờ khám</span>
                                        @break
                                        @case('rescheduled')
                                        <span class="badge rounded-pill bg-info-subtle text-info px-3">Đã đổi lịch</span>
                                        @break
                                        @case('cancelled')
                                        @case('rejected')
                                        @case('no_show')
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3">
                                            {{ match($item->status) {
                                    'cancelled' => 'Đã hủy',
                                    'rejected'  => 'Bác sĩ từ chối',
                                    'no_show'   => 'Không đến',
                                } }}
                                        </span>
                                        @break
                                        @default
                                        <span class="badge rounded-pill bg-secondary px-3">{{ $item->status }}</span>
                                        @endswitch
                                    </td>

                                    {{-- 5. Thao tác --}}
                                    <td class="text-center">
                                        @if($item->status === 'pending')
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form action="{{ route('doctor.appointments.accept', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                                    <i class="bi bi-check-circle"></i> Chấp nhận
                                                </button>
                                            </form>
                                            <form action="{{ route('doctor.appointments.reject', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                                                    <i class="bi bi-x-circle"></i> Từ chối
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <a href="{{ route('doctor.appointments.show', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Chi tiết
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                        Không có lịch hẹn nào phù hợp.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection