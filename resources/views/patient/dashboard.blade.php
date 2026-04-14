@extends('layouts.patient')

@section('content')

        <div class="flex justify-between items-center mb-10">
    <div class="relative w-full md:w-2/3 lg:w-1/2">
        <input type="text" 
               class="block w-full pl-6 pr-4 py-4 border border-gray-200 rounded-2xl bg-white shadow-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg outline-none transition-all" 
               placeholder="Tìm tên bác sĩ hoặc bệnh lý...">
        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
</div>

<div class="bg-white p-8 rounded-3xl shadow-md border border-gray-100 flex items-center mb-12 w-fit pr-24 transition-transform hover:scale-105">
    <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mr-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>
    <div>
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bác sĩ trực hôm nay</p>
        <h2 class="text-3xl font-black text-gray-800">18 <span class="text-xl font-bold">bác sĩ</span></h2>
    </div>
</div>

<div class="mb-12 text-left">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Chuyên khoa</h3>
    <div class="flex flex-wrap gap-4">
        <button class="px-8 py-3 bg-teal-700 text-white rounded-xl font-semibold text-base shadow-lg shadow-teal-200 transition-all hover:bg-teal-800">Tất cả</button>
        <button class="px-8 py-3 bg-white border-2 border-gray-100 text-gray-600 rounded-xl font-semibold text-base transition-all hover:border-teal-500 hover:text-teal-600">Nội khoa</button>
        <button class="px-8 py-3 bg-white border-2 border-gray-100 text-gray-600 rounded-xl font-semibold text-base transition-all hover:border-teal-500 hover:text-teal-600">Nhi khoa</button>
        <button class="px-8 py-3 bg-white border-2 border-gray-100 text-gray-600 rounded-xl font-semibold text-base transition-all hover:border-teal-500 hover:text-teal-600">Sản khoa</button>
    </div>
</div>

<div class="flex items-center justify-between mb-8">
    <h3 class="text-2xl font-bold text-gray-800">Đội ngũ Bác sĩ</h3>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 pb-10">
    @foreach($doctors as $doctor)
        <div class="transform transition duration-300 hover:-translate-y-2">
            <x-doctor-card :doctor="$doctor" />
        </div>
    @endforeach
</div>

@endsection