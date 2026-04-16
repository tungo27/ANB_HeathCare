@extends('layouts.app') {{-- Hoặc tên file layout chính của bạn --}}

@section('main_content')
    @include('components.header-doctor')
    <div class="min-vh-100 d-flex">
        <div class="d-flex flex-column flex-grow-1">
            {{-- Header --}}
            <header class="bg-white border-bottom py-3 px-4">
                <h2 class="fw-semibold fs-4 text-dark mb-0">
                    {{ __('Danh sách ca trực của tôi') }}
                </h2>
            </header>

            {{-- Main Content --}}
            <main class="p-4">
                <div class="container-fluid">
                    <div class="card shadow-sm p-4">
                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Tab lọc trạng thái --}}
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item">
                                <a href="{{ route('doctor.dashboard') }}"
                                    class="nav-link {{ !request('status') ? 'active fw-bold' : 'text-secondary' }}">
                                    Tất cả
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('doctor.dashboard', ['status' => 'available']) }}"
                                    class="nav-link {{ request('status') == 'available' ? 'active fw-bold' : 'text-secondary' }}">
                                    Sẵn sàng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('doctor.dashboard', ['status' => 'booked']) }}"
                                    class="nav-link {{ request('status') == 'booked' ? 'active fw-bold' : 'text-secondary' }}">
                                    Đã được đặt
                                </a>
                            </li>
                        </ul>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày làm việc</th>
                                        <th>Khung giờ</th>
                                        <th>Phòng</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $item)
                                        <tr>
                                            <td>{{ $item->work_date ? $item->work_date->format('d/m/Y') : 'Chưa xác định' }}
                                            </td>
                                            <td class="font-monospace">
                                                {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}
                                                <span class="mx-1">→</span>
                                                {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                            </td>
                                            <td>{{ $item->room }}</td>
                                            <td class="text-center">
                                                @if ($item->status == 1)
                                                    <span class="badge rounded-pill bg-success-subtle text-success px-3">Sẵn
                                                        sàng</span>
                                                @elseif ($item->status == 2)
                                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Đã
                                                        đặt</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-5 text-center text-muted">
                                                Bạn chưa có ca trực nào.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $schedules->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
