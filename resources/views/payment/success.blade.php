@extends('dashboard.layouts.app')

@section('title', 'Pembayaran Berhasil - Temploka')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success Icon -->
    <div class="flex justify-center mb-5">
        <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center">
            <i class="fas fa-check text-white text-4xl"></i>
        </div>
    </div>
    
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2.5">
            @if($order->status === 'paid')
                Pembayaran Berhasil!
            @elseif($order->status === 'pending')
                Pesanan Diproses!
            @else
                Pesanan Gagal
            @endif
        </h1>
        <p class="text-sm lg:text-base text-gray-900">
            @if($order->status === 'paid')
                Terima kasih atas pembelian Anda. Template sekarang sudah aktif!
            @elseif($order->status === 'pending')
                Menunggu konfirmasi pembayaran
            @else
                Silakan coba lagi atau hubungi customer service
            @endif
        </p>
    </div>
    
    <!-- Order Details Card -->
    <div class="bg-white border border-gray-300 rounded-2xl p-5 lg:p-6">
        <!-- Order Number -->
        <div class="text-center mb-5 pb-5 border-b border-gray-300">
            <p class="text-sm lg:text-base text-gray-900 mb-2">Nomor Pesanan</p>
            <h2 class="text-xl lg:text-3xl font-bold text-gray-900 mb-3">{{ $order->order_code }}</h2>
            <span class="inline-block px-2.5 py-1 rounded-md text-sm 
                @if($order->status === 'paid') bg-green-100 text-green-600
                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-600
                @else bg-red-100 text-red-600 @endif">
                @if($order->status === 'paid') Lunas
                @elseif($order->status === 'pending') Menunggu Pembayaran
                @else Gagal @endif
            </span>
        </div>
        
        <!-- Transaction Details -->
        <div class="space-y-5 mb-5">
            <!-- Template -->
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file text-primary text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm lg:text-base text-gray-900">Template</p>
                    <h3 class="text-lg lg:text-xl font-bold text-gray-900">{{ $template->name }}</h3>
                </div>
            </div>
            
            <!-- Transaction Date -->
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar text-primary text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm lg:text-base text-gray-900">Tanggal Pesanan</p>
                    <h3 class="text-lg lg:text-xl font-bold text-gray-900">
                        {{ $order->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB
                    </h3>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-credit-card text-primary text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm lg:text-base text-gray-900">Metode Pembayaran</p>
                    <h3 class="text-lg lg:text-xl font-bold text-gray-900">
                        @if($order->payment_method === 'bank_transfer') Transfer Bank
                        @elseif($order->payment_method === 'e_wallet') E-Wallet
                        @elseif($order->payment_method === 'free') Gratis
                        @else {{ ucfirst($order->payment_method) }} @endif
                    </h3>
                </div>
            </div>
            
            @if($order->status === 'pending' && $order->payment_method !== 'free')
            <!-- Payment Instructions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <p class="text-yellow-700 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Silakan lakukan pembayaran dan upload bukti pembayaran. Template akan aktif otomatis setelah pembayaran terverifikasi.
                </p>
            </div>
            @endif
        </div>
        
        <!-- Total Payment -->
        <div class="bg-primary-100 rounded-2xl p-5 mb-5 flex justify-between items-center">
            <span class="text-base lg:text-lg text-gray-900">Total Pembayaran</span>
            <span class="text-xl lg:text-2xl font-bold text-primary">Rp {{ number_format($order->amount) }}</span>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-3">
            @if($order->status === 'paid')
            <!-- Jika sudah bayar, langsung ke modules -->
            <a href="{{ route('dashboard.modules') }}" class="w-full bg-primary hover:bg-teal-700 text-white py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Mulai Gunakan Template
            </a>
            <a href="/dashboard" class="w-full border-2 border-primary hover:bg-primary-100 text-primary py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Kembali ke Dashboard
            </a>
            @elseif($order->status === 'pending' && $order->payment_method !== 'free')
            <!-- Jika masih pending, tampilkan opsi upload bukti -->
            <a href="{{ route('payment.instructions', $order) }}" class="w-full bg-primary hover:bg-teal-700 text-white py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Upload Bukti Pembayaran
            </a>
            <a href="{{ route('dashboard.templates') }}" class="w-full border-2 border-primary hover:bg-primary-100 text-primary py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Lihat Template Lain
            </a>
            @else
            <!-- Fallback -->
            <a href="/dashboard" class="w-full bg-primary hover:bg-teal-700 text-white py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('dashboard.templates') }}" class="w-full border-2 border-primary hover:bg-primary-100 text-primary py-3.5 px-5 rounded-2xl text-base lg:text-lg font-bold transition block text-center">
                Lihat Template Lain
            </a>
            @endif
        </div>
    </div>
</div>
@endsection