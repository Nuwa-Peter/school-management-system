<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check Out a Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('checkouts.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="user_id" :value="__('Select Student')" />
                                <select name="user_id" id="user_id" class="tom-select" required>
                                    <option value="">Select a student...</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('user_id') == $student->id)>
                                            {{ $student->name }} ({{ $student->unique_id }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="book_id" :value="__('Select Book')" />
                                <select name="book_id" id="book_id" class="tom-select" required>
                                    <option value="">Select a book...</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                            {{ $book->title }} - {{ $book->author }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                                <p class="text-sm text-gray-500 mt-1">Only books with available copies are shown.</p>
                            </div>

                            <div>
                                <x-input-label for="due_date" :value="__('Due Date')" />
                                <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', now()->addWeeks(2)->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('checkouts.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Check Out Book') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
