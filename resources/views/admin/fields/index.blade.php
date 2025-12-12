@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-500">Manajemen Lapangan Badminton</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.fields.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Lapangan Baru
        </a>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lapangan</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Jam</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fields as $field)
                <tr>
                    <td class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $field->id }}</td>
                    <td class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $field->name }}</td>
                    <td class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        @php $fileExists = $field->photo_url && \Illuminate\Support\Facades\Storage::disk('public')->exists($field->photo_url); @endphp
                        @if ($fileExists)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($field->photo_url) }}" alt="{{ $field->name }}" class="h-10 w-10 object-cover mx-auto">
                        @else
                            <div class="text-gray-500 text-xs">Tidak Ada</div>
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <a href="{{ route('admin.fields.edit', $field) }}" class="bg-yellow-600 text-black px-3 py-2 rounded">Edit</a>
                        
                        <form action="{{ route('admin.fields.destroy', $field) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">Belum ada data lapangan yang ditambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection