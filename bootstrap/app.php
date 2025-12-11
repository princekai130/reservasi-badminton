<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\MarkBookingsAsCompleted; 
use Illuminate\Console\Scheduling\Schedule; // Import class Schedule

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withSchedule(function (Schedule $schedule) { // <-- DAFTARKAN JADWAL DI SINI
        // Jadwalkan Command Anda untuk dijalankan setiap jam
        $schedule->command(MarkBookingsAsCompleted::class)->hourly();
        // Atau $schedule->command('booking:complete')->daily();
        
        // Catatan: Jika Anda tidak meng-import MarkBookingsAsCompleted,
        // Anda harus menggunakan namespace penuh:
        // $schedule->command(\App\Console\Commands\MarkBookingsAsCompleted::class)->hourly();

    })
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
