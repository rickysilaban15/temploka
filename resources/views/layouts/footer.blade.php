<!-- resources/views/layouts/footer.blade.php -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <!-- Company Info -->
            <div class="lg:col-span-2">
                 <!-- Logo -->
<!-- Logo dengan border dan shadow tebal -->
<div class="flex items-center flex-shrink-0 mb-6">
    <a href="{{ route('home') }}" 
       class="flex items-center space-x-2 lg:space-x-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-lg p-1">
        
        <!-- Logo dengan border putih dan shadow -->
        <div class="p-1 bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl">
            <div class="p-2 bg-white rounded-lg">
                <img src="{{ asset('images/logos.png') }}"
                     alt="Temploka Logo"
                     class="w-10 h-10 lg:w-12 lg:h-12 object-contain"
                     loading="lazy">
            </div>
        </div>
        
        <span class="font-bold text-xl lg:text-2xl text-white">
            Temploka
        </span>
    </a>
</div>
                <p class="text-gray-300 mb-6 max-w-md">
                    Solusi template bisnis untuk transformasi digital usaha Anda. Mudah, cepat, dan profesional.
                </p>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-gray-300">hello@temploka.com</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span class="text-gray-300">+62 812-3456-7890</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-300">Jakarta, Indonesia</span>
                    </div>
                </div>
            </div>

            <!-- Product Links -->
            <div>
                <h3 class="font-semibold text-lg mb-4">Produk</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('templates.index') }}" class="text-gray-300 hover:text-white transition duration-150">Template</a></li>
                    <li><a href="{{ route('integrations.index') }}" class="text-gray-300 hover:text-white transition duration-150">Integrasi</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-gray-300 hover:text-white transition duration-150">Harga</a></li>
                    <li><a href="{{ route('features') }}" class="text-gray-300 hover:text-white transition duration-150">Fitur</a></li>
                </ul>
            </div>

            <!-- Support Links -->
            <div>
                <h3 class="font-semibold text-lg mb-4">Dukungan</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('help-center') }}" class="text-gray-300 hover:text-white transition duration-150">Pusat Bantuan</a></li>
                    <li><a href="{{ route('documentation') }}" class="text-gray-300 hover:text-white transition duration-150">Dokumentasi</a></li>
                    <li><a href="{{ route('tutorials') }}" class="text-gray-300 hover:text-white transition duration-150">Tutorial</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition duration-150">Kontak</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">
                © 2025 Temploka. All rights reserved.
            </p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                    <span class="sr-only">Facebook</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                    <span class="sr-only">Instagram</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm3.5 6h-7C5.673 6 5 6.673 5 7.5v5c0 .827.673 1.5 1.5 1.5h7c.827 0 1.5-.673 1.5-1.5v-5c0-.827-.673-1.5-1.5-1.5zM10 12.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5zm3-5.5a.5.5 0 11-1 0 .5.5 0 011 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition duration-150">
                    <span class="sr-only">Twitter</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>