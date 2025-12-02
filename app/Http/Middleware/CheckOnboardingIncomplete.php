<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingIncomplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug info
        \Log::info('CheckOnboardingIncomplete middleware executed', [
            'user_id' => Auth::id(),
            'onboarding_completed' => Auth::user()?->onboarding_completed
        ]);

        // Jika user belum login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika user sudah complete onboarding, redirect ke dashboard
        if (Auth::user()->onboarding_completed) {
            \Log::info('User already completed onboarding, redirecting to dashboard');
            return redirect()->route('dashboard');
        }

        \Log::info('User has not completed onboarding, allowing access to onboarding');
        return $next($request);
    }
}   