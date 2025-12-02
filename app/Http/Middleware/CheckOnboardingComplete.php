<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika user belum complete onboarding, redirect ke onboarding
        if (!Auth::user()->onboarding_completed) {
            return redirect()->route('onboarding.step1');
        }

        return $next($request);
    }
}