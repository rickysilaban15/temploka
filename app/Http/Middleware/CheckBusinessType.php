<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBusinessType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Cek apakah user sudah onboarding
        if (!$user->onboarding_completed) {
            return redirect()->route('onboarding.step1');
        }
        
        // Jika spesifik type dibutuhkan
        if (!empty($types) && !in_array($user->business_type, $types)) {
            // Redirect ke dashboard umum
            return redirect()->route('dashboard')
                ->with('warning', 'Fitur ini khusus untuk tipe bisnis tertentu.');
        }
        
        return $next($request);
    }
}