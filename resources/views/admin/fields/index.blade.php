@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4" x-data="{ q: '', view: 'table' }">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Manajemen Lapangan Badminton</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambah, edit, atau hapus lapangan. Tampilan ini responsif dan mendukung light/dark mode.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:flex-none">
                <input type="search" x-model="q" placeholder="Cari nama lapangan..." class="w-full md:w-72 rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400">
                <button type="button" x-show="q" @click="q=''" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">×</button>
            </div>

            <div class="inline-flex rounded-md shadow-sm border dark:border-gray-700 bg-white dark:bg-gray-800" role="group" aria-label="view toggle">
                <button type="button" @click="view='table'" :class="view==='table' ? 'bg-emerald-600 text-white' : 'text-gray-700 dark:text-gray-200'" class="px-3 py-2 text-sm rounded-l-md">Tabel</button>
                <button type="button" @click="view='grid'" :class="view==='grid' ? 'bg-emerald-600 text-white' : 'text-gray-700 dark:text-gray-200'" class="px-3 py-2 text-sm rounded-r-md">Grid</button>
            </div>

            <a href="{{ route('admin.fields.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow text-sm">
                + Tambah Lapangan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4">
            <div class="rounded-md px-4 py-3 border bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- GRID VIEW (Mobile/Optional) --}}
    <div x-show="view==='grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-cloak>
        @forelse ($fields as $field)
            @php
                $photo = ($field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url))
                    ? \Illuminate\Support\Facades\Storage::url($field->photo_url)
                    : asset('images/placeholder-field.png');
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition hover:shadow-lg" x-show="!q || '{{ strtolower($field->name) }}'.includes(q.toLowerCase())">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-16 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden flex items-center justify-center">
                        <img src="{{ $photo }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $field->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $field->description ?? 'Tidak ada deskripsi.' }}</div>
                        <div class="mt-2 text-emerald-600 dark:text-emerald-400 font-bold">Rp {{ number_format($field->price_per_hour,0,',','.') }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">/ jam</span></div>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.fields.edit', $field) }}" class="px-3 py-1 rounded text-sm bg-yellow-500 hover:bg-yellow-600 text-black">Edit</a>

                    <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" class="inline" onsubmit="return confirm('Hapus lapangan ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 rounded text-sm bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center text-gray-500 dark:text-gray-300">
                Belum ada data lapangan.
            </div>
        @endforelse
    </div>

    {{-- TABLE VIEW (Desktop) --}}
    <div x-show="view==='table'" class="hidden md:block mt-4" x-cloak>
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama Lapangan</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deskripsi</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga / Jam</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Foto</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($fields as $field)
                        @php
                            $photo = ($field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url))
                                ? \Illuminate\Support\Facades\Storage::url($field->photo_url)
                                : asset('images/placeholder-field.png');
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition" x-show="!q || '{{ strtolower($field->name) }}'.includes(q.toLowerCase())">
                            <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-200">{{ $field->id }}</td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $field->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $field->id }}</div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $field->description ?? '-' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($field->price_per_hour,0,',','.') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="w-20 h-12 mx-auto rounded overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <img src="{{ $photo }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.fields.edit', $field) }}" class="px-3 py-1 text-sm rounded bg-yellow-500 hover:bg-yellow-600 text-black">Edit</a>
                                    <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" class="inline" onsubmit="return confirm('Hapus lapangan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm rounded bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-300">Belum ada data lapangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($fields, 'links'))
            <div class="mt-4">
                {{ $fields->links() }}
            </div>
        @endif
    </div>
</div>
@endsection