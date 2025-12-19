@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Laporan Pendapatan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan pendapatan dan detail booking yang terkonfirmasi.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md shadow text-sm">
                <!-- Icon -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/></svg>
                Export CSV
            </a>

            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-md shadow text-sm">
                Refresh
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4">
            <div class="rounded-md px-4 py-3 border bg-emerald-50 border-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 items-end">
        <div>
            <label for="month" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Bulan</label>
            <select name="month" id="month" class="mt-1 block w-full py-2 px-3 border rounded-md bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                    </option>
                @endfor
            </select>
        </div>

        <div>
            <label for="year" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Tahun</label>
            <select name="year" id="year" class="mt-1 block w-full py-2 px-3 border rounded-md bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div>
            <label for="field_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Lapangan</label>
            <select name="field_id" id="field_id" class="mt-1 block w-full py-2 px-3 border rounded-md bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-400">
                <option value="">-- Semua Lapangan --</option>
                @foreach ($fields as $f)
                    <option value="{{ $f->id }}" {{ (int)$fieldId === $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-md">Filter Laporan</button>
            <a href="{{ route('admin.reports.index') }}" class="w-full md:w-auto bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 font-semibold py-2 px-4 rounded-md text-center">Reset</a>
        </div>
    </form>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Pendapatan</div>
                    <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($reportData['totalRevenue'],0,',','.') }}</div>
                </div>
                <div class="text-gray-400 dark:text-gray-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-6 4-8 8-8 8h20s-2-4-8-8z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Total Booking</div>
            <div class="mt-2 text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ $reportData['bookings']->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">data terkonfirmasi</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Rata-rata per Booking</div>
            <div class="mt-2 text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">
                Rp {{ number_format($reportData['bookings']->count() ? $reportData['totalRevenue'] / $reportData['bookings']->count() : 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">Periode</div>
            <div class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ date('F', mktime(0,0,0,(int)$month,1)) }} {{ $year }}</div>
        </div>
    </div>

    {{-- Detail list: responsive cards + table --}}
    {{-- Mobile cards --}}
    <div class="grid gap-4 md:hidden">
        @forelse ($reportData['bookings'] as $booking)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $booking->field->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $booking->user->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200' }}">
                                {{ ucfirst($booking->status ?? '—') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center text-gray-500 dark:text-gray-300">
                Tidak ada data booking terkonfirmasi dalam periode ini.
            </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <div class="hidden md:block mt-4">
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Lapangan</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Waktu Mulai</th>
                        <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Harga</th>
                        <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($reportData['bookings'] as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                            <td class="py-4 px-6 text-sm font-medium text-gray-700 dark:text-gray-200">{{ $booking->field->name ?? 'N/A' }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                            <td class="py-4 px-6 text-sm text-right font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200' }}">
                                    {{ ucfirst($booking->status ?? '—') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-300">Tidak ada data booking terkonfirmasi dalam periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection