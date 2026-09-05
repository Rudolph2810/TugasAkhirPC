<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Charter Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(!file_exists(public_path('build/manifest.json')))
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @endif
</head>

<body>
    <div class="min-h-screen flex">
        <!-- Left side - Form -->
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-md w-full space-y-8">
                <div>
                    <div class="flex justify-center">
                        <img src="{{ asset('storage/logo/logo.jpg') }}" alt="Logo" class="h-900 w-auto"
                            onerror="this.src='https://placehold.co/200x80?text=Logo'">
                    </div>
                    <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
                        Project Charter Management System
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Silakan login dengan NIP Anda
                    </p>
                </div>

                <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="rounded-md shadow-sm -space-y-px">
                        <div>
                            <label for="nip" class="sr-only">NIP</label>
                            <input id="nip" name="nip" type="text" required
                                class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="NIP" value="{{ old('nip') }}">
                            @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input id="password" name="password" type="password" required
                                class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Password">
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Login
                        </button>
                    </div>

                    <div class="text-sm text-center">
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Belum punya akun? Daftar disini
                        </a>
                    </div>
                    @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Right side - Image -->
        <div
            class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-blue-500 to-indigo-600 items-center justify-center p-12">
            <div class="text-white text-center">
                <svg class="w-32 h-32 mx-auto mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-5xl font-bold">Selamat Datang -</h2>
                <p class="mt-4 text-lg"> Project Charter Online</p>
                <p class="mt-2 text-blue-100"></p>
            </div>
        </div>
    </div>
</body>

</html>