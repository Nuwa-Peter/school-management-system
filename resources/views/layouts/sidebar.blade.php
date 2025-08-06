<aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <div class="mb-10 text-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="w-20 h-20 mx-auto mb-2 rounded-full">
            <h1 class="text-xl font-bold text-white">St. Joseph's VSS</h1>
        </a>
    </div>
    <nav>
        <ul>
            <li class="mb-2">
                <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-home class="w-6 h-6 mr-3" />
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('users.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('users.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-users class="w-6 h-6 mr-3" />
                    <span>User Management</span>
                </a>
            </li>
            <!-- More links will be added here -->
        </ul>
    </nav>
</aside>
