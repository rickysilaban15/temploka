@extends('layouts.app')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Kategori Template</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Temukan template yang tepat berdasarkan kategori kebutuhan website Anda
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="card p-8 text-center group hover:shadow-xl transition duration-300">
                <div class="w-20 h-20 mx-auto mb-6 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition duration-200">
                    <span class="text-3xl">{{ $category->icon }}</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $category->name }}</h3>
                <p class="text-gray-600 mb-4 text-sm">{{ $category->description }}</p>
                <div class="text-blue-600 font-semibold">
                    {{ $category->templates_count }} template tersedia
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection