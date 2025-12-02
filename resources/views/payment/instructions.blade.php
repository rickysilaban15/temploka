@extends('dashboard.layouts.app')

@section('title', 'Instruksi Pembayaran - Temploka')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button - UPDATED: Kembali ke dashboard templates -->
    <div class="mb-6">
        <a href="{{ route('dashboard.templates') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Template</span>
        </a>
    </div>

    <div class="bg-white border border-gray-300 rounded-2xl p-6 lg:p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-building text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Instruksi Pembayaran</h1>
            <p class="text-gray-600">Lakukan pembayaran sesuai instruksi di bawah ini</p>
        </div>

        <!-- Order Info -->
        <div class="bg-gray-50 rounded-xl p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor Pesanan</p>
                    <p class="font-semibold text-gray-900">{{ $order->order_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                    <p class="font-semibold text-primary text-lg">Rp {{ number_format($order->amount) }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Template</p>
                    <p class="font-semibold text-gray-900">{{ $template->name }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Metode Pembayaran</p>
                    <p class="font-semibold text-gray-900">
                        {{ $order->payment_method === 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="space-y-6">
            @if($order->payment_method === 'bank_transfer')
            <!-- Bank Transfer Instructions -->
            <div class="border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rekening Tujuan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bank</span>
                        <span class="font-semibold">BCA - Bank Central Asia</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Rekening</span>
                        <span class="font-semibold">1234 5678 9012 3456</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Atas Nama</span>
                        <span class="font-semibold">PT. Temploka Indonesia</span>
                    </div>
                </div>
            </div>

            <!-- Transfer Steps -->
            <div class="border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Langkah-langkah Transfer</h3>
                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                    <li>Buka aplikasi mobile banking atau internet banking bank Anda</li>
                    <li>Pilih menu transfer antar bank</li>
                    <li>Masukkan nomor rekening tujuan: <strong>1234 5678 9012 3456</strong></li>
                    <li>Masukkan nominal transfer: <strong>Rp {{ number_format($order->amount) }}</strong></li>
                    <li>Konfirmasi dan lakukan transfer</li>
                    <li>Simpan bukti transfer Anda</li>
                </ol>
            </div>
            @else
            <!-- E-Wallet Instructions -->
            <div class="border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Instruksi E-Wallet</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode</span>
                        <span class="font-semibold">E-Wallet (GOPAY/OVO/DANA)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Telepon</span>
                        <span class="font-semibold">0812-3456-7890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Atas Nama</span>
                        <span class="font-semibold">PT. Temploka Indonesia</span>
                    </div>
                </div>
            </div>

            <!-- E-Wallet Steps -->
            <div class="border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Langkah-langkah E-Wallet</h3>
                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                    <li>Buka aplikasi e-wallet Anda (GOPAY/OVO/DANA)</li>
                    <li>Pilih menu transfer atau bayar</li>
                    <li>Masukkan nomor telepon: <strong>0812-3456-7890</strong></li>
                    <li>Masukkan nominal transfer: <strong>Rp {{ number_format($order->amount) }}</strong></li>
                    <li>Konfirmasi dan lakukan pembayaran</li>
                    <li>Simpan bukti pembayaran Anda</li>
                </ol>
            </div>
            @endif

            <!-- Upload Proof Section -->
            <div class="border border-gray-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran</h3>
                <form action="{{ route('payment.upload-proof') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Bukti Pembayaran (JPG, PNG, Max: 2MB)
                            </label>
                            <input type="file" name="payment_proof" accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            @error('payment_proof')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full bg-primary hover:bg-teal-700 text-white py-3 px-5 rounded-2xl font-semibold transition">
                            <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>

            <!-- Important Notes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Penting!
                </h3>
                <ul class="list-disc list-inside space-y-2 text-yellow-700">
                    <li>Bayar tepat sesuai nominal yang tertera</li>
                    <li>Proses verifikasi membutuhkan waktu 1x24 jam setelah upload bukti</li>
                    <li>Template akan otomatis aktif setelah pembayaran terverifikasi</li>
                    <li>Hubungi customer service jika mengalami kendala</li>
                </ul>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 mt-8">
            <!-- UPDATED: Kembali ke dashboard templates -->
            <a href="{{ route('dashboard.templates') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-5 rounded-2xl text-center font-semibold transition">
                Kembali ke Template
            </a>
            <button onclick="window.print()" class="flex-1 bg-primary hover:bg-teal-700 text-white py-3 px-5 rounded-2xl font-semibold transition">
                <i class="fas fa-print mr-2"></i>Cetak Instruksi
            </button>
        </div>
    </div>
</div>
@endsection