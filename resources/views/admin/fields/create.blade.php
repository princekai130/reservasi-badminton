@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Tambah Lapangan Baru</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Isi data lapangan dan unggah foto untuk menampilkan listing yang menarik.</p>
            </div>
            <a href="{{ route('admin.fields.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Kembali</a>
        </div>

        <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lapangan</label>
                <input id="name" name="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price_per_hour" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga per Jam (Rp)</label>
                <input id="price_per_hour" name="price_per_hour" type="number" step="1000" min="0" value="{{ old('price_per_hour') }}" required
                       class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400 @error('price_per_hour') border-red-500 @enderror">
                @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Lapangan (opsional)</label>

                <div id="photo-drop" class="relative w-full rounded-md border-2 border-dashed border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 flex items-center gap-4">
                    <div class="w-36 h-24 bg-white dark:bg-gray-800 rounded overflow-hidden flex items-center justify-center border border-gray-100 dark:border-gray-700">
                        <img id="photo-preview" src="{{ asset('images/placeholder-field.png') }}" alt="Preview" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1">
                        <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">Tarik & lepas gambar di sini atau pilih dari perangkat Anda.</div>
                        <div class="flex items-center gap-3">
                            <label for="photo_url" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white dark:bg-gray-800 border hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-sm">
                                Pilih Foto
                            </label>
                            <input id="photo_url" name="photo_url" type="file" accept="image/*" class="sr-only">
                            <button id="clear-photo" type="button" class="text-sm text-gray-500 dark:text-gray-300 hover:underline hidden">Hapus</button>
                        </div>
                        <div id="photo-meta" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                        @error('photo_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">Format: JPG/PNG, maksimal 2MB.</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.fields.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</a>
                <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-semibold shadow" type="submit">Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const input = document.getElementById('photo_url');
    const preview = document.getElementById('photo-preview');
    const meta = document.getElementById('photo-meta');
    const clearBtn = document.getElementById('clear-photo');
    const drop = document.getElementById('photo-drop');

    function showFile(file) {
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        meta.textContent = `${file.name} — ${(file.size / 1024).toFixed(0)} KB`;
        clearBtn.classList.remove('hidden');
    }

    input.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) {
            preview.src = "{{ asset('images/placeholder-field.png') }}";
            meta.textContent = '';
            clearBtn.classList.add('hidden');
            return;
        }
        showFile(file);
    });

    clearBtn.addEventListener('click', () => {
        input.value = '';
        preview.src = "{{ asset('images/placeholder-field.png') }}";
        meta.textContent = '';
        clearBtn.classList.add('hidden');
    });

    // Drag & drop basic handlers
    ;['dragenter','dragover'].forEach(ev => {
        drop.addEventListener(ev, (e) => {
            e.preventDefault();
            drop.classList.add('ring-2','ring-emerald-400');
        });
    });
    ;['dragleave','drop'].forEach(ev => {
        drop.addEventListener(ev, (e) => {
            e.preventDefault();
            drop.classList.remove('ring-2','ring-emerald-400');
        });
    });
    drop.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files && e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            input.files = e.dataTransfer.files;
            showFile(file);
        }
    });
})();
</script>
@endsection