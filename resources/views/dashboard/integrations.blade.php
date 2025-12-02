@extends('dashboard.layouts.app')

@section('title', 'Integrasi - Temploka')

@section('content')


                <!-- Integrations List Container -->
                <div class="bg-white rounded-2xl border border-gray-200 p-4 lg:p-6 space-y-4 lg:space-y-6">
                    @forelse($integrations as $integration)
                        <div class="integration-item bg-white border border-gray-200 rounded-xl p-4 lg:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-6 hover:shadow-lg transition-all duration-200">
                            <!-- Logo and Details -->
                            <div class="flex items-center gap-4 lg:gap-6 flex-1">
                                <!-- Logo Placeholder -->
                                <div class="w-16 h-16 lg:w-[75px] lg:h-[75px] bg-white rounded-2xl flex items-center justify-center border border-gray-200 flex-shrink-0">
                                    <i class="{{ $integration->icon ?? 'fas fa-plug' }} text-2xl lg:text-3xl" style="color: {{ $integration->logo_color ?? '#333333' }};"></i>
                                </div>
                                
                                <!-- Text Details -->
                                <div class="flex flex-col gap-2 lg:gap-2.5 flex-1 min-w-0">
                                    <h3 class="text-lg lg:text-xl font-bold text-black line-clamp-1" style="font-family: 'Inter', sans-serif; font-weight: 700;">
                                        {{ $integration->name ?? 'Nama Platform' }}
                                    </h3>
                                    <p class="text-sm lg:text-base text-gray-600 line-clamp-2" style="font-family: 'Inter', sans-serif; font-weight: 400;">
                                        {{ $integration->description ?? 'Deskripsi platform' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status Badge and Actions -->
                            <div class="flex items-center justify-between lg:justify-end gap-3 lg:gap-4 flex-shrink-0">
                                <!-- Status Badge -->
                                @php
                                    $userConnected = $userIntegrations->contains('integration_id', $integration->id);
                                    $connection = $userIntegrations->firstWhere('integration_id', $integration->id);
                                @endphp

                                <div class="flex-shrink-0">
                                    @if($userConnected && $connection->connection_status === 'connected')
                                        <span class="status-badge-active px-3 py-1 lg:px-4 lg:py-2 rounded-md text-xs lg:text-sm font-medium" style="font-family: 'Inter', sans-serif;">
                                            Aktif
                                        </span>
                                    @elseif($userConnected && $connection->connection_status === 'pending')
                                        <span class="status-badge-pending px-3 py-1 lg:px-4 lg:py-2 rounded-md text-xs lg:text-sm font-medium" style="font-family: 'Inter', sans-serif;">
                                            Pending
                                        </span>
                                    @else
                                        <span class="status-badge-disconnected px-3 py-1 lg:px-4 lg:py-2 rounded-md text-xs lg:text-sm font-medium" style="font-family: 'Inter', sans-serif;">
                                            Belum Terhubung
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex-shrink-0">
                                    @if($userConnected && $connection->connection_status === 'connected')
                                        <button class="btn btn-secondary text-xs lg:text-sm px-3 lg:px-4 py-2 lg:py-3">
                                            Putuskan
                                        </button>
                                    @elseif($userConnected && $connection->connection_status === 'pending')
                                        <button class="btn btn-secondary text-xs lg:text-sm px-3 lg:px-4 py-2 lg:py-3">
                                            Batalkan
                                        </button>
                                    @else
                                        <button class="btn btn-primary text-xs lg:text-sm px-3 lg:px-4 py-2 lg:py-3">
                                            Hubungkan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="col-span-full flex flex-col items-center justify-center py-12 lg:py-16">
                            <i class="fas fa-plug text-gray-300 text-5xl lg:text-6xl mb-4"></i>
                            <h3 class="text-lg lg:text-xl font-semibold text-gray-700 mb-2 text-center">Tidak ada integrasi yang tersedia</h3>
                            <p class="text-gray-500 text-center max-w-md text-sm lg:text-base">Integrasi yang Anda cari tidak tersedia. Integrasi baru akan segera hadir.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Recent Activities Section -->
                @if(isset($activities) && $activities->count() > 0)
                <div class="mt-8 lg:mt-12">
                    <h2 class="text-xl lg:text-2xl font-bold text-black mb-4 lg:mb-6" style="font-family: 'Inter', sans-serif;">
                        Aktivitas Terbaru
                    </h2>
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 lg:p-6">
                        <div class="space-y-3 lg:space-y-4">
                            @foreach($activities as $activity)
                            <div class="flex items-center justify-between py-2 lg:py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center gap-3 lg:gap-4">
                                    <div class="w-8 h-8 lg:w-10 lg:h-10 bg-[#009689] rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-link text-white text-sm lg:text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm lg:text-base font-medium text-gray-900">
                                            {{ $activity->integration->name ?? 'Platform' }}
                                        </p>
                                        <p class="text-xs lg:text-sm text-gray-500">
                                            {{ $activity->connection_status === 'connected' ? 'Berhasil terhubung' : 'Status diperbarui' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs lg:text-sm text-gray-500 flex-shrink-0">
                                    {{ $activity->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Import Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Apply Inter font globally within this view */
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Integration Item Card */
    .integration-item {
        transition: all 0.2s ease-in-out;
    }

    .integration-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    /* Status Badge */
    .status-badge-active {
        background-color: rgba(0, 150, 137, 0.25);
        color: #009689;
        border: 1px solid rgba(0, 150, 137, 0.3);
    }

    .status-badge-pending {
        background-color: rgba(251, 191, 36, 0.25);
        color: #d97706;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .status-badge-disconnected {
        background-color: rgba(107, 114, 128, 0.25);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    /* Buttons */
    .btn {
        border-radius: 20px;
        font-weight: 500;
        line-height: 1.2;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .btn-primary {
        background-color: #009689;
        color: #FFFFFF;
    }

    .btn-primary:hover {
        background-color: #007a70;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background-color: #FFFFFF;
        color: #000000;
        border-color: #D9D9D9;
    }

    .btn-secondary:hover {
        background-color: #f3f4f6;
        transform: translateY(-1px);
    }

    /* Line clamp utility */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .integration-item {
            padding: 16px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }
    }

    /* Tablet optimizations */
    @media (max-width: 1024px) and (min-width: 769px) {
        .integration-item {
            padding: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Connect button functionality
        const connectButtons = Array.from(document.querySelectorAll('.btn-primary')).filter(button => button.textContent.includes('Hubungkan'));
        connectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.integration-item');
                const platform = card.querySelector('h3').textContent;
                
                if (confirm(`Anda akan menghubungkan ${platform}. Lanjutkan?`)) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghubungkan...';
                    this.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        alert(`${platform} berhasil terhubung!`);
                        // In a real app, you would reload the page or update the DOM via an API call
                        location.reload();
                    }, 2000);
                }
            });
        });

        // Disconnect button functionality
        const disconnectButtons = Array.from(document.querySelectorAll('.btn-secondary')).filter(button => 
            button.textContent.includes('Putuskan') || button.textContent.includes('Batalkan')
        );
        disconnectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.integration-item');
                const platform = card.querySelector('h3').textContent;
                const action = this.textContent.includes('Putuskan') ? 'memutuskan koneksi' : 'membatalkan';
                
                if (confirm(`Anda akan ${action} ${platform}. Lanjutkan?`)) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                    this.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        alert(`${action === 'memutuskan koneksi' ? 'Koneksi' : 'Permintaan'} ${platform} berhasil ${action === 'memutuskan koneksi' ? 'diputuskan' : 'dibatalkan'}!`);
                        // In a real app, you would reload the page or update the DOM via an API call
                        location.reload();
                    }, 1500);
                }
            });
        });

        // Add hover effects for better mobile experience
        const integrationItems = document.querySelectorAll('.integration-item');
        integrationItems.forEach(item => {
            item.addEventListener('touchstart', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
            });
            
            item.addEventListener('touchend', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    });
</script>
@endpush