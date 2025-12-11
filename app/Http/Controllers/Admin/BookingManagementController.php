<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class BookingManagementController extends Controller
{
    // 1. Melihat Semua Booking
    public function index()
    {
        // Ambil semua booking, urutkan dari yang terbaru (pending ditaruh di atas)
        $bookings = Booking::with(['user', 'field'])
                            ->orderBy('status', 'asc')
                            ->latest()
                            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    // 2. Mengonfirmasi Booking
    public function confirm(Booking $booking)
    {
        // Pengecekan overlap juga harus dilakukan di sini,
        // untuk mencegah admin mengonfirmasi booking yang tumpang tindih
        // jika data diubah secara manual di tengah jalan. (Logika bisnis penting!)

        // Sederhana: ubah status
        $booking->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Reservasi berhasil dikonfirmasi.');
    }

    // 3. Membatalkan Booking
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('warning', 'Reservasi berhasil dibatalkan.');
    }

    public function reportIndex(Request $request)
    {
        // Ambil input filter, default ke bulan dan tahun saat ini
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $fieldId = $request->input('field_id'); // Filter Lapangan

        // Ambil daftar semua lapangan untuk dropdown filter
        // Gunakan FQCN jika Anda belum meng-import Field
        $fields = \App\Models\Field::all(); 

        // 1. Inisiasi Query untuk Laporan
        $bookings = Booking::with('user', 'field')
            ->where('status', 'confirmed'); // Hanya hitung booking yang sudah confirmed
        
        // 2. Terapkan Filter Bulan dan Tahun
        if ($month && $year) {
            $bookings->whereMonth('start_time', $month)
                    ->whereYear('start_time', $year);
        }
        
        // 3. Terapkan Filter Lapangan jika dipilih
        if ($fieldId) {
            $bookings->where('field_id', $fieldId);
        }
        
        $bookings = $bookings->get();
        
        // 4. Hitung Total Pemasukan
        $totalRevenue = $bookings->sum('total_price');

        // 5. Data Laporan (untuk dikirim ke View)
        $reportData = [
            'totalRevenue' => $totalRevenue,
            'bookings' => $bookings,
        ];
        
        // Kirim data ke View, termasuk nilai filter yang sedang aktif
        return view('admin.reports.index', compact('reportData', 'fields', 'month', 'year', 'fieldId'));
    }
}
