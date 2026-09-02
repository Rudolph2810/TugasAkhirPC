@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Info -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="h-16 w-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-gray-500">NIP: {{ auth()->user()->nip }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500">Email</label>
                                <p class="font-medium">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Role</label>
                                <p class="font-medium">{{ auth()->user()->role_label }}</p>
                            </div>
                            @if(auth()->user()->level)
                            <div>
                                <label class="text-sm text-gray-500">Level</label>
                                <p class="font-medium">{{ auth()->user()->level_label }}</p>
                            </div>
                            @endif
                            @if(auth()->user()->department)
                            <div>
                                <label class="text-sm text-gray-500">Departemen</label>
                                <p class="font-medium">{{ auth()->user()->department->name }}</p>
                            </div>
                            @endif
                            @if(auth()->user()->division)
                            <div>
                                <label class="text-sm text-gray-500">Divisi</label>
                                <p class="font-medium">{{ auth()->user()->division->name }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="text-sm text-gray-500">Status</label>
                                <p class="font-medium">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ auth()->user()->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Ubah Password</h3>
                        <form method="POST" action="{{ route('profile.change-password') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Password Lama</label>
                                    <input type="password" name="current_password"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                    <input type="password" name="password"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password
                                        Baru</label>
                                    <input type="password" name="password_confirmation"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection