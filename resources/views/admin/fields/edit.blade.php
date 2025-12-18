@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4 max-w-4xl">
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex text-gray-600 dark:text-gray-300">
            <li><a href="{{ route('admin.fields.index') }}" class="hover:underline text-emerald-600 dark:text-emerald-400">Lapangan</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500 dark:text-gray-400">Edit: {{ $field->name }}</li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
            {{-- IMAGE PREVIEW / DETAILS --}}
            <aside class="flex flex-col items-center gap-4">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview Foto</label>
                    @php
                        $exists = $field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url);
                        $photo = $exists ? \Illuminate\Support\Facades\Storage::url($field->photo_url) : asset('images/placeholder-field.png');
                    @endphp
                    <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-700">
                        <img id="current-photo" src="{{ $photo }}" alt="Foto {{ $field->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                        @if($exists)
                            Saat ini: <span class="font-medium text-gray-700 dark:text-gray-200">{{ basename($field->photo_url) }}</span>
                        @else
                            Belum ada foto lapangan.
                        @endif
                    </div>
                    <label class="mt-3 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <input id="remove-photo" type="checkbox" name="remove_photo" class="rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                        Hapus foto saat ini setelah Update
                    </label>
                </div>

                <div class="w-full text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Format: JPG/PNG. Maks 2MB. Preview akan tampil setelah memilih file.
                    </p>
                </div>
            </aside>

            {{-- FORM --}}
            <div class="md:col-span-2">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Edit Lapangan — {{ $field->name }}</h1>

                <form action="{{ route('admin.fields.update', $field) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lapangan</label>
                        <input id="name" name="name" value="{{ old('name', $field->name) }}" required
                               class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Per Jam (Rp)</label>
                            <input id="price_per_hour" name="price_per_hour" type="number" step="1000" value="{{ old('price_per_hour', $field->price_per_hour) }}" required
                                   class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('price_per_hour') border-red-500 @enderror">
                            @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="photo_url_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ganti Foto (opsional)</label>
                            <input id="photo_url_input" name="photo_url" type="file" accept="image/*"
                                   class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('photo_url') border-red-500 @enderror">
                            <div id="file-meta" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></div>
                            @error('photo_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('description') border-red-500 @enderror">{{ old('description', $field->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-semibold shadow">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.fields.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const input = document.getElementById('photo_url_input');
    const preview = document.getElementById('current-photo');
    const meta = document.getElementById('file-meta');
    const removeCheckbox = document.getElementById('remove-photo');

    if (!input || !preview) return;

    input.addEventListener('change', function(e){
        const file = e.target.files[0];
        removeCheckbox.checked = false;
        if (!file) {
            meta.textContent = '';
            return;
        }
        preview.src = URL.createObjectURL(file);
        meta.textContent = `${file.name} — ${(file.size/1024).toFixed(0)} KB`;
    });
})();
</script>
@endsection