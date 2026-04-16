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
                                    class="nav-link {{ request('status') == 'pending' ? 'active fw-bold text-warning' : 'text-secondary' }}">
                                    Chờ khám
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('doctor.appointments', ['status' => 'completed']) }}"
                                    class="nav-link {{ request('status') == 'completed' ? 'active fw-bold text-success' : 'text-secondary' }}">
                                    Đã khám
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('doctor.appointments', ['status' => 'cancelled']) }}"
                                    class="nav-link {{ request('status') == 'cancelled' ? 'active fw-bold text-danger' : 'text-secondary' }}">
                                    Đã hủy
                                </a>
                            </li>
                        </ul>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bệnh nhân</th>
                                        <th>Ngày khám</th>
                                        <th>Trạng thái</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $item)
                                        <tr>
                                            <td>
                                                {{-- Giả sử bạn có quan hệ patient() trả về User --}}
                                                <div class="fw-medium text-dark">{{ $item->patient->name ?? 'Không rõ' }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->appointment_date ? \Carbon\Carbon::parse($item->appointment_date)->format('d/m/Y') : 'Chưa xác định' }}
                                            </td>
                                            <td>
                                                @if ($item->status == 'completed')
                                                    <span class="badge rounded-pill bg-success-subtle text-success px-3">Đã
                                                        khám</span>
                                                @elseif ($item->status == 'cancelled')
                                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Đã
                                                        hủy</span>
                                                @else
                                                    <span class="badge rounded-pill bg-warning-subtle text-warning px-3">Chờ
                                                        khám</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3">Chi
                                                    tiết</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-5 text-center text-muted">Không có lịch hẹn nào.
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
