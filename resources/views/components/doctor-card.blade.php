@props(['doctor'])

<div
    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col items-center text-center transition hover:shadow-md">
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRVHCDgl-nyIJ1iSz1vzjcOAT5dF1XVE9231A&s" alt="Bác sĩ"
        {{-- <img src="{{ $doctor->avatar ?? asset('images/default-avatar.png') }}" alt="Bác sĩ" --}} alt="Bác sĩ" class="w-24 h-24 rounded-full object-cover mb-4 border-2 border-teal-50">
    <h4 class="font-bold text-gray-800">BS. {{ $doctor->user->full_name ?? 'Bác sĩ chưa có tên' }}</h4>
    <p class="text-xs text-gray-500 mb-6">{{ $doctor->specialty?->name ?? 'N/A' }}</p>
    <a href="{{ route('patient.booking.select-slot', ['doctor' => $doctor->user_id ?? $doctor->id]) }}"
        class="w-full py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-sm font-medium transition inline-block text-center">
        Chọn bác sĩ
    </a>
</div>
