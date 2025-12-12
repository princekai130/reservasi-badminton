@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-500">Riwayat Reservasi Anda</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Biaya</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($history as $booking)
                <tr>
                    <td class="py-4 px-6 whitespace-nowrap">{{ $booking->field->name ?? 'Lapangan Dihapus' }}</td>
                    <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6 whitespace-nowrap font-semibold">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        @php
                            $statusColor = [
                                'pending' => 'text-yellow-600 bg-yellow-100',
                                'confirmed' => 'text-green-600 bg-green-100',
                                'cancelled' => 'text-red-600 bg-red-100',
                                'completed' => 'text-blue-600 bg-blue-100', // Tambahkan status completed
                            ][$booking->status] ?? 'text-gray-600 bg-gray-100';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }} capitalize">
                            {{ $booking->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">Anda belum memiliki riwayat reservasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection