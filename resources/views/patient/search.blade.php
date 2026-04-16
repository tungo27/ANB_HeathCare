@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Kết quả tìm kiếm cho: <span class="text-teal-600">"{{ $searchTerm }}"</span>
    </h2>

    @if($doctors->isEmpty())
        <div class="bg-white p-10 rounded-3xl shadow-md text-center">
            <p class="text-gray-500 italic">Không tìm thấy bác sĩ nào phù hợp với yêu cầu của bạn.</p>
            <a href="{{ route('patient.dashboard') }}" class="inline-block mt-4 text-teal-600 font-bold hover:underline">
                 Quay lại trang chủ
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($doctors as $doctor)
                <div class="transform transition duration-300 hover:-translate-y-2">
                    <x-doctor-card :doctor="$doctor" />
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection