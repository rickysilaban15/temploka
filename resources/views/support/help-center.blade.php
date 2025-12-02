@extends('layouts.app')

@section('title', 'Pusat Bantuan - Temploka')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Pusat Bantuan</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Temukan jawaban untuk pertanyaan Anda atau hubungi tim support kami
            </p>
        </div>

        <!-- Search -->
        <div class="mb-12">
            <div class="relative max-w-2xl mx-auto">
                <input 
                    type="text" 
                    placeholder="Cari solusi..." 
                    class="w-full px-6 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                <svg class="absolute right-4 top-4 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- FAQ Sections -->
        <div class="space-y-8 mb-12">
            @foreach($faqs as $category)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">{{ $category['category'] }}</h3>
                <div class="space-y-4">
                    @foreach($category['questions'] as $faq)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $faq['question'] }}</h4>
                        <p class="text-gray-600">{{ $faq['answer'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Contact CTA -->
        <div class="bg-primary-50 rounded-lg p-8 text-center">
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Butuh bantuan lebih lanjut?</h3>
            <p class="text-gray-600 mb-6">Tim support kami siap membantu Anda 24/7</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                Hubungi Support
            </a>
        </div>
    </div>
</div>
@endsection