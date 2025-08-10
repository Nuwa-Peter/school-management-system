<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resource Booking System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div x-data="{ show: false, startTime: '', endTime: '' }" x-show="show" x-on:open-booking-modal.window="show = true; startTime = $event.detail.startStr; endTime = $event.detail.endStr;" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Book a Resource</h3>
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <div>
                        <x-input-label for="resource_id" :value="__('Select Resource')" />
                        <select name="resource_id" id="resource_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach($resources as $resource)
                                <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4">
                        <x-input-label for="start_time" :value="__('Start Time')" />
                        <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" x-model="startTime" required />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="end_time" :value="__('End Time')" />
                        <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" x-model="endTime" required />
                    </div>
                     <div class="mt-4">
                        <x-input-label for="notes" :value="__('Notes (Optional)')" />
                        <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <button type="button" @click="show = false" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</button>
                        <x-primary-button>
                            {{ __('Book Resource') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new Calendar(calendarEl, {
                plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($events),
                selectable: true,
                select: function(info) {
                    // Open modal with start and end times
                    window.dispatchEvent(new CustomEvent('open-booking-modal', { detail: { startStr: info.startStr, endStr: info.endStr } }));
                },
                eventClick: function(info) {
                    if (confirm("Are you sure you want to cancel this booking?")) {
                        let form = document.createElement('form');
                        form.action = `/bookings/${info.event.id}`;
                        form.method = 'POST';
                        form.innerHTML = `<input type="hidden" name="_method" value="DELETE">` +
                                         `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
