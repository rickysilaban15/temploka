<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50" aria-label="Main navigation">
    <div class="container-custom">
        <div class="flex justify-between items-center h-16 lg:h-20">
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
            <div class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                <a href="{{ route('home') }}" 
                   class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}"
                   aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                    Beranda
                </a>
                <a href="{{ route('templates.index') }}" 
                   class="nav-link {{ request()->routeIs('templates.*') ? 'nav-link-active' : '' }}"
                   aria-current="{{ request()->routeIs('templates.*') ? 'page' : 'false' }}">
                    Template
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="nav-link {{ request()->routeIs('categories.*') ? 'nav-link-active' : '' }}"
                   aria-current="{{ request()->routeIs('categories.*') ? 'page' : 'false' }}">
                    Kategori
                </a>
                <!-- Cari link harga di navigation dan update menjadi: -->
<a href="{{ auth()->check() ? route('dashboard.templates') : route('templates.index') }}" 
   class="text-gray-700 hover:text-primary-600 transition duration-150">
    Harga
</a>
                <a href="{{ route('contact') }}" 
                   class="nav-link {{ request()->routeIs('contact') ? 'nav-link-active' : '' }}"
                   aria-current="{{ request()->routeIs('contact') ? 'page' : 'false' }}">
                    Kontak
                </a>
            </div>

            <!-- Desktop Auth Buttons -->
            <div class="hidden lg:flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link"
                       aria-label="Dashboard">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="nav-link"
                                aria-label="Logout">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       class="nav-link"
                       aria-label="Login">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="btn-primary text-sm px-4 py-2"
                       aria-label="Daftar">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button type="button" 
                        id="mobile-menu-button"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                        aria-label="Toggle mobile menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" 
         class="mobile-menu hidden lg:hidden bg-white border-t border-gray-200 shadow-lg"
         aria-labelledby="mobile-menu-button">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" 
               class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('home') ? 'text-primary-500 bg-primary-50' : '' }}"
               aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                Beranda
            </a>
            <a href="{{ route('templates.index') }}" 
               class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('templates.*') ? 'text-primary-500 bg-primary-50' : '' }}"
               aria-current="{{ request()->routeIs('templates.*') ? 'page' : 'false' }}">
                Template
            </a>
            <a href="{{ route('categories.index') }}" 
               class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('categories.*') ? 'text-primary-500 bg-primary-50' : '' }}"
               aria-current="{{ request()->routeIs('categories.*') ? 'page' : 'false' }}">
                Kategori
            </a>
            <a href="{{ route('pricing') }}" 
               class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('pricing') ? 'text-primary-500 bg-primary-50' : '' }}"
               aria-current="{{ request()->routeIs('pricing') ? 'page' : 'false' }}">
                Harga
            </a>
            <a href="{{ route('contact') }}" 
               class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('contact') ? 'text-primary-500 bg-primary-50' : '' }}"
               aria-current="{{ request()->routeIs('contact') ? 'page' : 'false' }}">
                Kontak
            </a>
            
            <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition duration-150 ease-in-out">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="block px-3 py-3 text-base font-medium bg-primary-500 text-white hover:bg-primary-600 rounded-lg transition duration-150 ease-in-out text-center">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    function toggleMobileMenu() {
        const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
        mobileMenu.classList.toggle('hidden');
        mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
        
        // Toggle icon
        const icon = mobileMenuButton.querySelector('svg');
        if (!isExpanded) {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
        } else {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
        }
    }
    
    mobileMenuButton.addEventListener('click', toggleMobileMenu);
    
    // Close mobile menu when clicking outside or on a link
    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
            if (!mobileMenu.classList.contains('hidden')) {
                toggleMobileMenu();
            }
        }
        
        // Close when clicking on a mobile menu link
        if (mobileMenu.contains(event.target) && event.target.tagName === 'A') {
            toggleMobileMenu();
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
            toggleMobileMenu();
        }
    });
});
</script>   