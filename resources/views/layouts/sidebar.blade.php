<aside class="w-64 bg-gray-800 text-white h-screen p-4 flex flex-col">
    <div class="mb-10 text-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="w-20 h-20 mx-auto mb-2 rounded-full">
            <h1 class="text-xl font-bold text-white">St. Joseph's VSS</h1>
        </a>
    </div>
    <nav class="flex-grow overflow-y-auto">
        @php
            $userRole = Auth::user()->role;
            $isAdmin = in_array($userRole, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER]);
        @endphp
        <ul>
            {{-- Common Dashboard Link --}}
            <li class="mb-2">
                @if($userRole === \App\Enums\Role::STUDENT)
                    <a href="{{ route('student.dashboard') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('student.dashboard') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-home class="w-6 h-6 mr-3" />
                        <span>Dashboard</span>
                    </a>
                @elseif($userRole === \App\Enums\Role::PARENT)
                     <a href="{{ route('parent.dashboard') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('parent.dashboard') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-home class="w-6 h-6 mr-3" />
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-home class="w-6 h-6 mr-3" />
                        <span>Dashboard</span>
                    </a>
                @endif
            </li>

            {{-- Admin & Staff Menu --}}
            @if(in_array($userRole, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER, \App\Enums\Role::BURSAR, \App\Enums\Role::LIBRARIAN, \App\Enums\Role::TEACHER]))

                @if($isAdmin)
                    <li class="mb-2">
                        <a href="{{ route('students.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('students.*') ? 'bg-gray-700' : '' }}">
                            <x-heroicon-o-users class="w-6 h-6 mr-3" />
                            <span>Student Management</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('class-levels.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('class-levels.*') ? 'bg-gray-700' : '' }}">
                            <x-heroicon-o-academic-cap class="w-6 h-6 mr-3" />
                            <span>Classes & Streams</span>
                        </a>
                    </li>
                @endif

                @if(in_array($userRole, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER, \App\Enums\Role::BURSAR]))
                <li class="mb-2">
                    <x-sidebar-dropdown :active="request()->routeIs(['invoices.*', 'fee-structures.*', 'expenses.*', 'reports.*'])">
                        <x-slot name="trigger"><x-heroicon-o-banknotes class="w-6 h-6 mr-3" /><span>Finance</span></x-slot>
                        <x-slot name="content">
                            <a href="{{ route('invoices.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Invoices</a>
                            <a href="{{ route('fee-structures.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Fee Structures</a>
                            <a href="{{ route('expenses.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Expenses</a>
                            <a href="{{ route('reports.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Reports</a>
                        </x-slot>
                    </x-sidebar-dropdown>
                </li>
                @endif

                @if(in_array($userRole, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER, \App\Enums\Role::LIBRARIAN]))
                <li class="mb-2">
                    <x-sidebar-dropdown :active="request()->routeIs(['books.*', 'checkouts.*', 'inventory.*', 'bookings.*'])">
                        <x-slot name="trigger"><x-heroicon-o-book-open class="w-6 h-6 mr-3" /><span>Library & Resources</span></x-slot>
                        <x-slot name="content">
                            <a href="{{ route('books.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Book Catalog</a>
                            <a href="{{ route('checkouts.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Book Checkouts</a>
                             <a href="{{ route('inventory.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">General Inventory</a>
                            <a href="{{ route('bookings.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Resource Bookings</a>
                        </x-slot>
                    </x-sidebar-dropdown>
                </li>
                @endif

                @if($isAdmin)
                <li class="mb-2">
                    <x-sidebar-dropdown :active="request()->routeIs(['dormitories.*', 'room-assignments.*', 'clubs.*'])">
                        <x-slot name="trigger"><x-heroicon-o-user-group class="w-6 h-6 mr-3" /><span>Welfare & Activities</span></x-slot>
                        <x-slot name="content">
                            <a href="{{ route('dormitories.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Manage Dormitories</a>
                            <a href="{{ route('room-assignments.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Room Assignments</a>
                            <a href="{{ route('clubs.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Manage Clubs</a>
                        </x-slot>
                    </x-sidebar-dropdown>
                </li>
                <li class="mb-2">
                    <a href="{{ route('announcements.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('announcements.*') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-megaphone class="w-6 h-6 mr-3" />
                        <span>Announcements</span>
                    </a>
                </li>
                @endif

                @if($userRole === \App\Enums\Role::TEACHER)
                <li class="mb-2">
                    <a href="{{ route('marks.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('marks.index') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-pencil-square class="w-6 h-6 mr-3" />
                        <span>Mark Entry</span>
                    </a>
                </li>
                @endif

                 @if(in_array($userRole, [\App\Enums\Role::TEACHER, \App\Enums\Role::HEADTEACHER]))
                <li class="mb-2">
                    <a href="{{ route('teacher.chat.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('teacher.chat.index') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-3" />
                        <span>Chat</span>
                    </a>
                </li>
                @endif

                @if($isAdmin)
                 <li class="mb-2">
                    <a href="{{ route('communications.create') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('communications.create') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-3" />
                        <span>Communications</span>
                    </a>
                </li>
                @endif

            {{-- Student Menu --}}
            @elseif($userRole === \App\Enums\Role::STUDENT)
                <li class="mb-2">
                    <a href="{{ route('videos.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('videos.index') ? 'bg-gray-700' : '' }}">
                        <x-heroicon-o-video-camera class="w-6 h-6 mr-3" />
                        <span>Video Library</span>
                    </a>
                </li>
            {{-- Parent Menu --}}
            @elseif($userRole === \App\Enums\Role::PARENT)
                {{-- Parents have a very simple sidebar for now --}}
            @endif
        </ul>
    </nav>
    <div class="mt-auto pt-4 border-t border-gray-700">
        <a href="{{ route('about') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('about') ? 'bg-gray-700' : '' }}">
            <x-heroicon-o-information-circle class="w-6 h-6 mr-3" />
            <span>About</span>
        </a>
    </div>
</aside>
