<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
        // Cek apakah user login dan punya flag is_admin = 1
        {if (Auth::check() && Auth::user()?->is_admin) {
            return $next($request);
        }
        

        // Jika bukan admin, redirect
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman admin!');
    }
}
