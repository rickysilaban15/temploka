@extends('layouts.app')

@section('title', 'Tutorial - Temploka')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tutorial</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Video dan panduan langkah demi langkah untuk memaksimalkan penggunaan Temploka
            </p>
        </div>

        <!-- Tutorial Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($tutorials as $tutorial)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="bg-gray-200 h-48 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-600">Video Tutorial</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                            {{ $tutorial['level'] }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $tutorial['duration'] }}</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $tutorial['title'] }}</h3>
                    <p class="text-gray-600 mb-4">{{ $tutorial['description'] }}</p>
                    <a href="{{ $tutorial['video_url'] }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                        Tonton Tutorial
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Additional Resources -->
        <div class="bg-primary-50 rounded-lg p-8">
            <div class="text-center">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Ingin belajar lebih lanjut?</h3>
                <p class="text-gray-600 mb-6">Jelajahi dokumentasi lengkap dan komunitas Temploka</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('documentation') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Baca Dokumentasi
                    </a>
                    <a href="{{ route('help-center') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Pusat Bantuan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection