@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-6">Edit Lapangan: {{ $field->name }}</h1>

    <form action="{{ route('admin.fields.update', $field) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT') {{-- PENTING: Untuk Update --}}

        {{-- Input Nama Lapangan --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nama Lapangan
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                   id="name" name="name" type="text" placeholder="Contoh: Lapangan A" value="{{ old('name', $field->name) }}">
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        {{-- Input Harga Per Jam --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price_per_hour">
                Harga Per Jam (Rp)
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price_per_hour') border-red-500 @enderror" 
                   id="price_per_hour" name="price_per_hour" type="number" step="1000" placeholder="Contoh: 50000" value="{{ old('price_per_hour', $field->price_per_hour) }}">
            @error('price_per_hour')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Input Deskripsi --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" 
                      id="description" name="description" rows="3" placeholder="Deskripsi singkat lapangan">{{ old('description', $field->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        {{-- Input Foto Lapangan (dengan preview foto lama) --}}
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="photo_url">
                Ganti Foto Lapangan
            </label>
            @php $fileExists = $field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url); @endphp
            @if ($fileExists)
                <p class="mb-2">Foto Saat Ini:</p>
                <img src="{{ \Illuminate\Support\Facades\Storage::url($field->photo_url) }}" alt="{{ $field->name }}" class="h-20 w-20 object-cover mb-2 border">
            @endif
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('photo_url') border-red-500 @enderror" 
                   id="photo_url" name="photo_url" type="file">
            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
            @error('photo_url')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update Lapangan
            </button>
            <a href="{{ route('admin.fields.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection