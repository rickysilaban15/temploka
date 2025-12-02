@extends('dashboard.layouts.app')

@section('title', 'Template Library - Temploka')

@section('content')
    <!-- Filter and Search Section -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-card mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <!-- Category and Sort Filters -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Category Filter -->
                <div class="relative">
                    <select class="appearance-none bg-gray-100 border-0 rounded-2xl pl-5 pr-10 py-3 w-48 focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                        @foreach($categories ?? [] as $category)
                            <option>{{ $category }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-caret-down text-gray-500 text-sm"></i>
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="relative">
                    <select class="appearance-none bg-gray-100 border-0 rounded-2xl pl-5 pr-10 py-3 w-48 focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                        <option>Paling Populer</option>
                        <option>Terbaru</option>
                        <option>Rating Tertinggi</option>
                        <option>Download Terbanyak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-caret-down text-gray-500 text-sm"></i>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="relative w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input 
                    type="text" 
                    placeholder="Cari Template"
                    class="w-full bg-gray-100 border-0 rounded-2xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                >
            </div>
        </div>
    </div>

    <!-- Template Count -->
    <div class="mb-6">
        <p class="text-gray-600 text-sm">Menampilkan {{ $templates->count() }} template</p>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($templates as $template)
            <!-- Template Card -->
            <div class="template-card bg-white rounded-2xl overflow-hidden shadow-card hover-lift border border-gray-200">
                <div class="relative">
                    <!-- GUNAKAN $template->thumbnail JIKA ADA, JIKA TIDAK GUNAKAN PLACEHOLDER -->
                    @if($template->thumbnail)
                        <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                            <i class="fas fa-layer-group text-blue-500 text-4xl"></i>
                        </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        @if($template->price == 0)
                            <span class="bg-white text-primary border border-primary text-xs font-semibold px-3 py-1 rounded-full">Free</span>
                        @else
                            <span class="bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">Premium</span>
                        @endif
                        @if($template->is_featured)
                            <span class="bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Populer</span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-lg text-gray-900 flex-1">{{ $template->name }}</h3>
                        <span class="bg-transparent text-gray-900 border border-gray-400 text-xs font-semibold px-3 py-1 rounded-full">{{ $template->category->name ?? 'Uncategorized' }}</span>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                        {{ $template->description ?? 'Deskripsi template akan ditampilkan di sini.' }}
                    </p>

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="flex">
                                <!-- Rating default karena tidak ada di database -->
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-gray-700 text-sm">5.0</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-download text-gray-400 text-sm"></i>
                            <span class="text-gray-700 text-sm">0</span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <!-- SEMUA TEMPLATE: Button "Gunakan Template" -->
                        <a href="{{ route('templates.use', $template) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-2xl font-semibold text-sm transition duration-150 text-center block">
                            <i class="fas fa-edit mr-2"></i>Gunakan Template
                        </a>
                        
                        <button class="w-12 h-12 border border-gray-300 rounded-2xl flex items-center justify-center hover:bg-gray-50 transition duration-150 preview-btn" data-template-name="{{ $template->name }}">
                            <i class="fas fa-eye text-gray-600"></i>
                        </button>
                    </div>

                    <!-- Tampilkan harga untuk template berbayar -->
                    @if($template->price > 0)
                    <div class="mt-3 text-center">
                        <span class="text-sm text-gray-600">Harga: </span>
                        <span class="text-sm font-bold text-primary">Rp {{ number_format($template->price) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <!-- Pesan jika tidak ada template -->
            <div class="col-span-full flex flex-col items-center justify-center py-16">
                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada template yang ditemukan</h3>
                <p class="text-gray-500 text-center max-w-md">Template yang Anda cari tidak tersedia. Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Load More Button (Hanya ditampilkan jika ada template) -->
    @if($templates->count() > 0)
        <div class="flex justify-center">
            <button class="bg-primary hover:bg-teal-700 text-white py-3 px-8 rounded-2xl font-semibold transition duration-150 load-more-btn">
                Muat Lebih Banyak
            </button>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .template-card {
        transition: all 0.3s ease;
    }
    
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
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
    
    .template-card {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview button functionality
        const previewButtons = document.querySelectorAll('.preview-btn');
        previewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Tambahkan ini
                e.stopPropagation(); // Tambahkan ini
                const templateName = this.getAttribute('data-template-name');
                alert(`Preview template: ${templateName}\n\nFitur preview akan segera tersedia!`);
            });
        });

        // Search functionality - TAMBAHKAN NULL CHECK
        const searchInput = document.querySelector('input[placeholder="Cari Template"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const templateCards = document.querySelectorAll('.template-card');
                
                templateCards.forEach(card => {
                    // TAMBAHKAN NULL CHECK
                    const titleEl = card.querySelector('h3');
                    const descEl = card.querySelector('p');
                    const catEl = card.querySelector('.bg-transparent');
                    
                    const title = titleEl ? titleEl.textContent.toLowerCase() : '';
                    const description = descEl ? descEl.textContent.toLowerCase() : '';
                    const category = catEl ? catEl.textContent.toLowerCase() : '';
                    
                    if (title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Category filter functionality
        const categoryFilter = document.querySelector('select:first-of-type');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const selectedCategory = this.value.toLowerCase();
                const templateCards = document.querySelectorAll('.template-card');
                
                templateCards.forEach(card => {
                    const catEl = card.querySelector('.bg-transparent');
                    const category = catEl ? catEl.textContent.toLowerCase() : '';
                    
                    if (selectedCategory === 'semua kategori' || category.includes(selectedCategory)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Sort filter functionality
        const sortFilter = document.querySelector('select:last-of-type');
        if (sortFilter) {
            sortFilter.addEventListener('change', function() {
                const sortBy = this.value;
                alert(`Mengurutkan template berdasarkan: ${sortBy}`);
            });
        }

        // Load more functionality
        const loadMoreBtn = document.querySelector('.load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memuat...';
                this.disabled = true;
                
                setTimeout(() => {
                    alert('Template tambahan akan dimuat di sini!');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            });
        }

        // Add animation delay for template cards
        const templateCards = document.querySelectorAll('.template-card');
        templateCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush