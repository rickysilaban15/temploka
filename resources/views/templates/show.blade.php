@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">Home</a>
            </li>
            <li>
                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="{{ route('templates.index') }}" class="text-gray-400 hover:text-gray-500">Template</a>
            </li>
            <li>
                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="text-gray-500">{{ $template->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Template Images -->
        <div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-4">
                <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" 
                     class="w-full h-auto transition-transform duration-300 hover:scale-105 cursor-pointer" 
                     id="mainImage">
            </div>
            
            @if(is_array($template->preview_images) && count($template->preview_images) > 0)
            <div class="grid grid-cols-2 gap-4">
                @foreach($template->preview_images as $index => $image)
                <div class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow duration-300">
                    <img src="{{ $image }}" 
                         alt="Preview {{ $template->name }}" 
                         class="w-full h-32 object-cover"
                         onclick="changeMainImage('{{ $image }}')">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Template Details -->
        <div>
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mb-4">
                    {{ $template->category->name }}
                </span>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $template->name }}</h1>
                <p class="text-gray-600 mb-6">{{ $template->description }}</p>
                
                <div class="flex items-center mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="ml-1 text-gray-600">{{ $template->rating ?? '4.5' }} ({{ $template->reviews_count ?? '50' }} reviews)</span>
                    </div>
                    <span class="mx-4 text-gray-300">•</span>
                    <span class="text-gray-600">{{ $template->downloads ?? '200' }}+ downloads</span>
                </div>

                @if(is_array($template->features) && count($template->features) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Fitur Template</h3>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($template->features as $feature)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="border-t border-gray-200 pt-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                            @if($template->original_price && $template->original_price > $template->price)
                            <span class="text-gray-500 line-through ml-2">Rp {{ number_format($template->original_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        @if($template->original_price && $template->original_price > $template->price)
                        @php
                            $discount = (($template->original_price - $template->price) / $template->original_price) * 100;
                        @endphp
                        <span class="bg-red-100 text-red-800 text-sm px-2 py-1 rounded">Hemat {{ round($discount) }}%</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    @auth
    @if($userHasTemplate)
        <a href="{{ route('templates.use', $template->id) }}" 
           class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Gunakan Template
        </a>
    @else
        <form action="{{ route('templates.purchase', $template->id) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Beli Sekarang - Rp {{ number_format($template->price, 0, ',', '.') }}
            </button>
        </form>
    @endif
@else
    <a href="{{ route('login') }}?redirect={{ url()->current() }}" 
       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Login untuk Membeli
    </a>
@endauth

                    <button type="button" 
                            onclick="openPreviewModal()"
                            class="w-full border border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview Template
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Garansi 30 hari uang kembali
                    </p>
                </div>
            </div>
        </div>
    </div>

   

    <!-- Related Templates -->
    @if($relatedTemplates && $relatedTemplates->count() > 0)
    <section class="mt-16">
        <h2 class="text-2xl font-bold mb-8">Template Serupa</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedTemplates as $related)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative">
                    <img src="{{ $related->thumbnail }}" alt="{{ $related->name }}" class="w-full h-48 object-cover">
                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                        {{ $related->category->name }}
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $related->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $related->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-600 font-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                        <a href="{{ route('templates.show', $related->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Preview Template - {{ $template->name }}</h3>
            <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4 overflow-auto max-h-[calc(90vh-80px)]">
            @if($template->preview_url)
            <iframe src="{{ $template->preview_url }}" 
                    class="w-full h-96 border rounded-lg" 
                    frameborder="0"
                    loading="lazy"></iframe>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">Preview tidak tersedia untuk template ini.</p>
                <img src="{{ $template->thumbnail }}" alt="Preview" class="max-w-full h-auto mx-auto rounded-lg">
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(imageUrl) {
    document.getElementById('mainImage').src = imageUrl;
}

function openPreviewModal() {
    document.getElementById('previewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush