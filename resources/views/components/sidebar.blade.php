<!-- Sidebar -->
<aside class="w-64 bg-black text-white flex flex-col min-h-screen fixed left-0 top-0 z-40">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-700 flex items-center justify-center">

        <img src="{{ asset('storage/logo/logo.jpg') }}" alt="Logo" class="h-50 w-30">

    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-700">
        <p class="text-sm text-gray-300">{{ greeting() }}</p>
        <p class="text-md font-semibold text-white truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-gray-400">{{ auth()->user()->nip }}</p>
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-blue-700 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
            <img src="{{asset('storage/logo/dashboard.png')}}" alt="" class="w-5 h-5 mr-3 rounded-full">
            Dashboard
        </a>
        <a href="{{ route('profile') }}"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-blue-700 rounded-lg transition {{ request()->routeIs('profile') ? 'bg-gray-700 text-white' : '' }}">
            <img src="{{asset('storage/logo/profile.jpg')}}" alt="" class="w-5 h-5 mr-3 rounded-full">
            Profil
        </a>



        <!-- Tambahkan menu lain sesuai role -->
        @if((auth()->user()->role === 'comercil' && auth()->user()->level === 'staff') || auth()->user()->role ===
        'admin')
        <a href="{{ route('project.initiate') }}"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-blue-700 rounded-lg transition {{ request()->routeIs('project.initiate') ? 'bg-gray-700 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Inisiasi Proyek
        </a>
        @endif

        <!-- @if((auth()->user()->role === 'comercil' && auth()->user()->level === 'staff') || auth()->user()->role ===
        'admin')
        <a href="{{ route('dashboard') }}?status=menunggu_pengisian_pelaksana"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            Isi Data Proyek
        </a>
        @endif -->

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.users') }}"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-blue-700 rounded-lg transition {{ request()->routeIs('admin.*') ? 'bg-gray-700 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Manajemen Pengguna
        </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-900 hover:text-white rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>