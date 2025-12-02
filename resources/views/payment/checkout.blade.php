@extends('dashboard.layouts.app')

@section('title', 'Pembayaran - ' . $template->name . ' - Temploka')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button - UPDATED: Kembali ke dashboard templates -->
    <div class="mb-6">
        <a href="{{ route('dashboard.templates') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Template</span>
        </a>
    </div>

    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="template_id" value="{{ $template->id }}">
        
        <div class="flex flex-col lg:flex-row gap-5">
            <!-- Left Column - Payment Methods -->
            <div class="flex-1 space-y-5">
                <!-- Payment Methods -->
                <div class="bg-white border border-gray-300 rounded-2xl p-5">
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-gray-900 mb-2.5">Metode Pembayaran</h2>
                        <p class="text-base text-gray-900">Pilih metode pembayaran yang diinginkan</p>
                    </div>
                    
                    <div class="space-y-3">
                        <!-- Bank Transfer -->
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

                        <!-- E-Wallet -->
                        <label class="block cursor-pointer">
                            <input type="radio" name="payment_method" value="e_wallet" class="hidden">
                            <div class="border border-gray-300 rounded-xl p-3.5 flex items-center gap-5 cursor-pointer hover:border-primary transition">
                                <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-wallet text-primary text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">E-Wallet</h3>
                                    <p class="text-base text-gray-900">GOPAY/OVO/DANA</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                    <h3 class="text-lg font-bold text-yellow-800 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>Instruksi Pembayaran
                    </h3>
                    <ol class="list-decimal list-inside space-y-2 text-yellow-700 text-sm">
                        <li>Pilih metode pembayaran yang diinginkan</li>
                        <li>Klik "Lanjutkan ke Pembayaran"</li>
                        <li>Anda akan mendapatkan instruksi pembayaran lengkap</li>
                        <li>Lakukan pembayaran sesuai nominal yang tertera</li>
                        <li>Template akan aktif setelah pembayaran dikonfirmasi</li>
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
                        <span class="inline-block px-2.5 py-1 rounded-md text-sm 
                            @if($template->price == 0) bg-green-100 text-green-600
                            @else bg-blue-100 text-blue-600 @endif">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle payment method selection
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Remove selected style from all methods
                document.querySelectorAll('input[name="payment_method"]').forEach(m => {
                    const parent = m.closest('label');
                    if (!parent) return; // NULL CHECK
                    
                    const parentDiv = parent.querySelector('div');
                    if (!parentDiv) return; // NULL CHECK
                    
                    if (m.checked) {
                        parentDiv.classList.add('border-2', 'border-primary', 'bg-primary', 'bg-opacity-15');
                        parentDiv.classList.remove('border', 'border-gray-300');
                        
                        // Show check icon
                        if (!parentDiv.querySelector('.fa-check-circle')) {
                            const checkIcon = document.createElement('i');
                            checkIcon.className = 'fas fa-check-circle text-primary text-xl';
                            parentDiv.appendChild(checkIcon);
                        }
                    } else {
                        parentDiv.classList.remove('border-2', 'border-primary', 'bg-primary', 'bg-opacity-15');
                        parentDiv.classList.add('border', 'border-gray-300');
                        
                        // Remove check icon
                        const checkIcon = parentDiv.querySelector('.fa-check-circle');
                        if (checkIcon) {
                            checkIcon.remove();
                        }
                    }
                });
            });
        });

        // Form submission handling
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (!submitButton) return; // NULL CHECK
                
                const originalText = submitButton.innerHTML;
                
                // Show loading state
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                submitButton.disabled = true;
                
                // Note: Form akan submit secara normal, loading ini hanya visual
                // Jika ingin cancel submit untuk testing, uncomment baris berikut:
                // e.preventDefault();
                
                // Simulate processing delay (hanya untuk preview, hapus di production)
                // setTimeout(() => {
                //     submitButton.innerHTML = originalText;
                //     submitButton.disabled = false;
                // }, 2000);
            });
        }
    });
</script>
@endpush