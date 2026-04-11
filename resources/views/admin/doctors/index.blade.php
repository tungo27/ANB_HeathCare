@extends('layouts.admin')

@section('title', 'Quản lý Bác sĩ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">
@endpush

@section('content')
    <div class="admin-container">
        <div class="header-flex">
            <h3 class="primary">Danh sách Bác sĩ</h3>
            <a href="{{ route('admin.doctors.doctorCreate') }}" class="btn-primary">Thêm Bác sĩ</a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Họ và Tên</th>
                        <th>Email</th>
                        <th>Chuyên khoa</th>
                        <th>Kinh nghiệm (năm)</th>
                        <th>Điện thoại</th>
                        <th style="text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr>
                            <td><strong>{{ $doctor->user->full_name ?? 'Không xác định' }}</strong></td>
                            <td>{{ $doctor->user->email ?? 'N/A' }}</td>
                            <td>{{ $doctor->specialties->name ?? 'Chưa cập nhật' }}</td>
                            <td>{{ $doctor->years_of_experience }} năm</td>
                            <td>{{ $doctor->user->phone ?? 'Chưa có số' }}</td>
                            <td style="text-align: right;">

                                <a href="{{ isset($doctor->user->id) ? route('admin.doctors.doctorEdit', ['doctor' => $doctor->user->id]) : '#' }}"
                                    class="btn-text-primary">Sửa</a>


                                <form
                                    action="{{ isset($doctor->user->id) ? route('admin.doctors.doctorDestroy', ['doctor' => $doctor->user->id]) : '#' }}"
                                    method="POST" class="delete-form" style="display: inline-block;"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-text-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Hiện tại chưa có dữ liệu bác sĩ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 20px;">
            {{ $doctors->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/doctor.js') }}"></script>
@endpush
