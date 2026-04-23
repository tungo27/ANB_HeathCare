@extends('layouts.patient')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Lịch sử đặt lịch khám</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Danh sách lịch hẹn</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bác sĩ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày/Giờ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Triệu
                                chứng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                                thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao
                                tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $appointment->schedule?->doctor?->user?->full_name ?? 'N/A' }}
                                    </div>
                                    {{-- <div class="text-sm text-gray-500">
                                        {{ $appointment->schedule?->doctor?->specialty?->name ?? 'N/A' }}
                                    </div> --}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment->schedule->work_date)->format('d/m/Y') }}
                                    </div>
                                    {{-- <div class="text-sm text-gray-500">
                                        {{ $appointment->slot ? \Carbon\Carbon::parse($appointment->slot->slot_start_time)->format('H:i') : 'N/A' }}
                                    </div> --}}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $appointment->symptoms ? Str::limit($appointment->symptoms, 50) : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($appointment->status)
                                        @case('pending')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Chờ duyệt
                                            </span>
                                        @break

                                        @case('confirmed')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Đã xác nhận
                                            </span>
                                        @break

                                        @case('completed')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã khám
                                            </span>
                                        @break

                                        @case('cancelled')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Đã hủy
                                            </span>
                                        @break

                                        @case('rejected')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Bác sĩ từ chối
                                            </span>
                                        @break

                                        @case('no_show')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Không đến
                                            </span>
                                        @break

                                        @case('rescheduled')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Đã đổi lịch
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $appointment->status }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('patient.appointments.show', $appointment->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Xem chi tiết</a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Bạn chưa có lịch hẹn nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
