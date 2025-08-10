@props(['active' => false])

<div x-data="{
    open: @json($active),
    positionDropdown() {
        const trigger = this.$refs.trigger;
        const dropdown = this.$refs.dropdown;
        const rect = trigger.getBoundingClientRect();
        dropdown.style.left = `${rect.right + 5}px`;
        dropdown.style.top = `${rect.top}px`;
    }
}" @resize.window="if(open) positionDropdown()">

    <button @click="open = !open; $nextTick(() => positionDropdown())" x-ref="trigger" class="w-full flex items-center justify-between p-2 text-gray-300 hover:bg-gray-700 rounded-md" :class="{'bg-gray-700': open}">
        <div class="flex items-center">
            {{ $trigger }}
        </div>
        <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform duration-200" ::class="{'rotate-180': open}" />
    </button>

    <template x-teleport="body">
        <div x-show="open"
             @click.away="open = false"
             x-ref="dropdown"
             class="fixed z-50 mt-2 bg-gray-700 text-white rounded-md shadow-lg p-2 space-y-2"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-x-2"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-2">
            {{ $content }}
        </div>
    </template>
</div>
