@extends('layouts.app')

@section('hero')
<section class="relative overflow-hidden court-bg">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 via-green-500 to-sky-600 opacity-90 dark:opacity-80"></div>

    <div class="relative max-w-7xl mx-auto px-4 py-16 sm:py-20 lg:py-28">
        <div class="flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1 text-white">
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">
                    Reservasi Badminton
                </h1>
                <p class="mt-4 text-lg opacity-95">
                    Pesan lapangan dan jadwal latihan.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
use App\Models\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/* ambil fields jika belum dikirim dari controller */
$fields = $fields ?? Field::query()->latest()->take(6)->get();

if (! function_exists('fieldImageUrl')) {
    function fieldImageUrl($path) {
        if (!$path) return asset('images/placeholder-field.png');
        if (Str::startsWith($path, ['http://','https://'])) return $path;
        if (Str::startsWith($path, '/storage/')) return $path;
        if (Storage::disk('public')->exists($path)) return Storage::url($path);
        $trimmed = preg_replace('#^public/#','',$path);
        if (Storage::disk('public')->exists($trimmed)) return Storage::url($trimmed);
        return asset('images/placeholder-field.png');
    }
}
@endphp

<div class="container mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Lapangan Pilihan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Beberapa lapangan populer & terbaru — lihat detail lapangan untuk informasi lebih lengkap.</p>
        </div>

        <a href="{{ route('fields.index') }}" class="text-sm text-emerald-600 hover:underline dark:text-emerald-400">Lihat Semua Lapangan →</a>
    </div>

    @if($fields->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center text-gray-500 dark:text-gray-300">
            Tidak ada lapangan untuk ditampilkan saat ini.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($fields as $field)
                @php $photo = fieldImageUrl($field->photo_url); @endphp
                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-44 bg-gray-100 dark:bg-gray-700">
                        <img src="{{ $photo }}" alt="Foto {{ $field->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div class="absolute left-4 bottom-3 right-4 text-white">
                            <h3 class="text-lg font-semibold leading-tight">{{ $field->name }}</h3>
                            <p class="mt-1 text-xs opacity-90 line-clamp-2">{{ $field->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

