@extends('layouts.app') 
{{-- View ini menerima variabel $fields (Koleksi Model Field) --}}

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-500">Pilih Lapangan untuk Reservasi</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($fields as $field)
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:scale-105 duration-200">
            
            {{-- Bagian Gambar Lapangan --}}
            <div class="h-32 bg-gray-200 flex items-center justify-center relative">
                @php $fileExists = $field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url); @endphp
                @if($fileExists)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($field->photo_url) }}" alt="Foto Lapangan {{ $field->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-500">Foto Lapangan Tidak Tersedia</span>
                @endif
            </div>

            {{-- Bagian Detail --}}
            <div class="p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $field->name }}</h2>
                
                <p class="text-xl font-bold text-green-600 mb-2">
                    Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}<span class="text-sm text-gray-500 font-normal">/ jam</span>
                </p>

                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $field->description ?? 'Tidak ada deskripsi tersedia.' }}</p>

                {{-- Tombol Booking --}}
                <a href="{{ route('booking.create', $field->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition duration-150">
                    Pesan Sekarang
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 bg-white rounded-xl shadow-lg">
            <p class="text-lg text-gray-500">Saat ini tidak ada lapangan yang tersedia.</p>
            <p class="text-sm text-gray-400 mt-2">Silakan login sebagai Admin untuk menambahkan lapangan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection