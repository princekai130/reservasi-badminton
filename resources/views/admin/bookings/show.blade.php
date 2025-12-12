@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-3xl">
    <h1 class="text-2xl font-bold mb-4">Detail Reservasi #{{ $booking->id }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">User</p>
                <p class="font-medium">{{ $booking->user->name ?? '—' }}</p>
                <p class="text-xs text-gray-500">{{ $booking->user->email ?? '' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Lapangan</p>
                <p class="font-medium">{{ $booking->field->name ?? '—' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Mulai</p>
                <p class="font-medium">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y H:i') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Selesai</p>
                <p class="font-medium">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y H:i') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Durasi (jam)</p>
                <p class="font-medium">{{ $booking->total_hours ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Total Harga</p>
                <p class="font-medium">Rp {{ number_format($booking->total_price,0,',','.') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-medium">{{ ucfirst($booking->status) }}</p>
            </div>
        </div>
    </div>

    {{-- Form update --}}
    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="bg-white shadow rounded-lg p-4 mb-4">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600">Start Time</label>
                <input type="datetime-local" name="start_time" value="{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i') }}" class="w-full border rounded px-3 py-2">
                @error('start_time') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600">End Time</label>
                <input type="datetime-local" name="end_time" value="{{ \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i') }}" class="w-full border rounded px-3 py-2">
                @error('end_time') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600">Total Hours</label>
                <input type="number" name="total_hours" min="1" value="{{ $booking->total_hours }}" class="w-full border rounded px-3 py-2">
                @error('total_hours') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600">Total Price (Rp)</label>
                <input type="number" name="total_price" min="0" value="{{ $booking->total_price }}" class="w-full border rounded px-3 py-2">
                @error('total_price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="pending" {{ $booking->status==='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status==='confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ $booking->status==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>

            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded">Konfirmasi</button>
            </form>

            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" aria-label="Batalkan reservasi" class="bg-amber-400 text-black px-3 py-2 rounded hover:bg-amber-500 hover:text-white border border-amber-500">Batalkan</button>
            </form>

            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Hapus booking ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Hapus</button>
            </form>

            <a href="{{ route('admin.bookings.index') }}" class="ml-auto text-sm text-gray-600">Kembali</a>
        </div>
    </form>
</div>
@endsection