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
                <a href="{{ route('students.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('students.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-users class="w-6 h-6 mr-3" />
                    <span>Student Management</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('class-levels.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('class-levels.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-academic-cap class="w-6 h-6 mr-3" />
                    <span>Classes & Streams</span>
                </a>
            </li>
            <li class="mb-2">
                <x-sidebar-dropdown :active="request()->routeIs('subjects.*')">
                    <x-slot name="trigger">
                        <x-heroicon-o-book-open class="w-6 h-6 mr-3" />
                        <span>Subjects</span>
                    </x-slot>
                    <x-slot name="content">
                        <a href="{{ route('subjects.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Manage Subjects</a>
                        {{-- Placeholder for future link --}}
                        <a href="#" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Assign Subjects</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            <li class="mb-2">
                <a href="{{ route('teacher-assignments.create') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('teacher-assignments.create') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-user-plus class="w-6 h-6 mr-3" />
                    <span>Assign Teachers</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('student-assignments.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('student-assignments.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-user-group class="w-6 h-6 mr-3" />
                    <span>Assign Students</span>
                </a>
            </li>
            @if(\Illuminate\Support\Facades\Auth::user()->role === \App\Enums\Role::TEACHER)
            <li class="mb-2">
                <a href="{{ route('marks.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('marks.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-pencil-square class="w-6 h-6 mr-3" />
                    <span>Mark Entry</span>
                </a>
            </li>
            @endif
            <!-- More links will be added here -->
        </ul>
    </nav>
</aside>
