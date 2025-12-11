<?php

namespace App\Http\Controllers;

use App\Models\Booking; 
use Illuminate\Http\Request; 
use App\Models\Field;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    /**
     * Menyimpan reservasi baru ke database setelah validasi dan cek ketersediaan.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Baru
        $validated = $request->validate([
            'field_id'     => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_hour'   => 'required|date_format:H:i',
            'duration'     => 'required|integer|min:1|max:4', // Durasi 1-4 jam
        ]);

        // Konversi duration ke integer
        $duration = (int) $validated['duration'];
        $bookingDate = $validated['booking_date'];
        $startHour = $validated['start_hour'];

        // Gabungkan tanggal dan jam menjadi objek Carbon/DateTime yang valid
        $startTime = \Carbon\Carbon::parse($bookingDate . ' ' . $startHour);
        $endTime = $startTime->copy()->addHours($duration);
        
        // Konversi kembali ke string untuk query
        $startTimeStr = $startTime->format('Y-m-d H:i:s');
        $endTimeStr = $endTime->format('Y-m-d H:i:s');

        // Cek apakah waktu mulai di masa lalu (hanya cek jika tanggalnya hari ini)
        if ($startTime->isPast()) {
            return redirect()->back()->with('error', 'Waktu reservasi tidak boleh di masa lalu!');
        }
        
        // --- 2. Logika Pengecekan Overlap ---
        $isBooked = Booking::where('field_id', $validated['field_id'])
            ->where(function($query) use ($startTimeStr, $endTimeStr) {
                $query->whereBetween('start_time', [$startTimeStr, $endTimeStr])
                      ->orWhereBetween('end_time', [$startTimeStr, $endTimeStr])
                      ->orWhere(function($q) use ($startTimeStr, $endTimeStr) {
                          $q->where('start_time', '<=', $startTimeStr)
                            ->where('end_time', '>=', $endTimeStr);
                      });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($isBooked) {
            return redirect()->back()->with('error', 'Lapangan sudah terbooking pada waktu tersebut!');
        }
        
        // --- 3. Jika Lapangan Kosong, Lanjutkan Proses Booking ---
        $field = Field::findOrFail($validated['field_id']);
        $totalPrice = $field->price_per_hour * $duration;

        Booking::create([
            'user_id'      => Auth::id(),
            'field_id'     => $validated['field_id'],
            'start_time'   => $startTimeStr,
            'end_time'     => $endTimeStr,
            'total_hours'  => $duration,
            'total_price'  => $totalPrice,
            'status'       => 'pending', 
        ]);

        return redirect()->route('user.history')->with('success', 'Reservasi berhasil dibuat! Total biaya: Rp ' . number_format($totalPrice, 0, ',', '.'));
    }

    public function create(Field $field)
    {
        // Buat array jam dari 08:00 hingga 22:00
        $availableHours = [];
        for ($hour = 8; $hour < 22; $hour++) {
            $availableHours[] = sprintf('%02d:00', $hour);
        }

        return view('booking.create', compact('field', 'availableHours'));
    }

    public function userHistory()
    {
        $history = Booking::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.history', compact('history'));
    }
}
