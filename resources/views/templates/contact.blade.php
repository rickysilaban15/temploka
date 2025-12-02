@extends('layouts.app')

@section('title', 'Kontak - Temploka')

@section('content')
<!-- Contact Hero -->
<section class="bg-primary-50 py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Hubungi Kami
            </h1>
            <p class="text-lg text-gray-700 max-w-2xl mx-auto">
                Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda.
            </p>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">
                    Kirim Pesan
                </h2>
                <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150">
                        </div>
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Subjek
                        </label>
                        <input type="text" id="subject" name="subject" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesan
                        </label>
                        <textarea id="message" name="message" rows="6" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">
                        Informasi Kontak
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Jangan ragu untuk menghubungi kami melalui berbagai channel yang tersedia. Tim support kami siap membantu 24/7.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <p class="text-gray-600">hello@temploka.com</p>
                            <p class="text-sm text-gray-500">Respon dalam 24 jam</p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">WhatsApp</h3>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                            <p class="text-sm text-gray-500">Respon cepat</p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Lokasi</h3>
                            <p class="text-gray-600">Jakarta, Indonesia</p>
                            <p class="text-sm text-gray-500">Kantor pusat</p>
                        </div>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Jam Operasional</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Senin - Jumat</span>
                            <span class="text-gray-900 font-medium">09:00 - 18:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sabtu</span>
                            <span class="text-gray-900 font-medium">09:00 - 15:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Minggu</span>
                            <span class="text-gray-900 font-medium">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection