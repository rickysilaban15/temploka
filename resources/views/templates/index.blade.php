@extends('layouts.app')

@section('title', 'TEMPLOKA - Transformasi Digital Usaha Anda')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary-50 to-primary-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-8 lg:gap-24">
            <!-- Hero Text -->
            <div class="flex-1 space-y-6 text-center lg:text-left">
                <h1 class="font-black text-3xl sm:text-4xl lg:text-5xl lg:leading-[57px] text-gray-900">
                    Transformasi Digital Usaha Anda Dalam Sekejap
                </h1>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Gunakan template siap pakai untuk mengelola keuangan, produk, dan pelanggan tanpa ribet.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('templates.index') }}" class="btn-primary">
                        Mulai Sekarang
                    </a>
                    <a href="#template" class="btn-outline">
                        Lihat Template
                    </a>
                </div>
            </div>
            
          <!-- Hero Image -->
<div class="flex-1 flex justify-center">
    <div class="w-full max-w-md lg:max-w-lg">
        <img 
            src="{{ asset('images/hero.jpg') }}" 
            alt="Hero Illustration" 
            class="w-full h-auto rounded-2xl shadow-soft object-cover"
        >
    </div>
</div>

            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
           @php
$features = [
    [
        'icon' => '📈', 
        'title' => 'Template Bisnis',
        'description' => 'Ratusan template siap pakai untuk berbagai kebutuhan bisnis Anda'
    ],
    [
        'icon' => '🛍️', 
        'title' => 'Integrasi Marketplace',
        'description' => 'Terhubung langsung dengan marketplace favorit Anda dengan mudah'
    ],
    [
        'icon' => '🎨', 
        'title' => 'Desain No-Code',
        'description' => 'Buat dan kelola tanpa perlu coding atau keahlian teknis khusus'
    ],
];
@endphp

            
            @foreach($features as $feature)
            <div class="feature-card p-8 text-center">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center text-4xl">
                    {{ $feature['icon'] }}
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    {{ $feature['title'] }}
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $feature['description'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Integration Section -->
<section class="bg-primary-50 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                Terhubung dengan Platform Favorit Anda
            </h2>
            <p class="text-lg text-gray-700 max-w-2xl mx-auto">
                Terhubung langsung dengan platform yang Anda gunakan setiap hari.
            </p>
            
            <!-- Platform Icons -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 mt-12">

@php
$platforms = [
    ['name' => 'Tokopedia', 'color' => '#42B549', 'icon' => 'platforms/tokopedia.png'],
    ['name' => 'Shopee', 'color' => '#EE4D2D', 'icon' => 'platforms/shopee.png'],
    ['name' => 'Instagram', 'color' => '#D60096', 'icon' => 'platforms/instagram.png'],
    ['name' => 'TikTok', 'color' => '#000000', 'icon' => 'platforms/tiktok.png'],
    ['name' => 'WhatsApp', 'color' => '#009689', 'icon' => 'platforms/whatsapp.png'],
];
@endphp

@foreach($platforms as $platform)
    <div class="platform-card text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-2xl flex items-center justify-center shadow-soft">
            <img 
                src="{{ asset($platform['icon']) }}" 
                alt="{{ $platform['name'] }} Logo"
                class="w-10 h-10 object-contain"
            >
        </div>

        <span class="text-gray-900 font-medium">
            {{ $platform['name'] }}
        </span>
    </div>
@endforeach

</div>

            </div>
        </div>
    </div>
</section>

<!-- Templates Section -->
@if($featuredTemplates->count() > 0)
<section id="template" class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6 mb-12 lg:mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                Template Siap Pakai
            </h2>
            <p class="text-lg text-gray-700 max-w-2xl mx-auto">
                Pilih dari berbagai template profesional untuk kebutuhan bisnis Anda
            </p>
        </div>
        
        <!-- Template Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredTemplates as $template)
            <div class="template-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-200 transition-all duration-300">
                <div class="relative">
                    @if($template->thumbnail)
                        <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        @if($template->price == 0)
                            <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Free</span>
                        @else
                            <span class="bg-primary-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Premium</span>
                        @endif
                        @if($template->is_featured)
                            <span class="bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Populer</span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-semibold text-lg text-gray-900 flex-1 pr-2">{{ $template->name }}</h3>
                        <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded-full shrink-0">
                            {{ $template->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-2">
                        {{ $template->description }}
                    </p>

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-700 text-sm">5.0</span>
                        </div>

                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span>200+</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @auth
                            @if(in_array($template->id, $userTemplates))
                                <!-- User sudah memiliki template -->
                                <a href="{{ route('editor', ['template' => $template->id]) }}" 
                                   class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                    Edit Template
                                </a>
                            @else
                                <!-- User belum memiliki template -->
                                <a href="{{ route('templates.use', $template) }}" 
                                   class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                    Gunakan Template
                                </a>
                            @endif
                        @else
                            <!-- User belum login -->
                            <a href="{{ route('register') }}" 
                               class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                Daftar untuk Menggunakan
                            </a>
                        @endauth
                        
                        <a href="{{ route('templates.show', $template) }}" 
                           class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                            Lihat Detail
                        </a>
                    </div>

                    @if($template->price > 0)
                    <div class="mt-3 text-center">
                        <span class="text-sm text-gray-600">Harga: </span>
                        <span class="text-sm font-bold text-primary-500">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Templates Button -->
        <div class="text-center mt-12">
            <a href="{{ route('templates.index') }}" class="btn-primary inline-flex items-center">
                Lihat Semua Template
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
<section class="bg-primary-50 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6 mb-12 lg:mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                Apa Kata Mereka
            </h2>
            <p class="text-lg text-gray-700 max-w-2xl mx-auto">
                Bergabung dengan ribuan pengusaha yang telah bertransformasi digital
            </p>
        </div>
        
        <!-- Testimonial Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $testimonials = [
                [
                    'name' => 'Ricky Steven Silaban',
                    'role' => 'Pemilik Toko Online',
                    'text' => '"Temploka mengubah cara saya mengelola bisnis. Sekarang semua data terorganisir rapi dan saya bisa fokus mengembangkan usaha."',
                    'initial' => 'R'
                ],
                [
                    'name' => 'Adryan Nathanael',
                    'role' => 'Manajer Keuangan',
                    'text' => '"Template keuangan di Temploka sangat membantu. Laporan otomatis dan dashboard yang mudah dipahami membuat pekerjaan lebih efisien."',
                    'initial' => 'A'
                ],
                [
                    'name' => 'Michael Sofyan',
                    'role' => 'Entrepreneur',
                    'text' => '"Integrasi dengan marketplace favorit saya membuat semua lebih praktis. Tidak perlu lagi input data berulang-ulang. Sangat direkomendasikan!"',
                    'initial' => 'M'
                ]
            ];
            @endphp
            
            @foreach($testimonials as $testimonial)
            <div class="testimonial-card p-8">
                <!-- Quote Icon -->
                <div class="w-12 h-12 text-primary-500 mb-6">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                
                <!-- Testimonial Text -->
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $testimonial['text'] }}
                </p>
                
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ $testimonial['initial'] }}
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">
                            {{ $testimonial['name'] }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $testimonial['role'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6 mb-12 lg:mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                Pertanyaan Yang Sering Diajukan
            </h2>
            <p class="text-lg text-gray-700">
                Temukan jawaban untuk pertanyaan umum tentang Temploka
            </p>
        </div>
        
        <!-- FAQ Items -->
        <div class="space-y-6">
            @php
            $faqs = [
                [
                    'question' => 'Apa itu Temploka?',
                    'answer' => 'Temploka adalah platform SaaS yang menyediakan template siap pakai untuk membantu Anda mengelola berbagai aspek bisnis seperti keuangan, inventori, pelanggan, dan penjualan tanpa perlu coding atau keahlian teknis khusus.'
                ],
                [
                    'question' => 'Apakah saya perlu keahlian teknis untuk menggunakan Temploka?',
                    'answer' => 'Tidak sama sekali! Temploka dirancang untuk pengguna tanpa keahlian teknis. Interface yang intuitif memungkinkan siapa saja dapat menggunakan platform ini dengan mudah.'
                ],
                [
                    'question' => 'Platform marketplace apa saja yang bisa diintegrasikan?',
                    'answer' => 'Kami mendukung integrasi dengan berbagai platform marketplace populer seperti Tokopedia, Shopee, Instagram, TikTok, dan WhatsApp Business.'
                ],
                [
                    'question' => 'Berapa biaya yang diperlukan untuk mulai menggunakan Temploka?',
                    'answer' => 'Kami memiliki berbagai paket yang fleksibel sesuai kebutuhan Anda, mulai dari paket gratis hingga paket enterprise dengan fitur lengkap.'
                ],
                [
                    'question' => 'Apakah data saya aman di Temploka?',
                    'answer' => 'Ya, keamanan data adalah prioritas utama kami. Kami menggunakan enkripsi tingkat tinggi dan server yang terjamin keamanannya.'
                ]
            ];
            @endphp
            
            @foreach($faqs as $index => $faq)
            <div class="faq-item p-6" onclick="toggleFAQ({{ $index }})">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 pr-4">
                        {{ $faq['question'] }}
                    </h3>
                    <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="faq-content mt-4 text-gray-600 leading-relaxed hidden" id="faq-content-{{ $index }}">
                    {{ $faq['answer'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .feature-card {
        @apply bg-white rounded-2xl p-8 text-center shadow-soft hover:shadow-lg transition duration-300;
    }
    .platform-card {
        @apply text-center p-4 rounded-2xl bg-white shadow-soft hover:shadow-lg transition duration-300;
    }
    .testimonial-card {
        @apply bg-white rounded-2xl p-8 shadow-soft hover:shadow-lg transition duration-300;
    }
    .template-card {
        @apply bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-200 transition-all duration-300;
    }
    .template-card:hover {
        transform: translateY(-5px);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .faq-item {
        @apply bg-white rounded-2xl p-6 shadow-soft cursor-pointer hover:shadow-lg transition duration-300;
    }
</style>
@endpush

@push('scripts')
<script>
function toggleFAQ(index) {
    const content = document.getElementById('faq-content-' + index);
    const icon = content.previousElementSibling.querySelector('.faq-icon');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endpush