<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user adalah admin
        // Anda bisa menyesuaikan dengan field di model User
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
        }
        
        return $next($request);
    }
}