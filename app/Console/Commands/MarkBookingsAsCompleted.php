<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking; // Import Model Booking
use Carbon\Carbon;     // Import Carbon

class MarkBookingsAsCompleted extends Command
{
    protected $signature = 'booking:complete'; // Nama perintah yang akan dijalankan
    protected $description = 'Marks confirmed bookings as completed if their end time has passed.';

    public function handle()
    {
        // 1. Dapatkan waktu saat ini
        $now = Carbon::now();

        // 2. Cari booking yang:
        //    a. Berstatus 'confirmed'
        //    b. Waktu 'end_time' telah lewat (kurang dari waktu saat ini)
        $bookingsToComplete = Booking::where('status', 'confirmed')
                                     ->where('end_time', '<', $now)
                                     ->get();

        $count = $bookingsToComplete->count();

        if ($count > 0) {
            // 3. Update status booking
            $bookingsToComplete->each(function ($booking) {
                $booking->update(['status' => 'completed']);
            });

            $this->info("{$count} booking berhasil diubah statusnya menjadi 'completed'.");
        } else {
            $this->info('Tidak ada booking yang perlu diubah statusnya saat ini.');
        }

        return Command::SUCCESS;
    }
}
