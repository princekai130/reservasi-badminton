@extends('layouts.app') 
{{-- View ini menerima variabel $field (Model Lapangan) dan $availableHours (Array Jam) --}}

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    
    {{-- Tombol Kembali ke Daftar Lapangan --}}
    <a href="{{ route('fields.index') }}" class="text-blue-600 hover:underline mb-4 inline-block font-medium">&larr; Kembali ke Daftar Lapangan</a>
    
    <h1 class="text-3xl font-bold mb-2 text-gray-800">Formulir Reservasi</h1>
    <h2 class="text-xl text-gray-700 mb-6">Lapangan: <span class="font-semibold">{{ $field->name }}</span></h2>

    {{-- Notifikasi Error/Sukses --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 font-medium" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 font-medium" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST" class="bg-white shadow-xl rounded-lg px-8 pt-6 pb-8 mb-4">
        @csrf

        {{-- Input Tersembunyi untuk ID Lapangan --}}
        <input type="hidden" name="field_id" value="{{ $field->id }}">
        
        {{-- Detail Harga Lapangan --}}
        <div class="mb-6 border-l-4 border-green-500 p-4 rounded-md bg-gray-50">
            <p class="text-lg font-semibold text-gray-800">Harga per Jam:</p>
            <p class="text-3xl text-green-600 font-bold">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</p>
        </div>

        {{-- 1. Input Tanggal --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="booking_date">
                Pilih Tanggal Reservasi
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-200 @error('booking_date') border-red-500 @enderror" 
                   id="booking_date" 
                   name="booking_date" 
                   type="date" 
                   min="{{ date('Y-m-d') }}" 
                   value="{{ old('booking_date') ?? date('Y-m-d') }}" 
                   required>
            @error('booking_date')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            
            {{-- 2. Input Jam Mulai --}}
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_hour">
                    Jam Mulai
                </label>
                <select name="start_hour" id="start_hour" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-200 @error('start_hour') border-red-500 @enderror" required>
                    <option value="">-- Pilih Jam --</option>
                    {{-- Loop dari $availableHours yang dikirim dari Controller --}}
                    @foreach ($availableHours as $hour)
                        <option value="{{ $hour }}" {{ old('start_hour') == $hour ? 'selected' : '' }}>
                            {{ $hour }}
                        </option>
                    @endforeach
                </select>
                @error('start_hour')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 3. Input Durasi --}}
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="duration">
                    Durasi (Jam)
                </label>
                <select name="duration" id="duration" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-200 @error('duration') border-red-500 @enderror" required>
                    @for ($d = 1; $d <= 4; $d++) 
                        <option value="{{ $d }}" {{ old('duration') == $d ? 'selected' : '' }}>{{ $d }} Jam</option>
                    @endfor
                </select>
                @error('duration')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150" type="submit">
                Proses Reservasi
            </button>
            <p class="text-sm text-gray-500">Total biaya final akan ditampilkan di riwayat.</p>
        </div>
    </form>
    
</div>
@endsection