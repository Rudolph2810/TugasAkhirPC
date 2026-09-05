<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Project Charter Management')</title>
    @php
    $viteManifest = public_path('build/manifest.json');
    $hasViteBuild = file_exists($viteManifest);
    @endphp

    @if ($hasViteBuild)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @auth
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-10"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 text-white flex flex-col shadow-xl"
            :class="{ 'hidden': !sidebarOpen }">
            @include('components.sidebar')
        </div>

        <!-- Overlay (untuk mobile) -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 md:hidden"
            @click="sidebarOpen = false" :class="{ 'hidden': !sidebarOpen }">
        </div>
        @endauth

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'md:ml-64' : 'md:ml-16'">
            <!-- Header dengan Toggle Button -->
            @auth
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <!-- Tombol Toggle Sidebar -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800 truncate">
                            @yield('title', $title ?? 'Project Charter Management')
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @livewire('notification.notification-dropdown')
                        <!-- Profile Dropdown -->
                        <div class="relative hover:bg-gray-100" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <div
                                    class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium">
                                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 ">
                                <!-- User Info -->
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->nip }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->role_label }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->level }}</p>
                                </div>
                                <!-- Menu Items -->
                                <div class="py-1">

                                    <a href="{{ route('profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700  hover:bg-blue-300">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil
                                    </a>
                                </div>


                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>

                                        Logout
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>
            </header>
            @endauth

            <!-- Content -->
            <main class="p-6">
                @hasSection('content')
                @yield('content')
                @else
                {{ $slot }}
                @endif
            </main>

        </div>
    </div>
    @auth
    @include('components.footer')
    @endauth

    @livewireScripts
    @stack('scripts')
</body>

</html>