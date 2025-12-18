@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Riwayat Reservasi Anda</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lihat status reservasi, ulangi pemesanan, atau cek detail transaksi.</p>
        </div>

        <div class="flex gap-3 items-center w-full sm:w-auto">
            <a href="{{ route('fields.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md shadow text-sm">
                Buat Reservasi Baru
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="mb-4">
            <div class="rounded-md px-4 py-3 border bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($history->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg class="mx-auto w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 7h18M3 12h18M3 17h18"/>
            </svg>
            <h2 class="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-200">Belum ada riwayat reservasi</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ayo pesan lapangan pertama Anda sekarang.</p>
            <div class="mt-4">
                <a href="{{ route('fields.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Pesan Sekarang
                </a>

            </div>
        </div>
    @else
        <!-- MOBILE: CARD LIST -->
        <div class="grid gap-4 md:hidden">
            @foreach($history as $booking)
                @php
                    $status = $booking->status ?? 'unknown';
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                        'confirmed' => 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                        'cancelled' => 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                        'completed' => 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                    ][$status] ?? 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200';
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $booking->field->name ?? 'Lapangan Dihapus' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }} — {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors }} capitalize">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ url('/bookings/'.$booking->id) }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Lihat Detail</a>
                        <a href="{{ route('booking.create', $booking->field->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                            Ulangi Pesanan
                        </a>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- DESKTOP: TABLE -->
        <div class="hidden md:block mt-4">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lapangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Biaya</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($history as $booking)
                            @php
                                $status = $booking->status ?? 'unknown';
                                $statusColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                    'confirmed' => 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                    'cancelled' => 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                    'completed' => 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                ][$status] ?? 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $booking->field->name ?? 'Lapangan Dihapus' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $booking->field_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}
                                        <span class="text-xs text-gray-400">—</span>
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors }} capitalize">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection