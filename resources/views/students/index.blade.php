<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search and Filters -->
                    <form action="{{ route('students.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <x-input-label for="search" :value="__('Search by Name or LIN')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                            </div>
                            <div>
                                <x-input-label for="stream_id" :value="__('Filter by Stream')" />
                                <select id="stream_id" name="stream_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All Streams</option>
                                    @foreach($streams as $stream)
                                        <option value="{{ $stream->id }}" @selected(request('stream_id') == $stream->id)>
                                            {{ $stream->classLevel->name }} {{ $stream->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-primary-button class="mt-7">
                                    {{ __('Filter') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="mb-4 flex flex-wrap gap-2">
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add New Student</a>
                        <a href="{{ route('students.upload.form') }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Upload Students (Excel)</a>
                        <a href="{{ route('students.export.pdf') }}" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Download as PDF</a>
                        <a href="{{ route('students.export.excel') }}" class="px-4 py-2 bg-teal-500 text-white rounded-md hover:bg-teal-600">Download as Excel</a>
                    </div>

                    <!-- Students Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Name</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Student ID</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">LIN</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Stream(s)</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($students as $student)
                                    <tr class="border-b">
                                        <td class="py-3 px-4">{{ $student->name }}</td>
                                        <td class="py-3 px-4">{{ $student->unique_id }}</td>
                                        <td class="py-3 px-4">{{ $student->lin }}</td>
                                        <td class="py-3 px-4">
                                            @foreach($student->streams as $stream)
                                                {{ $stream->classLevel->name }} {{ $stream->name }}@if(!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('students.id-card', $student) }}" class="text-indigo-500 hover:underline">ID Card</a>
                                            @if($student->streams->first())
                                                <a href="{{ route('students.report-card', ['user' => $student, 'stream' => $student->streams->first()]) }}" class="text-blue-500 hover:underline ml-4">Report Card</a>
                                            @endif
                                            <button @click="$dispatch('open-photo-modal', { studentId: {{ $student->id }}, studentName: '{{ $student->name }}' })" class="text-green-500 hover:underline ml-4">Upload Photo</button>
                                            <a href="{{ route('profile.edit') }}" class="text-yellow-500 hover:underline ml-4">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 px-4 text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Upload Modal -->
    <div x-data="{ show: false, studentId: null, studentName: null }" x-show="show" x-on:open-photo-modal.window="show = true; studentId = $event.detail.studentId; studentName = $event.detail.studentName" style="display: none;" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="'Upload Photo for ' + studentName"></h3>
                <div class="mt-2 px-7 py-3">
                    <form :action="'/students/' + studentId + '/photo'" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Webcam and Preview -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <video id="webcam" autoplay playsinline width="320" height="240" class="border"></video>
                                <canvas id="canvas" class="hidden"></canvas>
                                <button type="button" id="snap" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded-md">Take Photo</button>
                            </div>
                            <div>
                                <img id="preview" src="#" alt="Image Preview" class="hidden w-full h-auto border">
                            </div>
                        </div>

                        <input type="hidden" name="photo_data" id="photo_data">

                        <x-input-label for="photo_upload" :value="__('Or Upload a File')" />
                        <input id="photo_upload" name="photo_upload" type="file" accept="image/*" class="mt-1 block w-full">

                        <div class="items-center px-4 py-3">
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button @click="show = false" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            const video = document.getElementById('webcam');
            const canvas = document.getElementById('canvas');
            const snap = document.getElementById('snap');
            const preview = document.getElementById('preview');
            const photo_upload = document.getElementById('photo_upload');
            const photo_data = document.getElementById('photo_data');

            let stream;

            window.addEventListener('open-photo-modal', async () => {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        video.srcObject = stream;
                    } catch (err) {
                        console.error("Error accessing webcam: ", err);
                        alert('Could not access webcam. Please ensure you have given permission.');
                    }
                }
            });

            window.addEventListener('close', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });

            snap.addEventListener('click', () => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const dataUrl = canvas.toDataURL('image/jpeg');
                preview.src = dataUrl;
                preview.classList.remove('hidden');
                photo_data.value = dataUrl;
                photo_upload.value = ''; // Clear file input
            });

            photo_upload.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        photo_data.value = ''; // Clear webcam data
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
