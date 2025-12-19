@extends('layouts.app') 
{{-- View ini menerima variabel $field (Model Lapangan) dan $availableHours (Array Jam) --}}

@section('content')
@php
use App\Models\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function fieldImageUrl($path) {
    if (!$path) return asset('images/placeholder-field.png');
    if (Str::startsWith($path, ['http://','https://'])) return $path;
    if (Str::startsWith($path, '/storage/')) return $path;
    if (Storage::disk('public')->exists($path)) return Storage::url($path);
    $trimmed = preg_replace('#^public/#','',$path);
    if (Storage::disk('public')->exists($trimmed)) return Storage::url($trimmed);
    return asset('images/placeholder-field.png');
}

$otherFields = Field::where('id','!=',$field->id)->latest()->take(4)->get();
@endphp

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- FORM AREA --}}
        <div class="lg:col-span-2">
            <a href="{{ route('fields.index') }}" class="text-emerald-600 hover:underline mb-4 inline-block font-medium">&larr; Kembali ke Daftar Lapangan</a>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-6 py-6">
                <header class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Formulir Reservasi</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lapangan: <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $field->name }}</span></p>
                </header>

                {{-- Notifikasi Error/Sukses --}}
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative mb-4 font-medium" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative mb-4 font-medium" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Hidden field id --}}
                    <input type="hidden" name="field_id" value="{{ $field->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="booking_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Tanggal</label>
                            <input id="booking_date" name="booking_date" type="date" min="{{ date('Y-m-d') }}" value="{{ old('booking_date') ?? date('Y-m-d') }}" required
                                   class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('booking_date') border-red-500 @enderror">
                            @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="start_hour" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Mulai</label>
                            <select name="start_hour" id="start_hour" required class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('start_hour') border-red-500 @enderror">
                                <option value="">-- Pilih Jam --</option>
                                @foreach ($availableHours as $hour)
                                    <option value="{{ $hour }}" {{ old('start_hour') == $hour ? 'selected' : '' }}>{{ $hour }}</option>
                                @endforeach
                            </select>
                            @error('start_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durasi (Jam)</label>
                            <select name="duration" id="duration" required class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('duration') border-red-500 @enderror">
                                @for ($d = 1; $d <= 4; $d++)
                                    <option value="{{ $d }}" {{ old('duration') == $d ? 'selected' : '' }}>{{ $d }} Jam</option>
                                @endfor
                            </select>
                            @error('duration') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga per Jam</label>
                            <div class="mt-1 text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-semibold shadow">Proses Reservasi</button>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total estimasi: <span id="total-estimate" class="font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($field->price_per_hour,0,',','.') }}</span></div>
                    </div>
                </form>
            </div>

            {{-- LAPANGAN LAINNYA --}}
            <section class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Lapangan Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($otherFields as $of)
                        @php $photo = fieldImageUrl($of->photo_url); @endphp
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="h-28 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                <img src="{{ $photo }}" alt="Foto {{ $of->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $of->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $of->description ?? 'Tidak ada deskripsi.' }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- SIDEBAR / PREVIEW --}}
        <aside class="hidden lg:block">
            <div class="sticky top-24">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    @php $photo = fieldImageUrl($field->photo_url); @endphp
                    <div class="h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                        <img src="{{ $photo }}" alt="Foto {{ $field->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $field->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-3">{{ $field->description ?? 'Tidak ada deskripsi.' }}</p>

                        <div class="mt-4 border-t pt-3 text-sm text-gray-700 dark:text-gray-300">
                            <div class="font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($field->price_per_hour,0,',','.') }} / jam</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lokasi: {{ $field->location ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
(function(){
    const price = Number(document.getElementById('booking-data').dataset.price);
    const durationEl = document.getElementById('duration');
    const totalEl = document.getElementById('total-estimate');

    function updateTotal(){
        const duration = parseInt(durationEl?.value || 1, 10);
        const total = price * duration;
        totalEl.textContent = new Intl.NumberFormat('id-ID').format(total);
    }

    if(durationEl){
        durationEl.addEventListener('change', updateTotal);
        updateTotal();
    }
})();
</script>


@endsection