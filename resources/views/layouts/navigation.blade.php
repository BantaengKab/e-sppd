<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-xl font-bold text-gray-900">E-SPPD</div>
                            <div class="text-xs text-gray-500">Kab. Bantaeng</div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 00-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- SPT Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 text-sm font-medium transition-colors duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            SPT
                            <svg class="w-4 h-4 ml-1" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute z-10 mt-3 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('spts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Daftar SPT
                                </a>
                                <a href="{{ route('spts.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Buat SPT Baru
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- SPPD -->
                    <a href="#" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        SPPD
                    </a>

                    <!-- Approvals (Supervisor, Finance, Verifikator) -->
                    @if(auth()->user()->hasRole(['supervisor', 'finance', 'verifikator', 'admin']))
                    <a href="{{ route('approvals.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Persetujuan
                        <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ auth()->user()->approvals()->where('status', 'pending')->count() }}
                        </span>
                    </a>
                    @endif

                    <!-- Users (Admin only) -->
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Pengguna
                    </a>
                    @endif
                </div>
            </div>

            <!-- Right side items -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 012-2h3.972a2 2 0 011.5.398m-.001 10.002V9a2 2 0 00-2 2H3.972a2 2 0 00-1.5.398m0 0A2.032 2.032 0 012.015 4.842A6 6 0 0121 9v3a6 6 0 01-4 5.659L15 17z" />
                        </svg>
                    </button>
                    <!-- Notification badge -->
                    @if(auth()->user()->approvals()->where('status', 'pending')->count() > 0)
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </div>

                <!-- User Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->role }}</div>
                        </div>
                        <svg class="ml-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Dashboard
            </a>

            <!-- SPT Items -->
            <a href="{{ route('spts.index') }}" class="{{ request()->routeIs('spts.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Daftar SPT
            </a>
            <a href="{{ route('spts.create') }}" class="{{ request()->routeIs('spts.create') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Buat SPT Baru
            </a>

            <!-- SPPD -->
            <a href="#" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                SPPD
            </a>

            <!-- Approvals -->
            @if(auth()->user()->hasRole(['supervisor', 'finance', 'verifikator', 'admin']))
            <a href="{{ route('approvals.index') }}" class="{{ request()->routeIs('approvals.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Persetujuan
            </a>
            @endif

            <!-- Users -->
            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Pengguna
            </a>
            @endif

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Profil
            </a>
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                <div class="text-xs text-gray-400">{{ auth()->user()->role }} - {{ auth()->user()->jabatan }}</div>
            </div>
        </div>
    </div>
</nav>
