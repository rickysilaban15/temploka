{{-- resources/views/dashboard/index.blade.php --}}
@extends('dashboard.layouts.app')

@section('title', 'Dashboard - Temploka')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Penjualan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-primary text-lg"></i>
                </div>
                @if($salesGrowth > 0)
                <div class="text-green-600 flex items-center">
                    <i class="fas fa-arrow-trend-up text-sm mr-1"></i>
                    <span class="text-sm font-semibold">+{{ number_format($salesGrowth, 1) }}%</span>
                </div>
                @elseif($salesGrowth < 0)
                <div class="text-red-600 flex items-center">
                    <i class="fas fa-arrow-trend-down text-sm mr-1"></i>
                    <span class="text-sm font-semibold">{{ number_format($salesGrowth, 1) }}%</span>
                </div>
                @else
                <div class="text-gray-600 flex items-center">
                    <span class="text-sm font-semibold">0%</span>
                </div>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-gray-600 text-sm">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_sales']) }}</h3>
                <p class="text-gray-500 text-sm font-medium">
                    {{ $stats['total_sales'] > 0 ? 'Total semua penjualan' : 'Belum ada penjualan' }}
                </p>
            </div>
        </div>

        <!-- Transaksi Bulan Ini -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cart-shopping text-blue-600 text-lg"></i>
                </div>
                @if($transactionGrowth > 0)
                <div class="text-green-600 flex items-center">
                    <i class="fas fa-arrow-trend-up text-sm mr-1"></i>
                    <span class="text-sm font-semibold">+{{ number_format($transactionGrowth, 1) }}%</span>
                </div>
                @elseif($transactionGrowth < 0)
                <div class="text-red-600 flex items-center">
                    <i class="fas fa-arrow-trend-down text-sm mr-1"></i>
                    <span class="text-sm font-semibold">{{ number_format($transactionGrowth, 1) }}%</span>
                </div>
                @else
                <div class="text-gray-600 flex items-center">
                    <span class="text-sm font-semibold">0%</span>
                </div>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-gray-600 text-sm">Transaksi Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['monthly_transactions']) }}</h3>
                <p class="text-gray-500 text-sm font-medium">
                    {{ $stats['monthly_transactions'] > 0 ? 'Transaksi bulan ' . now()->translatedFormat('F') : 'Belum ada transaksi' }}
                </p>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box-open text-yellow-600 text-lg"></i>
                </div>
                @if($stats['low_stock'] > 0)
                <div class="text-red-600 flex items-center">
                    <span class="text-sm font-semibold">Perlu restock</span>
                </div>
                @else
                <div class="text-green-600 flex items-center">
                    <span class="text-sm font-semibold">Stok aman</span>
                </div>
                @endif
            </div>
            <div class="space-y-1">
                <p class="text-gray-600 text-sm">Stok Menipis</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['low_stock'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">
                    {{ $stats['low_stock'] > 0 ? 'Produk perlu restock' : 'Semua stok aman' }}
                </p>
            </div>
        </div>

        <!-- Modul Aktif -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cube text-purple-600 text-lg"></i>
                </div>
                <div class="text-green-600 flex items-center">
                    <span class="text-sm font-semibold">{{ $stats['active_modules'] }} aktif</span>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-gray-600 text-sm">Modul Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_modules'] }}</h3>
                <p class="text-gray-500 text-sm font-medium">Akses cepat ke modul</p>
            </div>
        </div>
    </div>

    <!-- Charts and Modules Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Performa Penjualan</h3>
                    <p class="text-gray-600 text-sm">12 bulan terakhir</p>
                </div>
                @if($chartData['growth'] > 0)
                <div class="text-green-600 flex items-center">
                    <i class="fas fa-arrow-trend-up text-sm mr-1"></i>
                    <span class="text-sm font-semibold">+{{ $chartData['growth'] }}%</span>
                </div>
                @else
                <div class="text-gray-600 flex items-center">
                    <span class="text-sm font-semibold">{{ $chartData['growth'] }}%</span>
                </div>
                @endif
            </div>
            
            <!-- Chart Container -->
            <div class="h-64 relative">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Quick Access Modules -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900">Modul Aktif</h3>
                <p class="text-gray-600 text-sm">Akses cepat ke modul yang sering digunakan</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($quickAccess ?? [] as $module)
                <a href="{{ route('dashboard.modules') }}" class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200 hover:translate-y-[-2px] cursor-pointer group block">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 {{ $module['bg_color'] }} rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="{{ $module['icon'] }} {{ $module['icon_color'] }} text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $module['title'] }}</h4>
                            <p class="text-gray-600 text-xs truncate">{{ $module['description'] }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing sales chart...');
    
    // Pastikan Chart.js sudah terload
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded!');
        document.getElementById('salesChart').parentElement.innerHTML = `
            <div class="flex items-center justify-center h-full text-center">
                <div>
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-2"></i>
                    <p class="text-gray-600">Chart.js belum terload</p>
                    <p class="text-gray-400 text-sm">Refresh halaman atau gunakan browser lain</p>
                </div>
            </div>
        `;
        return;
    }
    
    // Chart Data dari Controller
    const chartData = @json($chartData);
    
    console.log('Chart data loaded:', chartData);
    
    try {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: chartData.data,
                    borderColor: '#009689',
                    backgroundColor: 'rgba(0, 150, 137, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#009689',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'JT';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        
        console.log('Sales chart initialized successfully!');
        
    } catch (error) {
        console.error('Chart error:', error);
    }
});
</script>
@endpush