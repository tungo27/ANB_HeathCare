<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Danh sách ca trực của tôi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="mb-6 flex space-x-2 border-b">
                    <a href="{{ route('doctor.dashboard') }}"
                        class="pb-2 px-4 text-sm font-medium {{ !request('status') ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Tất cả
                    </a>
                    <a href="{{ route('doctor.dashboard', ['status' => 'available']) }}"
                        class="pb-2 px-4 text-sm font-medium {{ request('status') == 'available' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Sẵn sàng
                    </a>
                    <a href="{{ route('doctor.dashboard', ['status' => 'booked']) }}"
                        class="pb-2 px-4 text-sm font-medium {{ request('status') == 'booked' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Đã được đặt
                    </a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2">
                            <th class="py-3 px-2">Ngày làm việc</th>
                            <th class="py-3 px-2">Khung giờ</th>
                            <th class="py-3 px-2">Phòng</th>
                            <th class="py-3 px-2 text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-2">
                                    {{ $item->work_date ? $item->work_date->format('d/m/Y') : 'Chưa xác định' }}
                                </td>
                                <td class="py-3 px-2">
                                    <span
                                        class="font-mono">{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}</span>
                                    →
                                    <span
                                        class="font-mono">{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</span>
                                </td>
                                <td class="py-3 px-2">{{ $item->room }}</td>
                                <td class="py-3 px-2 text-center">
                                    @if ($item->is_available)
                                        <span class="text-green-600 bg-green-100 px-2 py-1 rounded-full text-xs">Sẵn
                                            sàng để đặt</span>
                                    @else
                                        <span class="text-red-600 bg-red-100 px-2 py-1 rounded-full text-xs">Đã được
                                            đặt</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-500">
                                    Bạn chưa có ca trực nào. Hãy nhấn nút "Thêm ca trực mới".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
