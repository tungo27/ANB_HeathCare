<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm mới Bác sĩ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Block Hiển thị Lỗi Session Chung -->
                    @if (session('error'))
                        <div class="mb-4 text-sm text-red-600 bg-red-100 border border-red-400 p-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.doctors.store') }}" method="POST">
                        @csrf

                        <!-- Họ và Tên -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Họ và Tên</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email (Dùng để đăng
                                nhập)</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('email')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('phone')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Chuyên khoa -->
                        <div class="mb-4">
                            <label for="specialty_id" class="block text-sm font-medium text-gray-700">Chuyên
                                khoa</label>
                            <select name="specialty_id" id="specialty_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Chọn chuyên khoa --</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->id }}"
                                        {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialty_id')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tiểu sử -->
                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium text-gray-700">Tiểu sử / Giới
                                thiệu</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('bio') }}</textarea>
                            @error('bio')
                                <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Hành động -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.doctors.index') }}"
                                class="mr-4 text-sm text-gray-600 hover:text-gray-900">Hủy</a>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Lưu Bác sĩ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
