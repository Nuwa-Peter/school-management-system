@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <x-input-label for="title" :value="__('Book Title')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $book->title ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="author" :value="__('Author')" />
        <x-text-input id="author" class="block mt-1 w-full" type="text" name="author" :value="old('author', $book->author ?? '')" required />
        <x-input-error :messages="$errors->get('author')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="isbn" :value="__('ISBN (Optional)')" />
        <x-text-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn', $book->isbn ?? '')" />
        <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="publisher" :value="__('Publisher (Optional)')" />
        <x-text-input id="publisher" class="block mt-1 w-full" type="text" name="publisher" :value="old('publisher', $book->publisher ?? '')" />
        <x-input-error :messages="$errors->get('publisher')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="published_year" :value="__('Published Year (Optional)')" />
        <x-text-input id="published_year" class="block mt-1 w-full" type="number" name="published_year" :value="old('published_year', $book->published_year ?? '')" />
        <x-input-error :messages="$errors->get('published_year')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quantity" :value="__('Total Quantity')" />
        <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', $book->quantity ?? '')" required min="1" />
        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('books.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
        {{ __('Cancel') }}
    </a>

    <x-primary-button>
        {{ $buttonText ?? 'Save Book' }}
    </x-primary-button>
</div>
