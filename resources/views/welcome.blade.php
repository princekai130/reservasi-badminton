<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi Badminton — Sistem Reservasi</title>
    <meta name="description" content="Pesan lapangan, atur jadwal, dan kelola klub badminton Anda dengan mudah.">

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

    {{-- Font bagus untuk tampilan modern --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Sedikit gaya tambahan untuk dekorasi tema --}}
    <style>
        :root { --accent: #10b981; /* emerald-500 */ }
        body { font-family: 'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .shuttle-flight { animation: shuttle 3s ease-in-out infinite; transform-origin: center; }
        @keyframes shuttle {
            0% { transform: translateY(0) translateX(0) rotate(0deg); }
            50% { transform: translateY(-10px) translateX(6px) rotate(8deg); }
            100% { transform: translateY(0) translateX(0) rotate(0deg); }
        }
        /* court lines pattern */
        .court-bg { background: linear-gradient(135deg, rgba(255,255,255,0.02) 25%, transparent 25%), linear-gradient(225deg, rgba(255,255,255,0.02) 25%, transparent 25%); background-size: 40px 40px; }
    </style>

    {{-- Ini adalah cara Laravel memuat aset frontend yang sudah dikompilasi oleh Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900">
    <div class="min-h-screen">
        {{-- Memanggil komponen navigasi --}}
        @include('layouts.navigation')

        {{-- Hero: Tema "Reservasi Badminton" --}}
        <section class="relative overflow-hidden court-bg">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-green-500 to-sky-600 opacity-90 dark:opacity-80"></div>
            <svg class="absolute -top-8 right-8 w-72 h-72 opacity-10 dark:opacity-20" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs><linearGradient id="g" x1="0" x2="1"><stop stop-color="#fff" stop-opacity="0.6"/><stop offset="1" stop-color="#fff" stop-opacity="0.2"/></linearGradient></defs>
                <circle cx="60" cy="60" r="80" fill="url(#g)"/>
            </svg>

            <div class="relative max-w-7xl mx-auto px-4 py-16 sm:py-20 lg:py-28">
                <div class="flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 text-white">
                        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Reservasi Badminton</h1>
                        <p class="mt-4 text-lg opacity-95">Pesan lapangan dan atur jadwal lapangan — semuanya dalam satu platform yang cepat dan aman.</p>
                    </div>

                    <div class="w-72 h-72 flex items-center justify-center bg-white/10 rounded-2xl p-6">
                        <!-- Shuttlecock / racket dekoratif -->
                        <svg class="w-40 h-40 shuttle-flight" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <g fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M44 20c5 5 8 10 8 10s-6 3-11-2-11-9-11-9 6-3 14 1z" fill="white" opacity=".95"/>
                                <path d="M38 26c-6 6-12 12-19 18l-4-4c6-7 12-12 19-18" stroke="white" opacity=".9"/>
                                <path d="M46 28c0 5-6 11-11 16" opacity=".9"/>
                            </g>
                        </svg>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4 text-white">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                        <div>
                            <div class="font-semibold">Cepat & Real-time</div>
                            <div class="text-sm opacity-90">Konfirmasi instan dan manajemen jadwal otomatis.</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                        <div>
                            <div class="font-semibold">Multi Lapangan</div>
                            <div class="text-sm opacity-90">Dukungan banyak lapangan dan jenis permukaan.</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 4-6 4-6 0s6-4 6 0zM18 11c0 4-6 4-6 0s6-4 6 0z"/></svg>
                        <div>
                            <div class="font-semibold">Aman & Terpercaya</div>
                            <div class="text-sm opacity-90">Pembayaran dan data Anda terlindungi.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Slot untuk konten halaman --}}
        <main class="max-w-7xl mx-auto px-4 py-10">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-700 dark:text-gray-300">© {{ date('Y') }} Reservasi Badminton — Semua hak dilindungi.</div>
                <div class="flex gap-4 text-sm">
                    <a href="{{ url('/about') }}" class="text-gray-700 dark:text-gray-300 hover:underline">Tentang</a>
                    <a href="{{ url('/help') }}" class="text-gray-700 dark:text-gray-300 hover:underline">Bantuan</a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 dark:text-gray-300 hover:underline">Kontak</a>
                </div>
            </div>
        </footer>
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