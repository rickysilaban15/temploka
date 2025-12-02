<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    // ❌ HAPUS bagian __construct() ini
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function step1()
    {
        // Jika user sudah onboarding, redirect ke dashboard
        if (Auth::user()->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.step1');
    }

    public function storeBusinessType(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string|in:retail,culinary,fashion,services,other'
        ]);

        // Simpan business type ke database
        $user = Auth::user();
        $user->business_type = $request->business_type;
        $user->save();

        // Simpan juga ke session untuk step berikutnya
        session(['business_type' => $request->business_type]);

        return redirect()->route('onboarding.step2');
    }

    public function step2()
    {
        if (Auth::user()->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.step2');
    }

    public function storeGoals(Request $request)
    {
        $request->validate([
            'goals' => 'required|array',
            'goals.*' => 'in:finance,crm,ecommerce,inventory'
        ]);

        // Simpan goals ke database
        $user = Auth::user();
        $user->business_goals = $request->goals;
        $user->save();

        // Simpan juga ke session untuk step berikutnya
        session(['goals' => $request->goals]);

        return redirect()->route('onboarding.step3');
    }

    public function step3()
    {
        if (Auth::user()->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        // Ambil data dari session untuk rekomendasi
        $businessType = session('business_type');
        $goals = session('goals', []);
        
        // Generate rekomendasi template berdasarkan goals
        $recommendations = $this->generateRecommendations($goals);
        
        return view('onboarding.step3', compact('recommendations'));
    }

    public function complete(Request $request)
    {
        // Simpan data onboarding ke database
        $user = Auth::user();
        
        // Mark user sebagai completed onboarding
        $user->onboarding_completed = true;
        $user->save();

        // Clear session data
        session()->forget(['business_type', 'goals']);

        // Redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Onboarding berhasil diselesaikan! Selamat datang di dashboard Temploka!');
    }

    private function generateRecommendations($goals)
    {
        $recommendations = [];
        
        if (in_array('finance', $goals)) {
            $recommendations[] = [
                'id' => 'finance-pro',
                'title' => 'Dashboard Keuangan Pro',
                'description' => 'Kelola arus kas, laporan laba rugi, dan proyeksi keuangan',
                'features' => ['Laporan Real-time', 'Grafik Interaktif', 'Multi Akun'],
                'icon' => 'fas fa-chart-line',
                'color' => 'from-blue-500 to-blue-600'
            ];
        }
        
        if (in_array('crm', $goals)) {
            $recommendations[] = [
                'id' => 'crm-pro',
                'title' => 'CRM & Database Pelanggan',
                'description' => 'Kelola data pelanggan, riwayat transaksi, dan komunikasi',
                'features' => ['Segmentasi Otomatis', 'Follow-up Reminder', 'Analytics'],
                'icon' => 'fas fa-users',
                'color' => 'from-green-500 to-green-600'
            ];
        }
        
        if (in_array('ecommerce', $goals) || in_array('inventory', $goals)) {
            $recommendations[] = [
                'id' => 'ecommerce-pro',
                'title' => 'Toko Online Lengkap',
                'description' => 'Template toko online dengan integrasi payment gateway',
                'features' => ['Keranjang Belanja', 'Checkout Mudah', 'Integrasi Ongkir'],
                'icon' => 'fas fa-store',
                'color' => 'from-purple-500 to-purple-600'
            ];
        }
        
        // Default recommendations jika tidak ada goals yang dipilih
        if (empty($recommendations)) {
            $recommendations = [
                [
                    'id' => 'starter',
                    'title' => 'Template Starter Pack',
                    'description' => 'Template lengkap untuk memulai bisnis Anda',
                    'features' => ['Dashboard Basic', 'Manajemen Produk', 'Laporan Sederhana'],
                    'icon' => 'fas fa-rocket',
                    'color' => 'from-gray-500 to-gray-600'
                ]
            ];
        }
        
        return $recommendations;
    }
}