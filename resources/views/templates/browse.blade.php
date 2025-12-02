@extends('layouts.app')

@section('title', 'Template Library - Temploka')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-[#EEFDFC] to-[#EEFDFC]">
    <div class="flex-1 bg-gray-50 p-6">
        <!-- Filter Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <!-- Category and Sort Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Category Filter -->
                <div class="relative">
    <select id="categoryFilter" class="appearance-none bg-gray-100 border-0 rounded-2xl pl-5 pr-10 py-3 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
        <option value="">Semua Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
</div>

                <!-- Sort Filter -->
                <div class="relative">
                    <select id="sortFilter" class="appearance-none bg-gray-100 border-0 rounded-2xl pl-5 pr-10 py-3 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="latest">Terbaru</option>
                        <option value="popular">Paling Populer</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                        <option value="name">Nama A-Z</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="relative w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Cari Template"
                    class="w-full bg-gray-100 border-0 rounded-2xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                >
            </div>
        </div>

        <!-- Template Count -->
        <div class="mb-6">
            <p class="text-gray-600 text-sm">Menampilkan <span id="templateCount">{{ $templates->count() }}</span> template tersedia</p>
        </div>

        <!-- Templates Grid -->
        <div id="templatesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="template-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-200 transition-all duration-300" 
                     data-category="{{ $template->category_id }}"
                     data-name="{{ strtolower($template->name) }}"
                     data-description="{{ strtolower($template->description) }}"
                     data-price="{{ $template->price }}"
                     data-featured="{{ $template->is_featured }}"
                     data-latest="true">
                    
                    <div class="relative">
                        @if($template->thumbnail)
                            <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                            @if($template->price == 0)
                                <span class="bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Free</span>
                            @else
                                <span class="bg-[#009689] text-white text-xs font-semibold px-3 py-1 rounded-full">Premium</span>
                            @endif
                            @if($template->is_featured)
                                <span class="bg-[#F54900] text-white text-xs font-semibold px-3 py-1 rounded-full">Populer</span>
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
                                       class="w-full bg-[#009689] hover:bg-teal-700 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Template
                                    </a>
                                @else
                                    <!-- User belum memiliki template -->
                                    <a href="{{ route('templates.use', $template) }}" 
                                       class="w-full bg-[#009689] hover:bg-teal-700 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Gunakan Template
                                    </a>
                                @endif
                            @else
                                <!-- User belum login -->
                                <a href="{{ route('register') }}" 
                                   class="w-full bg-[#009689] hover:bg-teal-700 text-white py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Daftar untuk Menggunakan
                                </a>
                            @endauth
                            
                            <a href="{{ route('templates.show', $template) }}" 
                               class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-4 rounded-xl font-semibold text-sm transition duration-150 text-center block">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                        </div>

                        @if($template->price > 0)
                        <div class="mt-3 text-center">
                            <span class="text-sm text-gray-600">Harga: </span>
                            <span class="text-sm font-bold text-[#009689]">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16">
                    <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada template yang ditemukan</h3>
                    <p class="text-gray-500 text-center max-w-md">Template yang Anda cari tidak tersedia. Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($templates->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $templates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .template-card {
        transition: all 0.3s ease;
    }
    
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');
    const templatesGrid = document.getElementById('templatesGrid');
    const templateCount = document.getElementById('templateCount');
    const templateCards = document.querySelectorAll('.template-card');

    // Filter and sort templates
    function filterTemplates() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const sortBy = sortFilter.value;
        
        let visibleCount = 0;
        let filteredCards = [];

        // Filter cards based on search and category
        templateCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const description = card.getAttribute('data-description');
            const category = card.getAttribute('data-category');
            const price = parseFloat(card.getAttribute('data-price'));
            const isFeatured = card.getAttribute('data-featured') === '1';
            const isLatest = card.getAttribute('data-latest') === 'true';

            // Filter by search term
            const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
            
            // Filter by category
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
                visibleCount++;
                filteredCards.push({
                    element: card,
                    price: price,
                    name: name,
                    featured: isFeatured,
                    latest: isLatest
                });
            } else {
                card.style.display = 'none';
            }
        });

        // Sort filtered cards
        filteredCards.sort((a, b) => {
            switch(sortBy) {
                case 'price_low':
                    return a.price - b.price;
                case 'price_high':
                    return b.price - a.price;
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'popular':
                    return (b.featured ? 1 : 0) - (a.featured ? 1 : 0);
                case 'latest':
                default:
                    return (b.latest ? 1 : 0) - (a.latest ? 1 : 0);
            }
        });

        // Reorder cards in DOM
        filteredCards.forEach(item => {
            templatesGrid.appendChild(item.element);
        });

        // Update count
        templateCount.textContent = visibleCount;
    }

    // Event listeners
    searchInput.addEventListener('input', filterTemplates);
    categoryFilter.addEventListener('change', filterTemplates);
    sortFilter.addEventListener('change', filterTemplates);

    // Initial filter
    filterTemplates();

    // Add loading animation
    const cards = document.querySelectorAll('.template-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in-up');
    });
});
</script>
@endpush