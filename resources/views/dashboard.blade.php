<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard</h1>
                <p class="text-gray-500 text-sm">Selamat datang! Pilih lapangan untuk melakukan reservasi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($fields as $field)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition transform hover:scale-105 duration-200">
                    <div class="h-32 bg-gray-200 overflow-hidden relative">
                        @php $fileExists = $field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url); @endphp
                        @if ($fileExists)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($field->photo_url) }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 text-sm">Foto tidak tersedia</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1 line-clamp-1">{{ $field->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 line-clamp-2">{{ $field->description ?? 'Lapangan badminton berkualitas' }}</p>
                        <div class="flex items-center justify-between">
                            <div class="text-lg font-bold text-green-600">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                            <a href="{{ route('booking.create', $field->id) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">Pesan</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500">Belum ada lapangan yang tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
