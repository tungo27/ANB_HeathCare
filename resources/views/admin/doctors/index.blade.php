@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white">Danh sách Bác sĩ</h5>
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                    <i class="bi bi-plus-lg"></i> Thêm Bác sĩ
                </a>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Họ và Tên</th>
                                <th scope="col">Chuyên khoa</th>
                                <th scope="col">Kinh nghiệm</th>
                                <th scope="col">Điện thoại</th>
                                <th scope="col" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($doctors as $doctor)
                                <tr>
                                    <td><span class="fw-bold">{{ $doctor->user->full_name ?? 'N/A' }}</span></td>
                                    <td><span class="badge bg-info text-dark">{{ $doctor->specialty->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $doctor->years_of_experience }} năm</td>
                                    <td>{{ $doctor->user->phone ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        <a href="{{ isset($doctor->user->id) ? route('admin.doctors.edit', $doctor->user->id) : '#' }}"
                                            class="btn btn-sm btn-warning text-dark fw-medium">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </a>

                                        <form
                                            action="{{ isset($doctor->user->id) ? route('admin.doctors.destroy', $doctor->user->id) : '#' }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-medium ms-1">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu bác sĩ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
