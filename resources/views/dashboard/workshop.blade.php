@extends('dashboard.layouts.app')

@section('title', 'Workshop & E-Learning - Temploka')

@section('content')

            </div>

            <!-- Content Area -->
            <div class="flex-1 bg-[#F9FAFB] p-6 lg:p-8">
                <!-- Tab Navigation -->
                <div class="mb-8">
                    <div class="bg-[rgba(0,150,137,0.1)] rounded-[20px] p-2 w-full max-w-4xl mx-auto">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button class="workshop-tab px-4 sm:px-6 py-3 sm:py-4 rounded-[20px] bg-[#009689] text-white font-bold text-base sm:text-[18px] transition-all duration-200 flex-1 text-center" 
                                    style="font-family: 'Inter', sans-serif;" 
                                    data-tab="webinar">
                                Webinar Live
                            </button>
                            <button class="workshop-tab px-4 sm:px-6 py-3 sm:py-4 rounded-[20px] text-[#009689] font-medium text-base sm:text-[18px] transition-all duration-200 flex-1 text-center" 
                                    style="font-family: 'Inter', sans-serif;" 
                                    data-tab="tutorial">
                                Tutorial Rekaman
                            </button>
                            <button class="workshop-tab px-4 sm:px-6 py-3 sm:py-4 rounded-[20px] text-[#009689] font-medium text-base sm:text-[18px] transition-all duration-200 flex-1 text-center" 
                                    style="font-family: 'Inter', sans-serif;" 
                                    data-tab="certificate">
                                Sertifikat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="workshop-content">
                    <!-- Webinar Live Tab -->
                    <div id="webinar-workshop" class="workshop-panel active">
                        <!-- Section Header -->
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-8 gap-4">
                            <div class="flex-1">
                                <h2 class="text-2xl lg:text-[29px] font-bold text-black mb-2" style="font-family: 'Inter', sans-serif;">
                                    Webinar Live & Mendatang
                                </h2>
                                <p class="text-base text-black" style="font-family: 'Inter', sans-serif;">
                                    Ikuti sesi langsung dengan para ahli
                                </p>
                            </div>
                            <div class="flex items-center gap-2.5 px-4 py-3 bg-[rgba(0,150,137,0.25)] border border-gray-200 rounded-[20px] w-fit">
                                <span class="w-2.5 h-2 bg-[#00C614] rounded-full"></span>
                                <span class="text-base lg:text-[18px] font-medium text-[#009689]" style="font-family: 'Inter', sans-serif;">
                                    {{ ($liveWebinars && $liveWebinars->count()) ? $liveWebinars->count() : 0 }} Live Sekarang
                                </span>
                            </div>
                        </div>

                        <!-- Webinar Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                            @forelse($liveWebinars ?? [] as $webinar)
                                <div class="webinar-card bg-white rounded-[20px] overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                                    <!-- Webinar Image -->
                                    <div class="relative w-full h-48 lg:h-52">
                                        @if($webinar->image_url)
                                            <img src="{{ $webinar->image_url }}" alt="{{ $webinar->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                                <i class="fas fa-video text-blue-500 text-3xl"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Live Badge -->
                                        @if($webinar->status == 'live')
                                        <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-1 bg-[#EE4D2D] rounded-[5px]">
                                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                            <span class="text-white text-sm font-semibold" style="font-family: 'Inter', sans-serif;">LIVE</span>
                                        </div>
                                        @endif

                                        <!-- Popular Badge -->
                                        <div class="absolute top-4 left-4 flex items-center gap-2 px-3 py-1 bg-black/75 rounded-[5px]">
                                            <i class="fas fa-eye text-white text-xs"></i>
                                            <span class="text-white text-sm font-semibold" style="font-family: 'Inter', sans-serif;">Populer</span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5 lg:p-6">
                                        <div class="mb-4">
                                            <h3 class="text-lg lg:text-[18px] font-semibold text-black mb-2 line-clamp-2" style="font-family: 'Inter', sans-serif;">
                                                {{ $webinar->name }}
                                            </h3>
                                            <p class="text-base text-black mb-1" style="font-family: 'Inter', sans-serif;">
                                                {{ $webinar->instructor_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($webinar->start_date)->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        
                                        <button class="w-full bg-[#009689] hover:bg-[#007a6e] text-white py-3 lg:py-4 px-4 rounded-[20px] font-medium text-base lg:text-[18px] transition-colors duration-200" 
                                                style="font-family: 'Inter', sans-serif;">
                                            {{ $webinar->button_text ?? ($webinar->status == 'live' ? 'Ikuti Live' : 'Daftar Sekarang') }}
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <!-- Empty State -->
                                <div class="col-span-full flex flex-col items-center justify-center py-16">
                                    <i class="fas fa-video text-gray-300 text-5xl lg:text-6xl mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-700 mb-2 text-center">Tidak ada webinar yang dijadwalkan</h3>
                                    <p class="text-gray-500 text-center max-w-md">Webinar akan segera dijadwalkan. Silakan periksa kembali nanti.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Upcoming Webinars Section -->
                        @if($upcomingWebinars && $upcomingWebinars->count() > 0)
                        <div class="mt-12">
                            <h2 class="text-2xl lg:text-[29px] font-bold text-black mb-6" style="font-family: 'Inter', sans-serif;">
                                Webinar Mendatang
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach($upcomingWebinars as $webinar)
                                <div class="webinar-card bg-white rounded-[20px] overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                                    <!-- Webinar Image -->
                                    <div class="relative w-full h-48 lg:h-52">
                                        @if($webinar->image_url)
                                            <img src="{{ $webinar->image_url }}" alt="{{ $webinar->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center">
                                                <i class="fas fa-calendar-alt text-purple-500 text-3xl"></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Duration Badge -->
                                        <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-1 bg-black/75 rounded-[5px]">
                                            <i class="fas fa-clock text-white text-xs"></i>
                                            <span class="text-white text-sm font-semibold" style="font-family: 'Inter', sans-serif;">45:30</span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5 lg:p-6">
                                        <div class="mb-4">
                                            <h3 class="text-lg lg:text-[18px] font-semibold text-black mb-2 line-clamp-2" style="font-family: 'Inter', sans-serif;">
                                                {{ $webinar->name }}
                                            </h3>
                                            <p class="text-base text-black mb-1" style="font-family: 'Inter', sans-serif;">
                                                {{ $webinar->instructor_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($webinar->start_date)->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        
                                        <button class="w-full border border-[#009689] text-[#009689] hover:bg-[#009689] hover:text-white py-3 lg:py-4 px-4 rounded-[20px] font-medium text-base lg:text-[18px] transition-all duration-200" 
                                                style="font-family: 'Inter', sans-serif;">
                                            {{ $webinar->button_text ?? 'Daftar Sekarang' }}
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tutorial Rekaman Tab -->
                    <div id="tutorial-workshop" class="workshop-panel">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:p-8 shadow-card">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Tutorial Rekaman</h2>
                                    <p class="text-gray-600 text-sm lg:text-base">Akses materi pembelajaran kapan saja</p>
                                </div>
                            </div>
                            <div class="text-center py-12">
                                <i class="fas fa-play-circle text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Tutorial akan segera tersedia</h3>
                                <p class="text-gray-500 max-w-md mx-auto">Kami sedang menyiapkan materi tutorial terbaik untuk Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sertifikat Tab -->
                    <div id="certificate-workshop" class="workshop-panel">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:p-8 shadow-card">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
                                <div>
                                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Sertifikat Saya</h2>
                                    <p class="text-gray-600 text-sm lg:text-base">Sertifikat yang telah Anda dapatkan</p>
                                </div>
                            </div>
                            @if($certificates && $certificates->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($certificates as $certificate)
                                    <div class="border border-gray-200 rounded-lg p-4 lg:p-6 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <i class="fas fa-award text-[#009689] text-2xl"></i>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($certificate->issued_at)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 mb-2">{{ $certificate->title }}</h3>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $certificate->description }}</p>
                                        <button class="w-full mt-4 bg-[#009689] text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-[#007a6e] transition-colors duration-200">
                                            Unduh Sertifikat
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-award text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada sertifikat</h3>
                                    <p class="text-gray-500 max-w-md mx-auto">Sertifikat akan tersedia setelah Anda menyelesaikan webinar atau tutorial.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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

    /* Hide other panels */
    .workshop-panel {
        display: none;
    }
    
    .workshop-panel.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Custom shadow */
    .shadow-card {
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Animation */
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

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .webinar-card {
            margin-bottom: 1rem;
        }
        
        .workshop-tab {
            padding: 12px 16px;
            font-size: 14px;
        }
    }

    /* Tablet optimizations */
    @media (max-width: 1024px) and (min-width: 769px) {
        .webinar-card {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabs = document.querySelectorAll('.workshop-tab');
        const panels = document.querySelectorAll('.workshop-panel');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and panels
                tabs.forEach(t => {
                    t.classList.remove('bg-[#009689]', 'text-white', 'font-bold');
                    t.classList.add('text-[#009689]', 'font-medium');
                });
                panels.forEach(p => p.classList.remove('active'));
                
                // Add active class to current tab and panel
                this.classList.remove('text-[#009689]', 'font-medium');
                this.classList.add('bg-[#009689]', 'text-white', 'font-bold');
                
                const targetPanel = document.getElementById(`${targetTab}-workshop`);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                }
            });
        });

        // Mobile menu toggle (if needed)
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
@endpush