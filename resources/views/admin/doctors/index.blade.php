@extends('layouts.admin')

@section('title', 'Quản lý Bác sĩ')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Danh sách Bác sĩ</h3>
                <p class="text-sm text-gray-500 mt-1">Quản lý thông tin hồ sơ và chuyên khoa của đội ngũ bác sĩ.</p>
            </div>
            <a href="{{ route('admin.doctors.doctorCreate') }}"
                class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Thêm Bác sĩ
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Họ và
                                Tên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liên
                                hệ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Chuyên khoa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kinh
                                nghiệm</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành
                                động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($doctors as $doctor)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $doctor->user->full_name ?? 'Không xác định' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $doctor->user->email ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $doctor->user->phone ?? 'Chưa có số' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">
                                        {{ $doctor->specialties->name ?? 'Chưa cập nhật' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doctor->years_of_experience }} năm
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ isset($doctor->user->id) ? route('admin.doctors.doctorEdit', ['doctor' => $doctor->user->id]) : '#' }}"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">Sửa</a>

                                    <form
                                        action="{{ isset($doctor->user->id) ? route('admin.doctors.doctorDestroy', ['doctor' => $doctor->user->id]) : '#' }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bác sĩ này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md transition">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                    Hiện tại chưa có dữ liệu bác sĩ nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $doctors->links() }}
        </div>
    </div>
@endsection
