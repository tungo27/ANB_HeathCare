@extends('layouts.patient')
@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Đặt lịch khám với BS. {{ $doctor->user->full_name ?? 'Bác sĩ chưa có tên' }}</h2>
<div class="flex items-center gap-4 mb-6">
    {{-- Kiểm tra avatar ở bảng user hoặc doctor --}}
    <img src="{{ $doctor->user->avatar ?? asset('images/default.png') }}" class="w-16 h-16 rounded-full object-cover">
    <div>
        <h3 class="font-bold text-lg">BS. {{ $doctor->user->full_name ?? $doctor->user->name }}</h3>
        <p class="text-sm text-gray-500">{{ $doctor->Specialty?->name ?? 'Chuyên khoa Nội' }}</p>
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium mb-2">Chọn ngày khám</label>
    <input type="date" class="w-full p-3 border rounded-xl" min="{{ date('Y-m-d') }}">
</div>

<div class="mb-8">
    <label class="block text-sm font-semibold text-gray-700 mb-4">Chọn khung giờ phù hợp</label>
    
    @foreach($timeSlots as $session => $slots)
        <div class="mb-6">
            <h5 class="text-xs font-bold text-teal-700 uppercase tracking-wider mb-3 flex items-center">
                <span class="mr-2">
                    @if($session == 'Sáng') ☀️ @elseif($session == 'Chiều') 🌤️ @else 🌙 @endif
                </span>
                Ca {{ $session }}
            </h5>
            
            <div class="grid grid-cols-4 gap-3">
                @foreach($slots as $slot)
                    <button type="button" 
                        class="time-slot-btn py-2 text-sm font-medium border border-gray-200 rounded-xl hover:bg-teal-50 hover:border-teal-700 hover:text-teal-700 transition-all shadow-sm bg-white">
                        {{ $slot }}
                    </button>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<div class="mb-6">
    <label class="block text-sm font-medium mb-2">Mô tả triệu chứng bệnh</label>
    <textarea class="w-full p-4 border rounded-2xl" placeholder="Vui lòng mô tả chi tiết..."></textarea>
</div>

<div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
    <a href="{{ route('patient.dashboard') }}" 
       class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
        Hủy bỏ
    </a>
    <button type="submit" 
            class="px-8 py-2.5 text-sm font-bold text-white bg-teal-700 rounded-xl shadow-md hover:bg-teal-800 hover:shadow-lg transition-all transform active:scale-95">
        Xác nhận đặt lịch
    </button>
</div>
@endsection