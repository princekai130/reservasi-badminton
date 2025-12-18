<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Reservasi Badminton</title>
    
    {{-- Inisialisasi tema sebelum asset dimuat untuk mencegah flash --}}
    <script>
        (function() {
            try {
                const stored = localStorage.getItem('theme');
                if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    {{-- Ini adalah cara Laravel memuat aset frontend yang sudah dikompilasi oleh Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        {{-- Navbar --}}
        @include('layouts.navigation')

        {{-- HERO KHUSUS --}}
        @yield('hero')

        {{-- Konten --}}
        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.footer')
    </div>

    {{-- Script toggle theme --}}
    <script>
        function setThemeIcons() {
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!darkIcon || !lightIcon) return;
            const isDark = document.documentElement.classList.contains('dark');
            darkIcon.classList.toggle('hidden', !isDark);
            lightIcon.classList.toggle('hidden', isDark);
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            setThemeIcons();
        }

        document.addEventListener('DOMContentLoaded', function () {
            setThemeIcons();
        });
    </script>
</body>
</html>