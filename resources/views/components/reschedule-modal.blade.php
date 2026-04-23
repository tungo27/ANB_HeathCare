<!-- Reschedule Appointment Modal Snippet -->
<div id="rescheduleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reschedule Appointment</h3>

        <!-- Action targets the new patient reschedule route -->
        <form action="{{ route('patient.appointments.reschedule', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="new_schedule_id" class="block text-sm font-medium text-gray-700">Select New Time Slot</label>
                <select name="new_schedule_id" id="new_schedule_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    <option value="" disabled selected>-- Choose a new available slot --</option>
                    {{-- Assumes $availableSchedules is passed to the view via the Controller --}}
                    @foreach ($availableSchedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ \Carbon\Carbon::parse($schedule->work_date)->format('M d, Y') }} |
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeRescheduleModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Confirm
                    Reschedule</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRescheduleModal() {
        document.getElementById('rescheduleModal').classList.remove('hidden');
    }

    function closeRescheduleModal() {
        document.getElementById('rescheduleModal').classList.add('hidden');
    }
</script>
