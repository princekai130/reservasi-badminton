<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('fields.index') }}" class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 9.75L12 3l9 6.75M4.5 10.5v9A1.5 1.5 0 006 21h12a1.5 1.5 0 001.5-1.5v-9" />
                        </svg>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('fields.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                        Daftar Lapangan
                    </a>

                    @auth
                        <a href="{{ route('user.history') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                            Riwayat Reservasi
                        </a>

                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                                Manajemen Reservasi
                            </a>
                            <a href="{{ route('admin.fields.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                                Manajemen Lapangan
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium">
                                Laporan
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Theme Toggle Button -->
                <button onclick="toggleTheme()" aria-label="Toggle theme" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 3.5a.75.75 0 01.75-.75h0A.75.75 0 0111.5 3.5V5a.75.75 0 01-1.5 0V3.5zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM4.22 5.28a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 11-1.06 1.06L4.22 5.28zM13.66 14.72a.75.75 0 011.06-1.06l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06zM3.5 10a.75.75 0 01.75-.75H5a.75.75 0 010 1.5H4.25A.75.75 0 013.5 10zM15 10a.75.75 0 01.75-.75H16.5a.75.75 0 010 1.5h-.75A.75.75 0 0115 10zM4.22 14.72a.75.75 0 011.06 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06zM13.66 5.28a.75.75 0 011.06 1.06L13.66 7.4a.75.75 0 11-1.06-1.06l1.06-1.06zM10 6.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z"/>
                    </svg>
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 text-gray-700 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 116.707 2.707a6 6 0 0010.586 10.586z"/>
                    </svg>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Register
                    </a>
                @endguest
                
                @auth
                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.edit') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-sm font-medium">
                            {{ Auth::user()->name }} @if(Auth::user()?->is_admin) <span class="text-xs text-green-600">(Admin)</span> @endif
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('fields.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                Daftar Lapangan
            </a>

            @auth
                <a href="{{ route('user.history') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                    Riwayat Reservasi
                </a>

                @if (Auth::user()->is_admin)
                    <a href="{{ route('admin.bookings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                        Manajemen Reservasi
                    </a>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            @auth
                <div class="px-4 py-2">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }} @if(Auth::user()?->is_admin) <span class="text-xs text-green-600">(Admin)</span> @endif</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-green-600 text-white hover:bg-green-700">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-blue-600 text-white hover:bg-blue-700">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    function setThemeIcons() {
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        if (!darkIcon || !lightIcon) return;
        
        const isDark = document.documentElement.classList.contains('dark');
        darkIcon.classList.toggle('hidden', isDark);
        lightIcon.classList.toggle('hidden', !isDark);
    }

    function toggleTheme() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        setThemeIcons();
    }

    document.addEventListener('DOMContentLoaded', function() {
        setThemeIcons();
    });
</script>

<style>
    /* Add any additional custom styles here */
</style>
