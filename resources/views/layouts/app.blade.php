<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Temploka')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .btn-primary {
            @apply bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-button transition duration-150;
        }
        .btn-outline {
            @apply border border-primary-500 text-primary-500 hover:bg-primary-50 font-semibold py-3 px-6 rounded-button transition duration-150;
        }
        .nav-link {
            @apply text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out;
        }
        .nav-link-active {
            @apply text-primary-500 font-semibold;
        }
    </style>
</head>

@stack('scripts')
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
<div class="flex items-center flex-shrink-0">
    <a href="{{ route('home') }}" 
       class="flex items-center space-x-2 lg:space-x-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-lg p-1">

        <img src="{{ asset('images/logo.jpg') }}"
             alt="Temploka Logo"
             class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg object-contain">

        <span class="font-bold text-xl lg:text-2xl text-gray-900 hidden sm:block">
            Temploka
        </span>
    </a>
</div>


                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'text-primary-500 font-semibold' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('templates.*') ? 'text-primary-500 font-semibold' : '' }}">
                        Template
                    </a>
                    <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('categories.*') ? 'text-primary-500 font-semibold' : '' }}">
                        Kategori
                    </a>
                    <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('pricing') ? 'text-primary-500 font-semibold' : '' }}">
                        Harga
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('contact') ? 'text-primary-500 font-semibold' : '' }}">
                        Kontak
                    </a>

                    <!-- Additional links for logged in users -->
                    @auth
                    <a href="{{ route('dashboard.templates') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out {{ request()->routeIs('dashboard.templates') ? 'text-primary-500 font-semibold' : '' }}">
                        Template Saya
                    </a>
                    @endauth
                </div>

                <!-- Desktop Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-gray-600 hover:text-gray-900 p-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('home') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('templates.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('templates.*') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Template
                </a>
                <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('categories.*') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Kategori
                </a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('pricing') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Harga
                </a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('contact') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Kontak
                </a>

                @auth
                <a href="{{ route('dashboard.templates') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('dashboard.templates') ? 'text-primary-500 bg-primary-50' : '' }}">
                    Template Saya
                </a>
                @endauth
                
                <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium bg-primary-500 text-white hover:bg-primary-600 rounded-lg text-center">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Include Footer -->
    @include('layouts.footer')

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });

                // Close mobile menu when clicking on links
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }

            // Tab functionality for template detail page
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            if (tabButtons.length > 0) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const tabId = button.getAttribute('data-tab');
                        
                        // Remove active class from all buttons and contents
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabContents.forEach(content => content.classList.remove('active'));
                        
                        // Add active class to current button and content
                        button.classList.add('active');
                        document.getElementById(tabId).classList.add('active');
                    });
                });
            }
        });
    </script>

    <!-- Add CSS for tabs -->
    <style>
        .tab-button {
            @apply px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition duration-150 ease-in-out;
        }
        .tab-button.active {
            @apply text-primary-500 border-primary-500;
        }
        .tab-content {
            @apply hidden;
        }
        .tab-content.active {
            @apply block;
        }
    </style>
</body>
</html>