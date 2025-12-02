<div id="sidebar" class="fixed lg:sticky top-0 left-0 z-40 h-screen sidebar-gradient border-r border-gray-200 flex flex-col sidebar-transition sidebar-hidden lg:translate-x-0 shadow-sidebar">
    <!-- Header Section -->
    <div class="sidebar-header flex-shrink-0 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between h-16 lg:h-20 px-4 lg:px-6">
           <!-- Logo -->
<div class="flex items-center space-x-3 min-w-0">
    
    <!-- Logo Image -->
    <div class="flex-shrink-0">
        <img src="{{ asset('images/logo.jpg') }}"
             alt="Temploka Logo"
             class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 object-contain rounded-xl">
    </div>

    <!-- Logo Text -->
    <div class="logo-text min-w-0">
        <h1 class="text-lg sm:text-xl font-bold text-gray-900 truncate">
            Temploka
        </h1>
        <p class="text-gray-600 text-xs sm:text-sm truncate">
            Dashboard Bisnis
        </p>
    </div>

</div>

            
            <!-- Close Button (Mobile Only) -->
            <button id="sidebarClose" class="lg:hidden text-gray-400 hover:text-gray-600 transition p-2 rounded-lg hover:bg-gray-100 flex-shrink-0">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto custom-scrollbar bg-white">
        <nav class="p-4 lg:p-6 space-y-1 lg:space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gauge-high {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Dashboard</span>
            </a>
            
            <!-- Template -->
            <a href="{{ route('dashboard.templates') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.templates*') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file {{ request()->routeIs('dashboard.templates*') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard.templates*') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Template</span>
            </a>
            
            <!-- Modul -->
            <a href="{{ route('dashboard.modules') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.modules*') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-boxes-stacked {{ request()->routeIs('dashboard.modules*') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard.modules*') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Modul</span>
            </a>
            
            <!-- Integrasi -->
            <a href="{{ route('dashboard.integrations') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.integrations*') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-link {{ request()->routeIs('dashboard.integrations*') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard.integrations*') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Integrasi</span>
            </a>
            
            <!-- Workshop -->
            <a href="{{ route('dashboard.workshop') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.workshop*') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-graduate {{ request()->routeIs('dashboard.workshop*') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard.workshop*') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Workshop</span>
            </a>
            
            <!-- Pengaturan -->
            <a href="{{ route('dashboard.settings') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.settings*') ? 'bg-primary-50 border border-primary-200' : 'hover:bg-gray-50' }} group">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gear {{ request()->routeIs('dashboard.settings*') ? 'text-primary' : 'text-gray-500' }} text-sm group-hover:text-primary"></i>
                </div>
                <span class="font-medium {{ request()->routeIs('dashboard.settings*') ? 'text-primary' : 'text-gray-700' }} nav-text group-hover:text-primary whitespace-nowrap overflow-hidden">Pengaturan</span>
            </a>
        </nav>
        
        <!-- Logout Section -->
        <div class="px-4 lg:px-6 pb-4 lg:pb-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button w-full bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 transition-colors py-3 px-4 rounded-xl text-sm font-medium flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt flex-shrink-0"></i>
                    <span class="logout-text whitespace-nowrap overflow-hidden">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>