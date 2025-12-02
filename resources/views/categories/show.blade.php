@extends('layouts.app')

@section('content')
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Category Header -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 mx-auto mb-6 bg-blue-100 rounded-2xl flex items-center justify-center">
                <span class="text-3xl">{{ $category->icon }}</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">{{ $category->description }}</p>
            <div class="mt-4 text-blue-600 font-semibold">
                {{ $templates->total() }} template tersedia
            </div>
        </div>

        <!-- Templates Grid -->
        @if($templates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($templates as $template)
            <div class="card group">
                <div class="relative overflow-hidden">
                    <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @if($template->is_featured)
                    <div class="absolute top-4 right-4">
                        <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">Populer</span>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-2">{{ $template->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($template->description, 80) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-600 font-bold">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                        <a href="{{ route('templates.show', $template->slug) }}" class="btn-primary text-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $templates->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada template di kategori ini</h3>
            <p class="text-gray-500 mb-4">Template untuk kategori ini sedang dalam pengembangan</p>
            <a href="{{ route('categories.index') }}" class="btn-primary">Lihat Kategori Lain</a>
        </div>
        @endif
    </div>
</section>
@endsection