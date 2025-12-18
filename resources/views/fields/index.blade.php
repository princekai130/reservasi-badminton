@extends('layouts.app') 
{{-- View ini menerima variabel $fields (Koleksi Model Field) --}}

@section('content')
@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
/**
 * Kembalikan URL gambar yang usable atau null jika tidak ada.
 */
function fieldImageUrl($path) {
    if (!$path) return null;
    if (Str::startsWith($path, ['http://','https://'])) return $path;
    if (Str::startsWith($path, '/storage/')) return $path;
    if (Storage::disk('public')->exists($path)) return Storage::url($path);
    $trimmed = preg_replace('#^public/#','',$path);
    if (Storage::disk('public')->exists($trimmed)) return Storage::url($trimmed);
    return null;
}
@endphp

<div class="container mx-auto p-4" x-data="{ view:'grid', q:'' }">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100">Pilih Lapangan untuk Reservasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cepat, aman, dan mudah dipesan. Pilih lapangan yang sesuai dan pesan sekarang.</p>
        </div>

        <div class="flex gap-3 items-center w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <label for="search" class="sr-only">Cari</label>
                <input id="search" x-model="q" type="search" placeholder="Cari nama lapangan..." class="block w-full sm:w-64 rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400">
                <button type="button" x-show="q" @click="q=''" class="absolute right-1 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 px-2">×</button>
            </div>

            <div class="inline-flex rounded-md shadow-sm" role="group" aria-label="View toggle">
                <button type="button" @click="view='grid'" :class="view==='grid' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'" class="px-3 py-2 rounded-l-md border border-r-0 border-gray-200 dark:border-gray-700">
                    <!-- Grid icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/></svg>
                </button>
                <button type="button" @click="view='table'" :class="view==='table' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'" class="px-3 py-2 rounded-r-md border border-gray-200 dark:border-gray-700">
                    <!-- Table icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- GRID VIEW --}}
    <div x-show="view==='grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-cloak>
        @forelse ($fields as $field)
        @php
            $photo = fieldImageUrl($field->photo_url);
            $nameLower = strtolower($field->name ?? '');
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition transform hover:scale-105 duration-200" 
             :class="q && !('{{ $nameLower }}'.includes(q.toLowerCase())) ? 'hidden' : ''" 
             data-name="{{ $field->name }}">
            <div class="h-40 bg-gray-100 dark:bg-gray-700 flex items-center justify-center relative">
                @if($photo)
                    <img src="{{ $photo }}" alt="Foto Lapangan {{ $field->name }}" class="w-full h-full object-cover">
                @else
                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 px-4">
                        <svg class="w-12 h-12 mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h18v18H3V3zM7 7h10v10H7V7z"/></svg>
                        <span class="text-sm">Foto Lapangan Tidak Tersedia</span>
                    </div>
                @endif
            </div>

            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $field->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 line-clamp-3">{{ $field->description ?? 'Tidak ada deskripsi tersedia.' }}</p>

                <div class="flex items-center justify-between gap-3 mt-3">
                    <div>
                        <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">/ jam</div>
                    </div>
                    <a href="{{ route('booking.create', $field->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <p class="text-lg text-gray-500 dark:text-gray-300">Saat ini tidak ada lapangan yang tersedia.</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Silakan login sebagai Admin untuk menambahkan lapangan.</p>
        </div>
        @endforelse
    </div>

    {{-- TABLE VIEW --}}
    <div x-show="view==='table'" class="mt-2" x-cloak>
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lapangan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Deskripsi</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($fields as $field)
                    @php
                        $photo = fieldImageUrl($field->photo_url);
                        $nameLower = strtolower($field->name ?? '');
                    @endphp
                    <tr :class="q && !('{{ $nameLower }}'.includes(q.toLowerCase())) ? 'hidden' : ''" data-name="{{ $field->name }}">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden flex items-center justify-center">
                                    @if($photo)
                                        <img src="{{ $photo }}" alt="Foto {{ $field->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h18v18H3V3z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $field->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $field->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <div class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $field->description ?? 'Tidak ada deskripsi.' }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('booking.create', $field->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold">Pesan</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Saat ini tidak ada lapangan yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection