@extends('layouts.app')

@section('title', 'Integrasi - Temploka')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Integrasi Temploka</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Terhubung dengan platform favorit Anda untuk workflow yang lebih efisien
            </p>
        </div>

        <!-- Integrations by Category -->
        <div class="space-y-12">
            @foreach($integrations as $category)
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ $category['category'] }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($category['items'] as $integration)
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-2xl">{{ $integration['logo'] }}</div>
                            @if($integration['status'] === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                Coming Soon
                            </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $integration['name'] }}</h3>
                        <p class="text-gray-600 mb-4">{{ $integration['description'] }}</p>
                        @if($integration['status'] === 'active')
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                            Konfigurasi
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @else
                        <span class="text-gray-400 font-medium">Segera Hadir</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Request Integration -->
        <div class="mt-16 bg-white rounded-lg shadow-sm p-8 text-center">
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Tidak menemukan integrasi yang Anda butuhkan?</h3>
            <p class="text-gray-600 mb-6">Beritahu kami platform apa yang ingin Anda integrasikan dengan Temploka</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                Request Integrasi
            </a>
        </div>
    </div>
</div>
@endsection