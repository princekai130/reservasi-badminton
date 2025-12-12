
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-white">Daftar & Manajemen Reservasi</h1>

    {{-- Notifikasi Sukses/Warning dari Controller --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Lapangan</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Mulai</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Selesai</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                <tr>
                    <td class="py-3 px-4 text-sm text-gray-700">{{ $booking->id }}</td>
                    <td class="py-3 px-4 text-sm text-gray-700">
                        {{ $booking->user->name ?? '—' }}<br>
                        <span class="text-xs text-gray-500">{{ $booking->user->email ?? '' }}</span>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-700">{{ $booking->field->name ?? '—' }}</td>
                    <td class="py-3 px-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</td>
                    <td class="py-3 px-4 text-sm text-center">{{ $booking->total_hours ?? '-' }}</td>
                    <td class="py-3 px-4 text-sm text-gray-700">Rp {{ number_format($booking->total_price ?? 0,0,',','.') }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm text-center space-x-2">
                        @if($booking->status === 'pending')
                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition duration-150 relative z-10 pointer-events-auto focus:outline-none focus:ring-2 focus:ring-green-500">Konfirmasi</button>
                        </form>

                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" aria-label="Batalkan reservasi" class="bg-amber-400 text-black px-3 py-1 rounded text-sm hover:bg-amber-500 hover:text-white border border-amber-500 transition duration-150 relative z-10 pointer-events-auto focus:outline-none focus:ring-2 focus:ring-amber-500">Batalkan</button>
                        </form>
                        @endif

                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Yakin akan menghapus booking ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition duration-150 relative z-10 pointer-events-auto focus:outline-none focus:ring-2 focus:ring-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-6 text-center text-gray-500">Belum ada reservasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection