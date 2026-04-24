@extends('layouts.patient')

@section('content')
    {{-- PHẦN THÔNG BÁO CHÈN VÀO ĐÂY --}}
    <div class="max-w-screen-xl mx-auto px-4">
        {{-- 1. Thông báo Thành công --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm flex items-center animate-bounce-short">
                <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- 2. Thông báo Lỗi (Ví dụ: Slot đã bị đặt) --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-800 font-bold">Đã có lỗi xảy ra:</span>
                </div>
                <ul class="list-disc list-inside text-red-700 text-sm ml-9">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <form action="{{ route('patient.search') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4 mb-10">
        {{-- Thanh nhập liệu: Chiếm 1/2 màn hình trên máy tính để tạo sự cân đối --}}
        <div class="relative w-full md:w-2/3 lg:w-1/2">
            <input type="text" name="search" value="{{ request('search') }}"
                class="block w-full pl-5 pr-12 py-2.5 border border-gray-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base outline-none transition-all"
                placeholder="Tìm tên bác sĩ hoặc chuyên khoa...">

            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- Nút Tìm kiếm: Kích thước vừa vặn, bo góc đồng nhất --}}
        <button type="submit"
            class="w-full md:w-auto px-8 py-2.5 bg-teal-700 text-white font-semibold text-sm rounded-xl shadow-sm hover:bg-teal-800 transition-all active:scale-95">
            TÌM KIẾM
        </button>
    </form>



    <div class="mb-12 text-left">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Chuyên khoa</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('patient.dashboard') }}" {{-- Nút Tất cả: Trở về trang dashboard không kèm tham số lọc --}}
                class="px-8 py-3 rounded-xl font-semibold text-base transition-all border-2
                href="{{ route('patient.dashboard') }}
                {{ !request('specialty') ? 'bg-teal-700 text-white shadow-lg shadow-teal-200' : 'bg-white border-2 border-gray-100 text-gray-600 hover:border-teal-500 hover:text-teal-600' }}">
                Tất cả
            </a>

            {{-- Lặp qua danh sách chuyên khoa đổ từ Controller sang --}}
            @foreach ($specialties as $item)
                <a href="{{ route('patient.dashboard', ['specialty' => $item->id]) }}"
                    class="px-8 py-3 rounded-xl font-semibold text-base transition-all border-2
                      {{ request('specialty') == $item->id
                          ? 'bg-teal-700 text-white border-transparent shadow-lg shadow-teal-200'
                          : 'bg-white border-gray-100 text-gray-600 hover:border-teal-500 hover:text-teal-600' }}">
                    {{ $item->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div id="doctor-team-section" class="scroll-mt-[100px] min-h-screen pt-10">

        <h2 class="text-2xl font-bold mb-8 text-gray-800">Đội ngũ Bác sĩ</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 pb-10">
            @foreach ($doctors as $doctor)
                <div class="transform transition duration-300 hover:-translate-y-2">
                    <x-doctor-card :doctor="$doctor" />
                </div>
            @endforeach
        </div>

    </div>
@endsection
