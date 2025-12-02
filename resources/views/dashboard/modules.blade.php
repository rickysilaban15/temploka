@extends('dashboard.layouts.app')

@section('title', 'Modul Bisnis - Temploka')

@section('content')
    <!-- Success Message -->
    @if (session('success'))
    <div class="mb-4 sm:mb-6 bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded-2xl" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2 text-sm sm:text-base"></i>
            <span class="font-semibold text-sm sm:text-base">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Module Tab Navigation -->
    <div class="bg-primary-100 rounded-2xl p-2 mb-4 sm:mb-6 overflow-x-auto">
        <div class="flex gap-1 min-w-max">
            <button class="module-tab flex-1 py-2 px-3 sm:px-4 md:px-5 rounded-2xl font-bold text-sm sm:text-base transition-all duration-200 whitespace-nowrap" data-tab="invoice">
                Invoice Keuangan
            </button>
            <button class="module-tab flex-1 py-2 px-3 sm:px-4 md:px-5 rounded-2xl font-medium text-sm sm:text-base transition-all duration-200 whitespace-nowrap" data-tab="products">
                Katalog Produk
            </button>
            <button class="module-tab flex-1 py-2 px-3 sm:px-4 md:px-5 rounded-2xl font-medium text-sm sm:text-base transition-all duration-200 whitespace-nowrap" data-tab="crm">
                CRM
            </button>
            <button class="module-tab flex-1 py-2 px-3 sm:px-4 md:px-5 rounded-2xl font-medium text-sm sm:text-base transition-all duration-200 whitespace-nowrap" data-tab="ecommerce">
                E-Commerce
            </button>
        </div>
    </div>

    <!-- Module Content -->
    <div class="module-content">
        <!-- Invoice & Finance Module -->
        <div id="invoice-module" class="module-panel">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start justify-between mb-4 sm:mb-5 gap-3 sm:gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Invoice & Keuangan</h2>
                        <!-- Module Toggle Switch -->
                        <button 
                            onclick="toggleModule('invoice', this)"
                            class="module-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 bg-primary">
                            <span class="sr-only">Toggle module</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-6"></span>
                        </button>
                    </div>
                    <p class="text-gray-900 text-sm sm:text-base">Kelola invoice dan pembayaran pelanggan</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none border border-gray-300 hover:bg-gray-50 text-gray-900 px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Ekspor</span>
                        <span class="sm:hidden"><i class="fas fa-download"></i></span>
                    </button>
                    <button class="flex-1 sm:flex-none bg-primary hover:bg-teal-700 text-white px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Tambah Baru</span>
                        <span class="sm:hidden"><i class="fas fa-plus"></i></span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 mb-4 sm:mb-5">
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Invoice</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $invoices->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Lunas</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">Rp {{ number_format($invoices->where('status', 'paid')->sum('amount') ?? 0) }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Pending</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">Rp {{ number_format($invoices->where('status', 'pending')->sum('amount') ?? 0) }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Jatuh Tempo</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">Rp {{ number_format($invoices->where('status', 'overdue')->sum('amount') ?? 0) }}</h3>
                </div>
            </div>

            <!-- Invoice Table Card -->
            <div class="bg-white border border-gray-300 rounded-2xl p-3 sm:p-4 lg:p-5">
                <h3 class="text-sm sm:text-base text-gray-900 mb-3 sm:mb-4 lg:mb-5 font-semibold">Daftar Invoice</h3>
                
                <!-- Filters -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-4 sm:mb-5">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-3 sm:left-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500 text-xs sm:text-sm"></i>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Cari Invoice atau pelanggan"
                            class="w-full bg-gray-200 border-0 rounded-2xl pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 text-sm sm:text-base text-gray-500 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                    </div>
                    <div class="flex gap-2 sm:gap-3">
                        <div class="relative flex-1 sm:flex-none">
                            <select class="appearance-none w-full bg-gray-200 border-0 rounded-2xl pl-3 sm:pl-4 pr-8 sm:pr-10 py-2.5 text-sm sm:text-base text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">
                                <option>Semua Status</option>
                                <option>Lunas</option>
                                <option>Pending</option>
                                <option>Jatuh Tempo</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 sm:right-4 flex items-center pointer-events-none">
                                <i class="fas fa-caret-down text-gray-500 text-xs sm:text-sm"></i>
                            </div>
                        </div>
                        <button class="border border-gray-300 rounded-2xl px-3 sm:px-4 py-2.5 text-sm sm:text-base font-semibold text-gray-900 hover:bg-gray-50 transition flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                            <i class="fas fa-calendar text-xs sm:text-sm"></i>
                            <span class="hidden sm:inline">Pilih Tanggal</span>
                            <span class="sm:hidden">Tanggal</span>
                        </button>
                    </div>
                </div>

                <!-- Invoice Table Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border border-gray-300 rounded-t-xl">
                                <th class="text-left py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">No. Invoice</th>
                                <th class="text-left py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Nama Pelanggan</th>
                                <th class="text-left py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Tanggal</th>
                                <th class="text-left py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Jatuh Tempo</th>
                                <th class="text-left py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Status</th>
                                <th class="text-right py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Total</th>
                                <th class="text-right py-2.5 px-2.5 text-sm sm:text-base font-bold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-2.5 text-sm text-gray-900">{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                <td class="py-3 px-2.5 text-sm text-gray-900">{{ $invoice->customer_name ?? 'N/A' }}</td>
                                <td class="py-3 px-2.5 text-sm text-gray-900">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                                <td class="py-3 px-2.5 text-sm text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date ?? $invoice->created_at)->format('d M Y') }}</td>
                                <td class="py-3 px-2.5">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800', 
                                            'overdue' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($invoice->status ?? 'unknown') }}
                                    </span>
                                </td>
                                <td class="py-3 px-2.5 text-sm text-gray-900 text-right">Rp {{ number_format($invoice->amount ?? 0) }}</td>
                                <td class="py-3 px-2.5 text-right">
                                    <button class="text-primary hover:text-primary-600 text-sm font-semibold">
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 sm:py-16">
                                    <i class="fas fa-file-invoice text-gray-300 text-5xl sm:text-6xl mb-3 sm:mb-4"></i>
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Belum ada invoice</h3>
                                    <p class="text-gray-500 text-sm sm:text-base">Klik tombol "Tambah Baru" untuk membuat invoice pertama Anda</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Cards Mobile -->
                <div class="md:hidden space-y-3">
                    @forelse($invoices as $invoice)
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $invoice->invoice_number ?? 'N/A' }}</h4>
                                <p class="text-xs sm:text-sm text-gray-600">{{ $invoice->customer_name ?? 'N/A' }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($invoice->status ?? 'unknown') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-3">
                            <span>Due: {{ \Carbon\Carbon::parse($invoice->due_date ?? $invoice->created_at)->format('d M Y') }}</span>
                            <span class="font-semibold">Rp {{ number_format($invoice->amount ?? 0) }}</span>
                        </div>
                        <button class="w-full border border-primary text-primary py-2 rounded-lg text-sm font-semibold hover:bg-primary-50 transition">
                            Lihat Detail
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-10 sm:py-12">
                        <i class="fas fa-file-invoice text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">Belum ada invoice</h3>
                        <p class="text-gray-500 text-xs sm:text-sm">Klik "Tambah Baru" untuk membuat invoice</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Products Catalog Module -->
        <div id="products-module" class="module-panel">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start justify-between mb-4 sm:mb-5 gap-3 sm:gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Katalog Produk</h2>
                        <!-- Module Toggle Switch -->
                        <button 
                            onclick="toggleModule('products', this)"
                            class="module-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 bg-primary">
                            <span class="sr-only">Toggle module</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-6"></span>
                        </button>
                    </div>
                    <p class="text-gray-900 text-sm sm:text-base">Kelola produk dan stok inventori</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none border border-gray-300 hover:bg-gray-50 text-gray-900 px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Ekspor</span>
                        <span class="sm:hidden"><i class="fas fa-download"></i></span>
                    </button>
                    <button class="flex-1 sm:flex-none bg-primary hover:bg-teal-700 text-white px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Tambah Produk</span>
                        <span class="sm:hidden"><i class="fas fa-plus"></i></span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 mb-4 sm:mb-5">
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Produk</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $products->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Nilai Inventori</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ number_format($products->sum('price') ?? 0) }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Stok Menipis</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-red-600">{{ $products->where('stock', '<=', 5)->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Kategori</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">{{ $products->pluck('category_id')->unique()->count() ?? 0 }}</h3>
                </div>
            </div>

            <!-- Products Content -->
            <div class="bg-white border border-gray-300 rounded-2xl p-3 sm:p-4 lg:p-5">
                @if($products && $products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($products as $product)
                    <div class="border border-gray-200 rounded-xl p-3 sm:p-4 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-2 sm:mb-3">
                            <h4 class="font-semibold text-gray-900 text-sm">{{ $product->name ?? 'N/A' }}</h4>
                            <span class="text-xs font-semibold {{ ($product->stock ?? 0) <= 5 ? 'text-red-600' : 'text-green-600' }}">
                                Stok: {{ $product->stock ?? 0 }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mb-2 sm:mb-3">{{ Str::limit($product->description ?? 'No description', 60) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price ?? 0) }}</span>
                            <button class="text-primary hover:text-primary-600 text-xs font-semibold">
                                Edit
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 sm:py-12 lg:py-16">
                    <i class="fas fa-box-open text-gray-300 text-4xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2">Belum ada produk</h3>
                    <p class="text-gray-500 text-xs sm:text-sm lg:text-base">Klik "Tambah Produk" untuk menambahkan produk pertama Anda</p>
                </div>
                @endif
            </div>
        </div>

        <!-- CRM Module -->
        <div id="crm-module" class="module-panel">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start justify-between mb-4 sm:mb-5 gap-3 sm:gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Customer Relationship Management</h2>
                        <!-- Module Toggle Switch -->
                        <button 
                            onclick="toggleModule('crm', this)"
                            class="module-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 bg-primary">
                            <span class="sr-only">Toggle module</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-6"></span>
                        </button>
                    </div>
                    <p class="text-gray-900 text-sm sm:text-base">Kelola hubungan dengan pelanggan</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none border border-gray-300 hover:bg-gray-50 text-gray-900 px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Ekspor</span>
                        <span class="sm:hidden"><i class="fas fa-download"></i></span>
                    </button>
                    <button class="flex-1 sm:flex-none bg-primary hover:bg-teal-700 text-white px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Tambah Pelanggan</span>
                        <span class="sm:hidden"><i class="fas fa-user-plus"></i></span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 mb-4 sm:mb-5">
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Pelanggan</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $customers->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Pelanggan VIP</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600">{{ $customers->where('type', 'vip')->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Revenue</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">Rp {{ number_format($customers->sum('total_spent') ?? 0) }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Pelanggan Baru</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $customers->where('created_at', '>=', now()->subDays(30))->count() ?? 0 }}</h3>
                </div>
            </div>

            <!-- CRM Content -->
            <div class="bg-white border border-gray-300 rounded-2xl p-3 sm:p-4 lg:p-5">
                @if($customers && $customers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($customers as $customer)
                    <div class="border border-gray-200 rounded-xl p-3 sm:p-4 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-2 sm:mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $customer->name ?? 'N/A' }}</h4>
                                <p class="text-gray-600 text-xs">{{ $customer->email ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ ($customer->type ?? 'regular') == 'vip' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($customer->type ?? 'regular') }}
                            </span>
                        </div>
                        <div class="space-y-1 text-xs text-gray-600">
                            <p>Total Order: {{ $customer->total_orders ?? 0 }}</p>
                            <p>Total Belanja: Rp {{ number_format($customer->total_spent ?? 0) }}</p>
                            <p>Terakhir Order: {{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('d M Y') : 'Belum ada' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 sm:py-12 lg:py-16">
                    <i class="fas fa-users text-gray-300 text-4xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2">Belum ada pelanggan</h3>
                    <p class="text-gray-500 text-xs sm:text-sm lg:text-base">Klik "Tambah Pelanggan" untuk menambahkan pelanggan pertama Anda</p>
                </div>
                @endif
            </div>
        </div>

        <!-- E-commerce Module -->
        <div id="ecommerce-module" class="module-panel">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start justify-between mb-4 sm:mb-5 gap-3 sm:gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Template & Orders</h2>
                        <!-- Module Toggle Switch -->
                        <button 
                            onclick="toggleModule('ecommerce', this)"
                            class="module-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 bg-primary">
                            <span class="sr-only">Toggle module</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-6"></span>
                        </button>
                    </div>
                    <p class="text-gray-900 text-sm sm:text-base">Kelola template dan pesanan</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none border border-gray-300 hover:bg-gray-50 text-gray-900 px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150">
                        <span class="hidden sm:inline">Ekspor</span>
                        <span class="sm:hidden"><i class="fas fa-download"></i></span>
                    </button>
                    <a href="{{ route('dashboard.templates') }}" class="flex-1 sm:flex-none bg-primary hover:bg-teal-700 text-white px-3 sm:px-4 lg:px-5 py-2 sm:py-2.5 lg:py-3.5 rounded-2xl font-bold text-sm sm:text-base lg:text-lg transition duration-150 text-center">
                        <span class="hidden sm:inline">Lihat Template</span>
                        <span class="sm:hidden"><i class="fas fa-eye"></i></span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5 mb-4 sm:mb-5">
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Pesanan</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">{{ $orders->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Selesai</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600">{{ $orders->where('status', 'paid')->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Diproses</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600">{{ $orders->where('status', 'pending')->count() ?? 0 }}</h3>
                </div>
                <div class="bg-white border border-gray-300 rounded-xl p-3 sm:p-3.5">
                    <p class="text-gray-900 text-xs sm:text-sm lg:text-base mb-2 sm:mb-3 lg:mb-5">Total Revenue</p>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">Rp {{ number_format($orders->sum('amount') ?? 0) }}</h3>
                </div>
            </div>

            <!-- E-commerce Content -->
            <div class="bg-white border border-gray-300 rounded-2xl p-3 sm:p-4 lg:p-5">
                @if($orders && $orders->count() > 0)
                <div class="space-y-3 sm:space-y-4">
                    @foreach($orders as $order)
                    <div class="border border-gray-200 rounded-xl p-3 sm:p-4 hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2 sm:mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ $order->order_code ?? 'N/A' }}</h4>
                                <p class="text-gray-600 text-xs">{{ $order->template->name ?? 'Template' }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status ?? 'unknown') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600">
                            <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</span>
                            <span class="font-semibold">Rp {{ number_format($order->amount ?? 0) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 sm:py-12 lg:py-16">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 text-xs sm:text-sm lg:text-base">Klik "Lihat Template" untuk melihat dan membeli template</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .module-panel {
        display: none;
    }
    
    .module-panel.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .module-tab {
        color: #009689;
    }
    
    .module-tab.active {
        background: #009689;
        color: #FFFFFF;
        font-weight: 700;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hide scrollbar for tab navigation on mobile */
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }
    .overflow-x-auto {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 640px) {
        .grid-cols-2.sm\:grid-cols-2.lg\:grid-cols-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    /* Toast notification styles */
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out;
    }
    
    .animate-fade-out {
        animation: fadeOut 0.3s ease-in forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard modules script loaded');
    
    // Tab functionality dengan null check
    const tabs = document.querySelectorAll('.module-tab');
    const panels = document.querySelectorAll('.module-panel');
    
    // Pastikan elemen ada
    if (tabs.length === 0) {
        console.error('No module tabs found');
        return;
    }
    
    if (panels.length === 0) {
        console.error('No module panels found');
        return;
    }
    
    // Fungsi untuk aktifkan tab
    function activateTab(tabElement) {
        if (!tabElement) return;
        
        const targetTab = tabElement.getAttribute('data-tab');
        if (!targetTab) return;
        
        console.log('Activating tab:', targetTab);
        
        // Remove active class from all tabs and panels
        tabs.forEach(t => {
            if (t) t.classList.remove('active');
        });
        panels.forEach(p => {
            if (p) p.classList.remove('active');
        });
        
        // Add active class to current tab and panel
        tabElement.classList.add('active');
        const targetPanel = document.getElementById(`${targetTab}-module`);
        if (targetPanel) {
            targetPanel.classList.add('active');
        }
    }
    
    // Add click event to tabs
    tabs.forEach(tab => {
        if (tab) {
            tab.addEventListener('click', function() {
                activateTab(this);
            });
        }
    });
    
    // Set first tab as active by default
    if (tabs[0]) {
        tabs[0].classList.add('active');
    }
    if (panels[0]) {
        panels[0].classList.add('active');
    }
    
    // Module toggle functionality dengan null check yang lebih ketat
    window.toggleModule = function(module, button) {
        console.log('Toggle module called:', module, button);
        
        // Null check untuk button
        if (!button) {
            console.error('Button element is null');
            showToast('Gagal: Element tidak ditemukan', 'error');
            return;
        }
        
        // Pastikan button ada di DOM
        if (!document.contains(button)) {
            console.error('Button not in DOM');
            showToast('Gagal: Element tidak valid', 'error');
            return;
        }
        
        const isEnabled = button.classList.contains('bg-primary');
        console.log('Module is currently enabled:', isEnabled);
        
        const url = isEnabled 
            ? '{{ route("dashboard.modules.disable") }}' 
            : '{{ route("dashboard.modules.enable") }}';
        
        console.log('Request URL:', url);
        
        // Show loading state
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Tambahkan CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            showToast('Gagal: Token keamanan tidak ditemukan', 'error');
            button.innerHTML = originalHTML;
            button.disabled = false;
            return;
        }
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ module: module })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Toggle UI - pastikan button masih ada di DOM
                if (button && document.contains(button)) {
                    // Update button classes
                    if (isEnabled) {
                        // Disable module
                        button.classList.remove('bg-primary');
                        button.classList.add('bg-gray-300');
                        const span = button.querySelector('span:nth-child(2)');
                        if (span) {
                            span.classList.remove('translate-x-6');
                            span.classList.add('translate-x-1');
                        }
                    } else {
                        // Enable module
                        button.classList.remove('bg-gray-300');
                        button.classList.add('bg-primary');
                        const span = button.querySelector('span:nth-child(2)');
                        if (span) {
                            span.classList.remove('translate-x-1');
                            span.classList.add('translate-x-6');
                        }
                    }
                }
                
                // Show success message
                showToast(data.message, 'success');
                
                // Log status
                console.log(`Module ${module} ${isEnabled ? 'disabled' : 'enabled'} successfully`);
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
                console.error('API error:', data);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showToast('Terjadi kesalahan pada server', 'error');
        })
        .finally(() => {
            // Restore button state
            if (button && document.contains(button)) {
                button.disabled = false;
                
                // Perbaiki button HTML berdasarkan status
                if (button.classList.contains('bg-primary')) {
                    // Enabled state
                    const span = document.createElement('span');
                    span.className = 'inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-6';
                    button.innerHTML = '<span class="sr-only">Toggle module</span>';
                    button.appendChild(span);
                } else {
                    // Disabled state
                    const span = document.createElement('span');
                    span.className = 'inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out translate-x-1';
                    button.innerHTML = '<span class="sr-only">Toggle module</span>';
                    button.appendChild(span);
                }
            }
        });
    };
    
    // Toast notification dengan null check
    function showToast(message, type = 'info') {
        if (!message) return;
        
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium z-50 animate-fade-in-up ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 
            'bg-blue-600'
        }`;
        
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${icon} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Pastikan body ada
        if (document.body) {
            document.body.appendChild(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 3000);
        }
    }
    
    // Debug: Log semua module buttons
    const moduleButtons = document.querySelectorAll('.module-toggle');
    console.log('Found module buttons:', moduleButtons.length);
    moduleButtons.forEach((btn, index) => {
        console.log(`Button ${index}:`, btn);
    });
});
</script>
@endpush