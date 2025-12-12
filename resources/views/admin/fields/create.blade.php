
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-3xl font-bold mb-6 text-white">Tambah Lapangan Baru</h1>

    <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama</label>
            <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Harga per Jam (Rp)</label>
            <input name="price_per_hour" type="number" value="{{ old('price_per_hour') }}" class="w-full border rounded px-3 py-2" required>
            @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Deskripsi</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Foto (opsional)</label>
            <input name="photo_url" type="file" class="w-full">
            @error('photo_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.fields.index') }}" class="text-sm text-gray-600">Batal</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection