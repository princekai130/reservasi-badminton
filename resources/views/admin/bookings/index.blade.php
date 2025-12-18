@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4" x-data="{ q: '', status: 'all' }">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Daftar & Manajemen Reservasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua reservasi, konfirmasi, batalkan, atau hapus sesuai kebutuhan.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:flex-none">
                <input type="search" x-model="q" placeholder="Cari ID, user, atau lapangan..." class="w-full md:w-80 rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400">
                <button type="button" x-show="q" @click="q=''" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">×</button>
            </div>

            <select x-model="status" class="rounded-md border px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>

            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md shadow text-sm">
                Refresh
            </a>
        </div>
    </div>

    {{-- Notifikasi Sukses/Warning --}}
    @if(session('success'))
        <div class="mb-4">
            <div class="rounded-md px-4 py-3 border bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4">
            <div class="rounded-md px-4 py-3 border bg-yellow-50 border-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    {{-- MOBILE: Card list --}}
    <div class="grid gap-4 md:hidden">
        @forelse ($bookings as $booking)
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow p-4"
            x-show="(status === 'all' || status === '{{ $booking->status }}') && (!q || ('{{ $booking->id }} {{ $booking->user->name ?? '' }} {{ $booking->field->name ?? '' }}').toLowerCase().includes(q.toLowerCase()))"
            x-cloak
        >
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $booking->field->name ?? 'Lapangan Dihapus' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }} — {{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price ?? 0,0,',','.') }}</div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status === 'pending' ? 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : ($booking->status === 'confirmed' ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300' : ($booking->status === 'cancelled' ? 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300')) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 gap-3">
                <a href="{{ url('/bookings/'.$booking->id) }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Lihat Detail</a>

                <div class="flex items-center gap-2">
                    @if($booking->status === 'pending')
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1 rounded text-sm bg-green-600 hover:bg-green-700 text-white">Konfirmasi</button>
                    </form>
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1 rounded text-sm bg-amber-400 hover:bg-amber-500 text-black">Batalkan</button>
                    </form>
                    @endif
                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Yakin akan menghapus booking ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 rounded text-sm bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 dark:text-gray-300">Belum ada reservasi.</p>
        </div>
        @endforelse
    </div>

    {{-- DESKTOP: Table --}}
    <div class="hidden md:block mt-4">
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Lapangan</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Waktu</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jam</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($bookings as $booking)
                    <tr
                        class="hover:bg-gray-50 dark:hover:bg-gray-900 transition"
                        x-show="(status === 'all' || status === '{{ $booking->status }}') && (!q || ('{{ $booking->id }} {{ $booking->user->name ?? '' }} {{ $booking->field->name ?? '' }}').toLowerCase().includes(q.toLowerCase()))"
                        x-cloak
                    >
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-200">{{ $booking->id }}</td>
                        <td class="py-3 px-4 text-sm">
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $booking->user->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-200">{{ $booking->field->name ?? '—' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}<span class="text-xs text-gray-400"> — </span>{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-4 text-sm text-center">{{ $booking->total_hours ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-right text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price ?? 0,0,',','.') }}</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status === 'pending' ? 'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : ($booking->status === 'confirmed' ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300' : ($booking->status === 'cancelled' ? 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded text-sm bg-green-600 hover:bg-green-700 text-white">Konfirmasi</button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded text-sm bg-amber-400 hover:bg-amber-500 text-black">Batalkan</button>
                                </form>
                                @endif

                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Yakin akan menghapus booking ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded text-sm bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500 dark:text-gray-300">Belum ada reservasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($bookings, 'links'))
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection