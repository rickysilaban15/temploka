@extends('layouts.app')

@section('title', 'Fitur - Temploka')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Fitur Lengkap Temploka</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Semua yang Anda butuhkan untuk membangun website bisnis profesional dengan mudah dan cepat
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($features as $feature)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="text-3xl mb-4">{{ $feature['icon'] }}</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                <p class="text-gray-600 mb-4">{{ $feature['description'] }}</p>
                <ul class="space-y-2">
                    @foreach($feature['details'] as $detail)
                    <li class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $detail }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <!-- CTA Section -->
        <div class="bg-primary-600 rounded-2xl p-8 lg:p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Siap Memulai?</h2>
            <p class="text-primary-100 text-xl mb-8 max-w-2xl mx-auto">
                Bergabung dengan ribuan bisnis yang sudah menggunakan Temploka untuk website mereka
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('templates.index') }}" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50">
                    Jelajahi Template
                </a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center px-8 py-4 border border-white text-base font-medium rounded-md text-white hover:bg-primary-700">
                    Lihat Harga
                </a>
            </div>
        </div>
    </div>
</div>
@endsection