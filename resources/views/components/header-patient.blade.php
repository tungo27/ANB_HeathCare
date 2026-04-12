@extends('layouts.app')

@section('content')
  <aside class="w-64 bg-white border-r flex flex-col h-full">
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
    </aside>




@endsection