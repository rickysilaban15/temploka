@extends('layouts.app')

@section('title', 'Harga - TEMPLOKA')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-[#EEFDFC] to-[#EEFDFC]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px] py-12 sm:py-16 lg:py-[100px] text-center">
        <h1 class="font-black text-3xl sm:text-4xl lg:text-[47.12px] leading-tight lg:leading-[57px] text-black mb-4 lg:mb-5">
            Pilih Paket Yang Sesuai Untuk Anda
        </h1>
        <p class="font-normal text-base sm:text-lg lg:text-xl leading-relaxed lg:leading-[24px] text-[#333333] max-w-3xl mx-auto px-4">
            Mulai gratis tanpa kartu kredit. Upgrade kapan saja untuk fitur lebih lengkap.
        </p>
    </div>
</section>

<!-- Pricing Cards Section -->
<section class="bg-white py-12 sm:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px]">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 max-w-7xl mx-auto">
            
            @foreach($plans as $plan)
            <!-- Pricing Card -->
            <div class="w-full bg-white rounded-[20px] overflow-hidden transition-all duration-300 {{ $plan->is_featured ? 'ring-4 ring-primary-500 md:transform md:scale-105' : 'border border-[#E3E3E3]' }}" style="box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);">
                
                @if($plan->is_featured)
                <div class="bg-primary-500 text-white text-center py-3 font-bold text-xs sm:text-sm">
                    🔥 PALING POPULER
                </div>
                @endif
                
                <div class="p-6 sm:p-8 lg:p-10">
                    <!-- Plan Header -->
                    <div class="text-center mb-6 sm:mb-8">
                        <h3 class="font-bold text-2xl sm:text-[29.12px] leading-tight sm:leading-[35px] text-black mb-2 sm:mb-3">
                            {{ $plan->name }}
                        </h3>
                        <p class="font-normal text-sm sm:text-base text-[#333333] mb-4 sm:mb-6">
                            {{ $plan->description }}
                        </p>
                        
                        <!-- Price -->
                        <div class="mb-4 sm:mb-6">
                            @if($plan->price == 0)
                                <div class="font-black text-4xl sm:text-5xl text-primary-500">Gratis</div>
                                <div class="text-xs sm:text-sm text-gray-500 mt-2">Selamanya</div>
                            @else
                                <div class="flex items-baseline justify-center gap-2 flex-wrap">
                                    <span class="font-black text-4xl sm:text-5xl text-primary-500">
                                        Rp {{ number_format($plan->price, 0, ',', '.') }}
                                    </span>
                                    @if($plan->discount_percentage > 0)
                                    <span class="text-sm text-gray-500 line-through">
                                        Rp {{ number_format($plan->price / (1 - ($plan->discount_percentage / 100)), 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                        Hemat {{ $plan->discount_percentage }}%
                                    </span>
                                    @endif
                                </div>
                                <div class="text-xs sm:text-sm text-gray-500 mt-2">
                                    per bulan
                                </div>
                            @endif
                        </div>
                        
                        <!-- CTA Button -->
                        @if($plan->is_featured)
                        <a href="{{ route('pricing.templates', $plan->id) }}" class="btn-primary w-full block text-center text-sm sm:text-base py-3 sm:py-4">
                            {{ auth()->check() ? 'Lihat Template di Dashboard' : 'Lihat Template Sesuai' }}
                        </a>
                        @else
                        <a href="{{ route('pricing.templates', $plan->id) }}" class="btn-outline w-full block text-center text-sm sm:text-base py-3 sm:py-4">
                            {{ $plan->price == 0 ? 
                                (auth()->check() ? 'Lihat Template Gratis' : 'Lihat Template Gratis') : 
                                (auth()->check() ? 'Lihat Template di Dashboard' : 'Lihat Template Sesuai') 
                            }}
                        </a>
                        @endif
                    </div>
                    
                    <!-- Features List -->
                    <div class="space-y-3 sm:space-y-4 pt-4 sm:pt-6 border-t border-gray-200">
                        <p class="font-semibold text-xs sm:text-sm text-gray-700 mb-3 sm:mb-4">Fitur yang termasuk:</p>
                        @foreach($plan->features as $feature)
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700 leading-relaxed">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
</section>

<!-- New Section: Template Recommendations -->
<section class="bg-gray-50 py-12 sm:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px]">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="font-bold text-2xl sm:text-[29.12px] leading-tight sm:leading-[35px] text-black mb-3 sm:mb-4">
                Template Sesuai Budget Anda
            </h2>
            <p class="font-normal text-sm sm:text-base text-[#333333] px-4">
                Pilih paket, dan temukan template yang sesuai dengan anggaran Anda
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <!-- Free Templates -->
            <div class="bg-white rounded-[20px] p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🎁</span>
                </div>
                <h3 class="font-bold text-lg text-black mb-2">Template Gratis</h3>
                <p class="text-sm text-gray-600 mb-4">Template berkualitas tanpa biaya</p>
                <a href="{{ route('pricing.templates', $plans->where('price', 0)->first()->id ?? 1) }}" class="btn-outline w-full text-sm py-2">
                    Lihat Template Gratis
                </a>
            </div>

            <!-- Premium Templates -->
            <div class="bg-white rounded-[20px] p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">⭐</span>
                </div>
                <h3 class="font-bold text-lg text-black mb-2">Template Premium</h3>
                <p class="text-sm text-gray-600 mb-4">Template dengan fitur lengkap</p>
                <a href="{{ route('pricing.templates', $plans->where('price', '>', 0)->first()->id ?? 2) }}" class="btn-outline w-full text-sm py-2">
                    Lihat Template Premium
                </a>
            </div>

            <!-- All Templates -->
            <div class="bg-white rounded-[20px] p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">📦</span>
                </div>
                <h3 class="font-bold text-lg text-black mb-2">Semua Template</h3>
                <p class="text-sm text-gray-600 mb-4">Jelajahi semua template kami</p>
                <a href="{{ route('templates.index') }}" class="btn-primary w-full text-sm py-2">
                    Jelajahi Semua
                </a>
            </div>
        </div>
    </div>
</section>


<!-- Comparison Table Section -->
<section class="bg-[#EEFDFC] py-12 sm:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px]">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="font-bold text-2xl sm:text-[29.12px] leading-tight sm:leading-[35px] text-black mb-3 sm:mb-4">
                Bandingkan Semua Fitur
            </h2>
            <p class="font-normal text-sm sm:text-base text-[#333333] px-4">
                Lihat detail lengkap perbandingan fitur dari setiap paket
            </p>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white rounded-[20px] overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 lg:px-8 py-4 lg:py-6 text-left font-semibold text-base lg:text-lg text-black w-1/3">Fitur</th>
                            @foreach($plans as $plan)
                            <th class="px-6 lg:px-8 py-4 lg:py-6 text-center font-semibold text-base lg:text-lg text-black whitespace-nowrap {{ $plan->is_featured ? 'bg-primary-50 border-b-2 border-primary-500' : '' }}">
                                {{ $plan->name }}
                                @if($plan->is_featured)
                                <div class="text-xs font-normal text-primary-600 mt-1">POPULER</div>
                                @endif
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Template Limit -->
                        <tr>
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Jumlah Template</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                <span class="font-bold text-lg text-black">
                                    {{ $plan->template_limit ?? 'Unlimited' }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Storage -->
                        <tr class="bg-gray-50">
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Storage</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                <span class="font-bold text-black text-lg">
                                    {{ $plan->storage_gb ? ($plan->storage_gb == 999 ? 'Unlimited' : $plan->storage_gb . ' GB') : '1 GB' }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Priority Support -->
                        <tr>
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                    </svg>
                                    <span>Priority Support</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                @if($plan->priority_support)
                                <div class="inline-flex items-center gap-1 text-green-600 font-semibold">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Ya</span>
                                </div>
                                @else
                                <div class="inline-flex items-center gap-1 text-gray-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Tidak</span>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Custom Domain -->
                        <tr class="bg-gray-50">
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Custom Domain</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                @if($plan->custom_domain)
                                <div class="inline-flex items-center gap-1 text-green-600 font-semibold">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Ya</span>
                                </div>
                                @else
                                <div class="inline-flex items-center gap-1 text-gray-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Tidak</span>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- White Label -->
                        <tr>
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>White Label</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                @if($plan->white_label)
                                <div class="inline-flex items-center gap-1 text-green-600 font-semibold">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Ya</span>
                                </div>
                                @else
                                <div class="inline-flex items-center gap-1 text-gray-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Tidak</span>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- API Access -->
                        <tr class="bg-gray-50">
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>API Access</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                @if($plan->api_access)
                                <div class="inline-flex items-center gap-1 text-green-600 font-semibold">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Ya</span>
                                </div>
                                @else
                                <div class="inline-flex items-center gap-1 text-gray-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Tidak</span>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Billing Period -->
                        <tr>
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Billing Period</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                <span class="font-semibold text-black capitalize">
                                    {{ $plan->billing_period }}
                                </span>
                            </td>
                            @endforeach
                        </tr>
                        
                        <!-- Discount -->
                        <tr class="bg-gray-50">
                            <td class="px-6 lg:px-8 py-4 lg:py-5 font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Diskon</span>
                                </div>
                            </td>
                            @foreach($plans as $plan)
                            <td class="px-6 lg:px-8 py-4 lg:py-5 text-center {{ $plan->is_featured ? 'bg-primary-50' : '' }}">
                                @if($plan->discount_percentage > 0)
                                <span class="font-bold text-red-600 text-lg">
                                    {{ $plan->discount_percentage }}%
                                </span>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        
                        
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Comparison Cards -->
        <div class="lg:hidden space-y-6">
            @foreach($plans as $plan)
            <div class="bg-white rounded-[20px] overflow-hidden shadow-lg {{ $plan->is_featured ? 'ring-2 ring-primary-500' : '' }}">
                @if($plan->is_featured)
                <div class="bg-primary-500 text-white text-center py-3 font-bold text-sm">
                    🔥 PALING POPULER
                </div>
                @endif
                
                <div class="p-6">
                    <div class="text-center mb-6 pb-6 border-b border-gray-200">
                        <h3 class="font-bold text-xl text-black mb-2">{{ $plan->name }}</h3>
                        @if($plan->price == 0)
                            <div class="font-black text-3xl text-primary-500">Gratis</div>
                            <div class="text-sm text-gray-500">Selamanya</div>
                        @else
                            <div class="font-black text-3xl text-primary-500">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500 capitalize">per {{ $plan->billing_period }}</div>
                            @if($plan->discount_percentage > 0)
                            <div class="text-sm bg-red-100 text-red-800 px-3 py-1 rounded-full inline-block mt-2">
                                Hemat {{ $plan->discount_percentage }}%
                            </div>
                            @endif
                        @endif
                        
                        @if($plan->is_featured)
                        <a href="{{ route('pricing.templates', $plan->id) }}" 
                           class="btn-primary w-full mt-4 py-3 text-sm font-bold">
                            {{ auth()->check() ? 'Lihat Template di Dashboard' : 'Lihat Template Sesuai' }}
                        </a>
                        @else
                        <a href="{{ route('pricing.templates', $plan->id) }}" 
                           class="btn-outline w-full mt-4 py-3 text-sm font-bold">
                            {{ $plan->price == 0 ? 
                                (auth()->check() ? 'Lihat Template Gratis' : 'Lihat Template Gratis') : 
                                (auth()->check() ? 'Lihat Template di Dashboard' : 'Lihat Template Sesuai') 
                            }}
                        </a>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        @php
                        $features = [
                            'Jumlah Template' => $plan->template_limit ?? 'Unlimited',
                            'Storage' => $plan->storage_gb ? ($plan->storage_gb == 999 ? 'Unlimited' : $plan->storage_gb . ' GB') : '1 GB',
                            'Priority Support' => $plan->priority_support ? '✅' : '❌',
                            'Custom Domain' => $plan->custom_domain ? '✅' : '❌',
                            'White Label' => $plan->white_label ? '✅' : '❌',
                            'API Access' => $plan->api_access ? '✅' : '❌',
                            'Billing Period' => ucfirst($plan->billing_period),
                            'Diskon' => $plan->discount_percentage > 0 ? $plan->discount_percentage . '%' : '-',
                        ];
                        @endphp
                        
                        @foreach($features as $label => $value)
                        <div class="flex justify-between items-center py-2 {{ $loop->even ? 'bg-gray-50 -mx-6 px-6' : '' }}">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-semibold text-black">
                                @if($value === '✅')
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($value === '❌')
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    {{ $value }}
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Note -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                * Semua paket termasuk dukungan teknis dan pembaruan reguler
            </p>
            <p class="text-sm text-gray-600 mt-2">
                * Harga belum termasuk PPN 11% (jika berlaku)
            </p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-white py-12 sm:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px] max-w-4xl">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="font-bold text-2xl sm:text-[29.12px] leading-tight sm:leading-[35px] text-black mb-3 sm:mb-4">
                Pertanyaan Seputar Harga
            </h2>
            <p class="font-normal text-sm sm:text-base text-[#333333] px-4">
                Jawaban untuk pertanyaan yang sering ditanyakan
            </p>
        </div>
        
        <div class="space-y-4">
            @php
            $pricingFaqs = [
                [
                    'q' => 'Bagaimana cara memilih template yang sesuai dengan paket saya?', 
                    'a' => 'Pilih paket yang sesuai dengan budget Anda, lalu klik "Lihat Template Sesuai" untuk melihat template yang tersedia untuk paket tersebut.'
                ],
                [
                    'q' => 'Apakah saya bisa upgrade atau downgrade paket kapan saja?', 
                    'a' => 'Ya, Anda bisa mengubah paket kapan saja. Upgrade akan berlaku segera, downgrade akan berlaku di akhir periode billing.'
                ],
                [
                    'q' => 'Apakah ada biaya tersembunyi?', 
                    'a' => 'Tidak ada biaya tersembunyi. Harga yang tertera sudah final dan transparan.'
                ],
                [
                    'q' => 'Bagaimana cara pembayaran?', 
                    'a' => 'Kami menerima pembayaran via Transfer Bank, E-wallet, dan Kartu Kredit.'
                ],
                [
                    'q' => 'Apakah ada garansi uang kembali?', 
                    'a' => 'Ya, kami memberikan garansi 30 hari uang kembali tanpa pertanyaan.'
                ],
            ];
            @endphp
            
            @foreach($pricingFaqs as $index => $faq)
            <div class="bg-white border border-[#E3E3E3] rounded-[20px] overflow-hidden transition-all duration-300 faq-item group">
                <button class="w-full flex justify-between items-center p-6 text-left faq-trigger hover:bg-gray-50 transition-colors group-hover:border-primary-200">
                    <span class="font-semibold text-base text-black pr-4 text-left flex-1">{{ $faq['q'] }}</span>
                    <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300 faq-icon flex-shrink-0 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-content hidden transition-all duration-300">
                    <div class="px-6 pb-6 pt-2 border-t border-gray-200">
                        <p class="font-normal text-sm text-gray-700 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-primary-500 text-white py-12 sm:py-16 lg:py-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-[100px] text-center">
        <h2 class="font-black text-2xl sm:text-3xl lg:text-[37px] leading-tight lg:leading-[45px] mb-4 sm:mb-6">
            Siap Memulai?
        </h2>
        <p class="text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 max-w-2xl mx-auto px-4">
            Pilih paket dan temukan template yang sempurna untuk bisnis Anda
        </p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
            <a href="{{ route('templates.index') }}" class="bg-white text-primary-500 font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-[20px] hover:bg-gray-100 transition-all text-sm sm:text-base">
                🚀 Jelajahi Semua Template
            </a>
            <a href="{{ route('register') }}" class="border-2 border-white text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-[20px] hover:bg-white hover:text-primary-500 transition-all text-sm sm:text-base">
                📝 Daftar Sekarang
            </a>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.btn-primary {
    background-color: #00B3A8;
    color: white;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-primary:hover {
    background-color: #009C91;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 179, 168, 0.3);
}

.btn-outline {
    border: 2px solid #00B3A8;
    color: #00B3A8;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-outline:hover {
    background-color: #00B3A8;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 179, 168, 0.3);
}

.shadow-card {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

/* FAQ Styling */
.faq-item {
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.faq-content {
    overflow: hidden;
}

.faq-icon {
    transition: transform 0.3s ease;
}

.faq-icon.rotate-180 {
    transform: rotate(180deg);
}
</style>
@endpush

@push('scripts')
<script>
// FAQ functionality
(function(){
  const DEBUG = true;
  function log(...args){ if(DEBUG) console.log('[FAQ]', ...args); }

  function initFaqs(){
    log('initFaqs start');
    const triggers = document.querySelectorAll('.faq-trigger');

    if(!triggers.length){
      log('Tidak menemukan .faq-trigger di DOM — pastikan HTML sudah dimuat');
      return;
    }

    function openContent(content){
      content.style.display = 'block';
      const height = content.scrollHeight + 'px';
      content.style.height = '0px';
      void content.offsetHeight;
      content.style.transition = 'height 260ms ease';
      content.style.height = height;
      function done(){
        content.style.height = '';
        content.style.transition = '';
        content.removeEventListener('transitionend', done);
      }
      content.addEventListener('transitionend', done);
      content.classList.remove('hidden');
    }

    function closeContent(content){
      const height = content.scrollHeight + 'px';
      content.style.height = height;
      void content.offsetHeight;
      content.style.transition = 'height 260ms ease';
      content.style.height = '0px';
      function done(){
        content.style.display = 'none';
        content.style.height = '';
        content.style.transition = '';
        content.classList.add('hidden');
        content.removeEventListener('transitionend', done);
      }
      content.addEventListener('transitionend', done);
    }

    triggers.forEach(btn => {
      btn.style.cursor = 'pointer';
      btn.addEventListener('click', (e) => {
        const item = btn.closest('.faq-item');
        if(!item) return log('faq-trigger punya struktur DOM yang tidak terduga');

        const content = item.querySelector('.faq-content');
        const icon = item.querySelector('.faq-icon');

        if(!content) return log('Tidak menemukan .faq-content pada faq-item');
        
        document.querySelectorAll('.faq-item').forEach(other => {
          if(other === item) return;
          const otherContent = other.querySelector('.faq-content');
          const otherIcon = other.querySelector('.faq-icon');
          if(!otherContent) return;
          if(!otherContent.classList.contains('hidden')){
            closeContent(otherContent);
            if(otherIcon) otherIcon.classList.remove('rotate-180');
          }
        });

        if(content.classList.contains('hidden')){
          openContent(content);
          if(icon) icon.classList.add('rotate-180');
        } else {
          closeContent(content);
          if(icon) icon.classList.remove('rotate-180');
        }
      });
    });

    log('initFaqs done, triggers:', triggers.length);
  }

  try { initFaqs(); } catch(err){ console.error(err); }
  document.addEventListener('DOMContentLoaded', () => {
    try { initFaqs(); } catch(err){ console.error(err); }
  });
})();
</script>
@endpush