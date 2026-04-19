<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-blue-900 mb-4">Find Your Doctor</h2>
                <form action="{{ route('patient.search') }}" method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ $searchTerm ?? '' }}" placeholder="Search by name or specialty..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-semibold transition-colors shadow-md">Search</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($doctors as $doctor)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ $doctor->user->avatar_url ?? 'https://via.placeholder.com/150' }}" class="w-16 h-16 rounded-full object-cover border-2 border-emerald-100">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $doctor->user->full_name }}</h3>
                                <p class="text-emerald-600 font-medium">{{ $doctor->Specialty->name }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-6">{{ $doctor->bio }}</p>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="text-sm">
                                <span class="text-gray-500 block">Experience</span>
                                <span class="font-bold text-gray-900">{{ $doctor->years_of_experience }} Years</span>
                            </div>
                            <a href="{{ route('patient.booking', $doctor->user_id) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 italic text-lg">No doctors found matching your criteria.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
