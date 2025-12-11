@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Laporan Pendapatan Booking</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- 1. FILTER FORM (GET Request) --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-wrap items-end gap-4">
        
        {{-- Filter Bulan --}}
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
            <select name="month" id="month" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Filter Tahun --}}
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
            <select name="year" id="year" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        
        {{-- Filter Lapangan --}}
        <div>
            <label for="field_id" class="block text-sm font-medium text-gray-700">Lapangan</label>
            <select name="field_id" id="field_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Semua Lapangan --</option>
                @foreach ($fields as $field)
                    <option value="{{ $field->id }}" {{ (int)$fieldId === $field->id ? 'selected' : '' }}>
                        {{ $field->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition duration-150">
            Filter Laporan
        </button>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md transition duration-150">Reset Filter</a>
    </form>
    
    {{-- 2. TOTAL PENDAPATAN --}}
    <div class="bg-indigo-100 p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold text-indigo-800">Total Pemasukan Periode Filter</h2>
        <p class="text-4xl font-bold text-indigo-600">
            Rp {{ number_format($reportData['totalRevenue'], 0, ',', '.') }}
        </p>
    </div>

    <h2 class="text-xl font-semibold mb-3">Detail Booking Terkonfirmasi ({{ $reportData['bookings']->count() }} data)</h2>
    
    {{-- 3. TABEL DETAIL BOOKING --}}
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Mulai</th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportData['bookings'] as $booking)
                <tr>
                    <td class="py-4 px-6 whitespace-nowrap text-sm text-center">{{ $booking->id }}</td>
                    <td class="py-4 px-6 whitespace-nowrap text-sm">{{ $booking->user->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">{{ $booking->field->name ?? 'N/A' }}</td>
                    <td class="py-4 px-6 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6 whitespace-nowrap text-sm font-semibold text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada data booking yang terkonfirmasi dalam periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection