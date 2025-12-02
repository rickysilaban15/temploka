@extends('layouts.app')

@section('title', 'Dokumentasi - Temploka')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Dokumentasi</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Panduan lengkap dan dokumentasi teknis untuk menggunakan Temploka
            </p>
        </div>

        <!-- Documentation Sections -->
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($docs as $doc)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="text-3xl mb-4">{{ $doc['icon'] }}</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $doc['title'] }}</h3>
                <p class="text-gray-600 mb-4">{{ $doc['description'] }}</p>
                <ul class="space-y-2 mb-4">
                    @foreach($doc['sections'] as $section)
                    <li class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $section }}
                    </li>
                    @endforeach
                </ul>
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                    Baca Dokumentasi
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Quick Links -->
        <div class="mt-12 bg-white rounded-lg shadow-sm p-8">
            <h3 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Panduan Cepat</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Untuk Pemula</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">Memulai dalam 5 menit</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">Pilih template pertama</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">Kustomisasi dasar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Untuk Developer</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">API Reference</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">Custom Integration</a></li>
                        <li><a href="#" class="text-primary-600 hover:text-primary-700">Webhook Guide</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection