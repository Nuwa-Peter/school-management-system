<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Library Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-700">Manage Books</h3>
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Add New Book') }}
                        </a>
                    </div>

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

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('books.index') }}" class="mb-6">
                        <div class="flex items-end">
                            <div class="flex-grow">
                                <x-input-label for="search" :value="__('Search by Title, Author, or ISBN')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                            </div>
                            <div class="ml-4">
                                <x-primary-button>
                                    {{ __('Search') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                            <thead>
                                <tr class="text-left">
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Title</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Author</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">ISBN</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Quantity</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Available</th>
                                    <th class="bg-gray-50 sticky top-0 border-b border-gray-200 px-6 py-3 text-gray-600 font-bold tracking-wider uppercase text-xs">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($books as $book)
                                    <tr>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $book->title }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $book->author }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $book->isbn }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $book->quantity }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">{{ $book->available_quantity }}</td>
                                        <td class="border-dashed border-t border-gray-200 px-6 py-3">
                                            <a href="{{ route('books.show', $book) }}" class="text-green-600 hover:text-green-900">View</a>
                                            <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10 px-6 text-gray-500">
                                            No books found in the catalog.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
