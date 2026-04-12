@extends('layouts.app')

@section('content')
<div class="flex min-h-[calc(100vh-4rem)] bg-gray-50 overflow-hidden">
    
    {{-- <aside class="w-64 bg-white border-r flex flex-col h-full">
        <div class="p-6">
            <div class="flex items-center space-x-2 mb-10">
                <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">V</div>
                <div>
                    <h1 class="font-bold text-gray-800 leading-tight">V</h1>
                    <p class="text-xs text-gray-500">Hospital</p>
                </div>
            </div>
            <nav class="space-y-4">
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-teal-50 text-teal-700 border-l-4 border-teal-700 transition">
                    Trang chủ
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-teal-600 transition">
                    Danh sách Bác sĩ
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-teal-600 transition">
                    Đặt lịch khám
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-teal-600 transition">
                    Lịch sử khám
                </a>
            </nav>
        </div>
        <div class="p-6 border-t mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center text-gray-500 hover:text-red-600 transition w-full text-sm font-medium">
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside> --}}

    <main class="flex-1 h-full overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <div class="relative w-1/2">
                <input type="text" class="block w-full pl-4 pr-3 py-2 border border-gray-200 rounded-xl bg-white shadow-sm focus:ring-teal-500" placeholder="Tìm tên bác sĩ hoặc bệnh lý">
            </div>
            {{-- <div class="w-10 h-10 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center">
                User
            </div> --}}
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8 w-fit pr-20">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mr-4">Icon</div>
            <div>
                <p class="text-xs text-gray-500">Bác sĩ trực hôm nay</p>
                <h2 class="text-xl font-bold text-gray-800">18 bác sĩ</h2>
            </div>
        </div>

        <div class="mb-10 text-left">
            <h3 class="font-bold text-gray-800 mb-4">Chuyên khoa</h3>
            <div class="flex flex-wrap gap-3">
                <button class="px-5 py-2 bg-teal-700 text-white rounded-lg text-sm">Tất cả</button>
                <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm">Nội khoa</button>
                <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm">Nhi khoa</button>
                <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm">Sản khoa</button>
            </div>
        </div>

        <h3 class="font-bold text-gray-800 mb-6 text-left">Đội ngũ Bác sĩ</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-10">
            @foreach($doctors as $doctor)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col items-center text-center">
                <img src="{{ $doctor->avatar ?? 'https://via.placeholder.com/150' }}" class="w-24 h-24 rounded-full object-cover mb-4 border-2 border-teal-50">
                <h4 class="font-bold text-gray-800">BS. {{ $doctor->full_name }}</h4>
                <p class="text-xs text-gray-500 mb-6">{{ $doctor->specialty }}</p>
                <button class="w-full py-2 bg-teal-700 text-white rounded-xl text-sm font-medium hover:bg-teal-800 transition">
                    Chọn bác sĩ
                </button>
            </div>
            @endforeach
        </div>
    </main>
</div>
@endsection