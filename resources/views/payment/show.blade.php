@extends('dashboard.layouts.app')

@section('title', 'Pembayaran - ' . $template->name . ' - Temploka')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('templates.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Template</span>
        </a>
    </div>

    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="template_id" value="{{ $template->id }}">
        
        <div class="flex flex-col lg:flex-row gap-5">
            <!-- Left Column - Payment Methods & Bank Details -->
            <div class="flex-1 space-y-5">
                <!-- Payment Methods -->
                <div class="bg-white border border-gray-300 rounded-2xl p-5">
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-gray-900 mb-2.5">Metode Pembayaran</h2>
                        <p class="text-base text-gray-900">Pilih metode pembayaran yang diinginkan</p>
                    </div>
                    
                    <div class="space-y-3">
                        <!-- Bank Transfer (Selected by Default) -->
                        <label class="block cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" class="hidden" checked>
                            <div class="border-2 border-primary bg-primary bg-opacity-15 rounded-xl p-3.5 flex items-center gap-5 transition">
                                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building-columns text-primary text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">Transfer Bank</h3>
                                    <p class="text-base text-gray-900">BRI/BCA/BSI/Mandiri</p>
                                </div>
                                <i class="fas fa-check-circle text-primary text-xl"></i>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Bank Transfer Details -->
                <div class="bg-white border border-gray-300 rounded-2xl p-5">
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Detail Transfer Bank</h2>
                    
                    <div class="space-y-5">
                        <!-- Bank Selection -->
                        <div>
                            <label class="text-base font-bold text-gray-900 mb-2.5 block">Pilih Bank</label>
                            <div class="relative">
                                <select name="bank_name" class="w-full bg-primary-100 bg-opacity-50 border-0 rounded-2xl px-5 py-3.5 text-base text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary pl-12 appearance-none" required>
                                    <option value="">Pilih Bank Tujuan</option>
                                    <option value="BCA">BCA - Bank Central Asia</option>
                                    <option value="BRI">BRI - Bank Rakyat Indonesia</option>
                                    <option value="BSI">BSI - Bank Syariah Indonesia</option>
                                    <option value="Mandiri">Bank Mandiri</option>
                                    <option value="BNI">BNI - Bank Negara Indonesia</option>
                                </select>
                                <i class="fas fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                <i class="fas fa-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            </div>
                        </div>
                        
                        <!-- Account Number -->
                        <div>
                            <label class="text-base font-bold text-gray-900 mb-2.5 block">Nomor Rekening</label>
                            <div class="relative">
                                <input type="text" name="account_number" placeholder="Masukkan nomor rekening Anda" 
                                       class="w-full bg-primary-100 bg-opacity-50 border-0 rounded-2xl px-5 py-3.5 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary pl-12" required>
                                <i class="fas fa-credit-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            </div>
                        </div>
                        
                        <!-- Account Name -->
                        <div>
                            <label class="text-base font-bold text-gray-900 mb-2.5 block">Nama Pemilik Rekening</label>
                            <div class="relative">
                                <input type="text" name="account_name" placeholder="Nama lengkap sesuai rekening" 
                                       class="w-full bg-primary-100 bg-opacity-50 border-0 rounded-2xl px-5 py-3.5 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary pl-12" required>
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                    <h3 class="text-lg font-bold text-yellow-800 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Instruksi Pembayaran
                    </h3>
                    <ol class="list-decimal list-inside space-y-2 text-yellow-700 text-sm">
                        <li>Pilih bank tujuan transfer</li>
                        <li>Isi nomor rekening dan nama pemilik rekening Anda</li>
                        <li>Klik "Lanjutkan ke Pembayaran"</li>
                        <li>Anda akan mendapatkan instruksi transfer lengkap</li>
                        <li>Lakukan transfer sesuai nominal yang tertera</li>
                        <li>Template akan otomatis aktif setelah pembayaran dikonfirmasi</li>
                    </ol>
                </div>
            </div>
            
            <!-- Right Column - Order Summary -->
            <div class="lg:w-[399px]">
                <div class="bg-white border border-gray-300 rounded-2xl p-5 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Ringkasan Pesanan</h2>
                    
                    <!-- Product Info -->
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $template->name }}</h3>
                        <span class="inline-block px-2.5 py-1 rounded-md text-sm bg-blue-100 text-blue-600">
                            {{ $template->price == 0 ? 'Free' : 'Premium' }}
                        </span>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="space-y-2.5 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="text-base text-gray-900">Harga Template</span>
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($template->price) }}</span>
                        </div>
                        
                        @if($template->price > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-base text-gray-900">Biaya Admin</span>
                            <span class="text-lg font-bold text-gray-900">Rp 2.500</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-base text-gray-900">Pajak (10%)</span>
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($template->price * 0.1) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Divider -->
                    <div class="border-t border-gray-300 my-5"></div>
                    
                    <!-- Total -->
                    <div class="flex justify-between items-center mb-5">
                        <span class="text-base font-bold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-primary">
                            Rp {{ number_format($template->price == 0 ? 0 : $template->price + 2500 + ($template->price * 0.1)) }}
                        </span>
                    </div>
                    
                    <!-- Pay Button -->
                    <button type="submit" class="w-full bg-primary hover:bg-teal-700 text-white py-3.5 px-5 rounded-2xl text-lg font-bold transition">
                        Lanjutkan ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection