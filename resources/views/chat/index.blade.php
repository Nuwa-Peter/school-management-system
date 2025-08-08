<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="chat-window" class="h-96 overflow-y-auto border border-gray-300 rounded p-4 mb-4">
                        <!-- Messages will be appended here -->
                    </div>
                    <form id="chat-form">
                        @csrf
                        <div class="flex">
                            <input type="text" id="message" name="message" class="w-full border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Type your message...">
                            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('VITE_PUSHER_APP_KEY') }}',
                cluster: '{{ env('VITE_PUSHER_APP_CLUSTER') }}',
                forceTLS: true
            });

            const chatWindow = document.getElementById('chat-window');
            const chatForm = document.getElementById('chat-form');
            const messageInput = document.getElementById('message');
            const authUserId = {{ auth()->id() }};

            window.Echo.private('chat')
                .listen('MessageSent', (e) => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('mb-2');

                    let alignmentClass = e.user.id === authUserId ? 'text-right' : 'text-left';
                    let bgColor = e.user.id === authUserId ? 'bg-indigo-100' : 'bg-gray-100';

                    messageElement.innerHTML = `
                        <div class="${alignmentClass}">
                            <div class="inline-block p-2 rounded-lg ${bgColor}">
                                <strong class="font-bold">${e.user.first_name} ${e.user.last_name}:</strong>
                                <span>${e.message}</span>
                            </div>
                        </div>`;
                    chatWindow.appendChild(messageElement);
                    chatWindow.scrollTop = chatWindow.scrollHeight;
                });

            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value;

                if (message.trim() === '') {
                    return;
                }

                axios.post('{{ route('teacher.chat.send') }}', {
                    message: message
                })
                .then(response => {
                    messageInput.value = '';
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
