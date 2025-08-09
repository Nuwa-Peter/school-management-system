<aside class="w-64 bg-gray-800 text-white h-screen p-4 overflow-y-auto">
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
                        <a href="{{ route('teacher-assignments.create') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Assign Subjects to Teachers</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            <li class="mb-2">
                <a href="{{ route('student-assignments.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('student-assignments.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-user-group class="w-6 h-6 mr-3" />
                    <span>Assign Students</span>
                </a>
            </li>
            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER, \App\Enums\Role::BURSAR]))
            <li class="mb-2">
                <x-sidebar-dropdown :active="request()->routeIs('invoices.*') || request()->routeIs('fee-structures.*') || request()->routeIs('expenses.*') || request()->routeIs('reports.*')">
                    <x-slot name="trigger">
                        <x-heroicon-o-banknotes class="w-6 h-6 mr-3" />
                        <span>Finance</span>
                    </x-slot>
                    <x-slot name="content">
                        <a href="{{ route('invoices.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Invoices</a>
                        <a href="{{ route('fee-structures.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Fee Structures</a>
                        <a href="{{ route('expenses.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Expenses</a>
                        <a href="{{ route('reports.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Reports</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            @endif
            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER]))
            <li class="mb-2">
                <x-sidebar-dropdown :active="request()->routeIs('dormitories.*') || request()->routeIs('room-assignments.*') || request()->routeIs('clubs.*')">
                    <x-slot name="trigger">
                        <x-heroicon-o-user-group class="w-6 h-6 mr-3" />
                        <span>Welfare & Activities</span>
                    </x-slot>
                    <x-slot name="content">
                        <a href="{{ route('dormitories.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Manage Dormitories</a>
                        <a href="{{ route('room-assignments.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Room Assignments</a>
                        <a href="{{ route('clubs.index') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Manage Clubs</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            @endif
            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role, [\App\Enums\Role::ROOT, \App\Enums\Role::HEADTEACHER]))
            <li class="mb-2">
                <x-sidebar-dropdown :active="request()->routeIs('documents.*')">
                    <x-slot name="trigger">
                        <x-heroicon-o-document-duplicate class="w-6 h-6 mr-3" />
                        <span>Documents</span>
                    </x-slot>
                    <x-slot name="content">
                        <a href="{{ route('documents.id-card.select') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Generate ID Card</a>
                        <a href="{{ route('documents.report-card.select') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Generate Report Card</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            @endif
            @if(\Illuminate\Support\Facades\Auth::user()->role === \App\Enums\Role::TEACHER)
            <li class="mb-2">
                <a href="{{ route('marks.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('marks.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-pencil-square class="w-6 h-6 mr-3" />
                    <span>Mark Entry</span>
                </a>
            </li>
            @endif
            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role, [\App\Enums\Role::TEACHER, \App\Enums\Role::HEADTEACHER]))
            <li class="mb-2">
                <a href="{{ route('teacher.chat.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('teacher.chat.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-3" />
                    <span>Chat</span>
                </a>
            </li>
            @endif
            <li class="mb-2">
                <a href="{{ route('videos.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('videos.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-video-camera class="w-6 h-6 mr-3" />
                    <span>Video Library</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('communications.create') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('communications.create') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-3" />
                    <span>Communications</span>
                </a>
            </li>
            <li class="mb-2">
                <x-sidebar-dropdown :active="request()->routeIs('attendance.*')">
                    <x-slot name="trigger">
                        <x-heroicon-o-qr-code class="w-6 h-6 mr-3" />
                        <span>Attendance</span>
                    </x-slot>
                    <x-slot name="content">
                        <a href="{{ route('attendance.qrcode') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">Show QR Code</a>
                        <a href="{{ route('attendance.records') }}" class="block p-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md">View Records</a>
                    </x-slot>
                </x-sidebar-dropdown>
            </li>
            <!-- More links will be added here -->
            @if(\Illuminate\Support\Facades\Auth::user()->role === \App\Enums\Role::ROOT)
            <li class="mb-2">
                <a href="{{ route('admin.chat.index') }}" class="flex items-center p-2 text-gray-300 hover:bg-gray-700 rounded-md {{ request()->routeIs('admin.chat.index') ? 'bg-gray-700' : '' }}">
                    <x-heroicon-o-shield-check class="w-6 h-6 mr-3" />
                    <span>Manage Chats</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</aside>
