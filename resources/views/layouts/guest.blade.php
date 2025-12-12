<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
        <header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <svg class="w-10 h-10 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75M4.5 10.5v9A1.5 1.5 0 006 21h12a1.5 1.5 0 001.5-1.5v-9" />
                </svg>
                <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reservasi Badminton</div>
            </a>

            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ route('fields.index') }}" class="text-sm text-gray-700 dark:text-gray-200 hover:underline">Lihat Lapangan</a>
                @guest
                    <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm">Login</a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm">Dashboard</a>
                @endguest
            </div>
        </header>

        <main class="min-h-screen flex flex-col items-center justify-center px-4">
            <section class="text-center mb-8 max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">Sewa Lapangan, Main Seru</h1>
                <p class="mt-3 text-gray-600 dark:text-gray-300">Pesan lapangan favoritmu dengan mudah — lihat jadwal, pilih jam, dan konfirmasi dalam beberapa langkah.</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <a href="{{ route('fields.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Lihat Lapangan</a>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gray-500 border border-gray-300 text-white hover:bg-gray-100 px-4 py-2 rounded-md">Daftar</a>
                    @endguest
                </div>
            </section>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/90 dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50 shadow-lg rounded-2xl">
                {{ $slot }}
            </div>

            <footer class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} Reservasi Badminton —</footer>
        </main>
    </body>
</html>
