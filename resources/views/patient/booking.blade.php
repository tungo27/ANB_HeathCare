<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Doctor Info -->
                    <div class="bg-emerald-50 p-6 rounded-lg border border-emerald-100">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $doctor->user->avatar_url ?? 'https://via.placeholder.com/150' }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-emerald-200">
                            <div>
                                <h2 class="text-2xl font-bold text-emerald-900">{{ $doctor->user->full_name }}</h2>
                                <p class="text-emerald-600 font-medium">{{ $doctor->specialty?->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $doctor->qualification }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="font-semibold text-emerald-800 mb-2">About Doctor</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $doctor->bio }}</p>
                        </div>
                    </div>

                    <!-- Booking Section -->
                    <div>
                        <h3 class="text-xl font-bold text-blue-900 mb-4">Book an Appointment</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Select Date</label>
                            <input type="date" id="booking_date" min="{{ date('Y-m-d') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div id="slots_container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Available Slots</label>
                            <div id="slots_grid" class="grid grid-cols-3 gap-3">
                                <!-- AJAX loaded slots -->
                            </div>
                        </div>

                        <div id="booking_form" class="mt-6 hidden">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Symptoms (Optional)</label>
                                <textarea id="symptoms" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                            <button id="btn_confirm_booking"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 shadow-lg">
                                Confirm 1-Click Booking
                            </button>
                        </div>

                        <div id="no_slots" class="hidden mt-4 p-4 bg-red-50 text-red-700 rounded-md">
                            No available slots for this date.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let selectedSlotId = null;

            document.getElementById('booking_date').addEventListener('change', function() {
                const date = this.value;
                const doctorId = '{{ $doctor->user_id }}';

                fetch(`/patient/get-slots?doctor_id=${doctorId}&date=${date}`)
                    .then(res => res.json())
                    .then(slots => {
                        const grid = document.getElementById('slots_grid');
                        const container = document.getElementById('slots_container');
                        const noSlots = document.getElementById('no_slots');
                        const form = document.getElementById('booking_form');

                        grid.innerHTML = '';
                        if (slots.length > 0) {
                            slots.forEach(slot => {
                                const btn = document.createElement('button');
                                btn.className =
                                    'slot-btn border-2 border-blue-200 p-2 rounded text-center hover:bg-blue-50 transition-all';
                                btn.textContent = slot.start_time.substring(0, 5);
                                btn.onclick = () => selectSlot(slot.id, btn);
                                grid.appendChild(btn);
                            });
                            container.classList.remove('hidden');
                            noSlots.classList.add('hidden');
                        } else {
                            container.classList.add('hidden');
                            noSlots.classList.remove('hidden');
                            form.classList.add('hidden');
                        }
                    });
            });

            function selectSlot(id, btn) {
                selectedSlotId = id;
                document.querySelectorAll('.slot-btn').forEach(b => {
                    b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                    b.classList.add('border-blue-200');
                });
                btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.remove('border-blue-200');
                document.getElementById('booking_form').classList.remove('hidden');
            }

            document.getElementById('btn_confirm_booking').onclick = function() {
                const symptoms = document.getElementById('symptoms').value;

                fetch('/patient/book', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            schedule_id: selectedSlotId,
                            symptoms: symptoms
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = '/dashboard';
                        } else {
                            alert('Booking failed: ' + data.message);
                        }
                    })
                    .catch(err => alert('An error occurred. Please try again.'));
            };
        </script>
    @endpush
</x-app-layout>
