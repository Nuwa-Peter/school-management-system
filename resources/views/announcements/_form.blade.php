@csrf
<div class="space-y-6">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $announcement->title ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="content" :value="__('Content')" />
        <textarea name="content" id="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content', $announcement->content ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="start_date" :value="__('Visible From')" />
            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', optional($announcement->start_date ?? null)->format('Y-m-d'))" required />
            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="end_date" :value="__('Visible Until')" />
            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', optional($announcement->end_date ?? null)->format('Y-m-d'))" required />
            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('announcements.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
        {{ __('Cancel') }}
    </a>

    <x-primary-button>
        {{ $buttonText ?? 'Save Announcement' }}
    </x-primary-button>
</div>
