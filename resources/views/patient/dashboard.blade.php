@extends('layouts.patient')

@section('content')
{{-- <div class="flex min-h-[calc(100vh-4rem)] bg-gray-50 overflow-hidden"> --}}
        {{-- <main class="flex-1 h-full overflow-y-auto p-8"> --}}
        <div class="flex justify-between items-center mb-8">
            <div class="relative w-1/2">
                <input type="text" class="block w-full pl-4 pr-3 py-2 border border-gray-200 rounded-xl bg-white shadow-sm focus:ring-teal-500" placeholder="Tìm tên bác sĩ hoặc bệnh lý">
            </div>
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
            <x-doctor-card :doctor="$doctor" />
            @endforeach
        </div>
    {{-- </main>
</div> --}}
@endsection