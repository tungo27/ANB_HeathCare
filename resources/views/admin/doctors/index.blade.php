@extends('layouts.admin')

@section('title', 'Quản lý Bác sĩ')

@section('content')
    <div class="container py-5">
        {{-- Header --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 bg-white p-4 rounded-3 shadow-sm border">
            <div>
                <h3 class="fw-bold text-dark mb-1">Danh sách Bác sĩ</h3>
                <p class="text-muted small mb-0">Quản lý thông tin hồ sơ và chuyên khoa của đội ngũ bác sĩ.</p>
            </div>
            <a href="{{ route('admin.doctors.doctorCreate') }}"
                class="btn btn-success d-inline-flex align-items-center mt-3 mt-md-0 shadow-sm px-4">
                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                THÊM BÁC SĨ
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show  border-start border-4 border-success shadow-sm mb-4"
                role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-start border-4 border-danger shadow-sm mb-4"
                role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">Họ và Tên</th>
                            <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">Liên hệ</th>
                            <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">Chuyên khoa</th>
                            <th class="px-4 py-3 text-secondary small fw-bold text-uppercase">Kinh nghiệm</th>
                            <th class="px-4 py-3 text-secondary small fw-bold text-uppercase text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doctors as $doctor)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark">
                                        {{ $doctor->user->full_name ?? 'Không xác định' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-dark small">{{ $doctor->user->email ?? 'N/A' }}</div>
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">
                                        {{ $doctor->user->phone ?? 'Chưa có số' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge rounded-pill bg-info text-dark fw-medium px-3">
                                        {{ $doctor->specialty->name ?? 'Chưa cập nhật' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-secondary small">
                                    {{ $doctor->years_of_experience }} năm
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ isset($doctor->user->id) ? route('admin.doctors.doctorEdit', ['doctor' => $doctor->user->id]) : '#' }}"
                                            class="btn btn-sm btn-light text-primary border shadow-sm px-3">
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ isset($doctor->user->id) ? route('admin.doctors.doctorDestroy', ['doctor' => $doctor->user->id]) : '#' }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-light text-danger border shadow-sm px-3">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-5 text-center text-muted fst-italic">
                                    Hiện tại chưa có dữ liệu bác sĩ nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $doctors->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
