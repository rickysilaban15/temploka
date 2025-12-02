<!-- Mobile Header -->
<div class="lg:hidden bg-white border-b border-gray-200 sticky top-0 z-30 h-16 flex items-center justify-between px-4">
    <button id="sidebarToggleMobile" class="text-gray-600 p-2 rounded-lg hover:bg-gray-100">
        <i class="fas fa-bars text-lg"></i>
    </button>
    
    <div class="flex items-center space-x-3">
        @auth
        <div id="mobileProfileAvatar" class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-xs overflow-hidden">
            @if(Auth::user()->avatar)
                <img src="{{ Storage::url(Auth::user()->avatar) }}?t={{ time() }}" alt="Profile" class="w-full h-full object-cover" id="mobileProfileImage">
            @else
                <div class="w-full h-full flex items-center justify-center bg-primary text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                </div>
            @endif
        </div>
        @endauth
    </div>
</div>

<!-- Desktop Header -->
<header class="hidden lg:block bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Section - Toggle & Page Title -->
        <div class="flex items-center space-x-4">
            <button id="sidebarToggleDesktop" class="text-gray-400 hover:text-gray-600 transition p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    @if(request()->routeIs('dashboard'))
                        Dashboard
                    @elseif(request()->routeIs('dashboard.templates*'))
                        Template Library
                    @elseif(request()->routeIs('dashboard.modules*'))
                        Modul Bisnis
                    @elseif(request()->routeIs('dashboard.integrations*'))
                        Integrasi Marketplace & Sosial Media
                    @elseif(request()->routeIs('dashboard.workshop*'))
                        Workshop & E-Learning   
                    @elseif(request()->routeIs('dashboard.settings*'))
                        Pengaturan
                    @else
                        Dashboard
                    @endif
                </h1>
                <p class="text-gray-600 text-sm">
                    @if(request()->routeIs('dashboard'))
                        Selamat datang kembali! Berikut ringkasan bisnis Anda hari ini.
                    @elseif(request()->routeIs('dashboard.templates*'))
                        Temukan template yang sempurna untuk bisnis Anda
                    @elseif(request()->routeIs('dashboard.modules*'))
                        Kelola berbagai aspek bisnis Anda dalam satu tempat
                    @elseif(request()->routeIs('dashboard.integrations*'))
                        Hubungkan akun Anda dengan platform lain
                    @elseif(request()->routeIs('dashboard.workshop*'))
                        Pelajari dan tingkatkan skill bisnis Anda
                    @elseif(request()->routeIs('dashboard.settings*'))
                        Kelola profil, keamanan, dan preferensi Anda
                    @else
                        Selamat datang kembali! Berikut ringkasan bisnis Anda hari ini.
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Right Section - User Profile -->
        <div class="flex items-center space-x-4">
            @auth
            <div class="flex items-center space-x-3">
                <div id="desktopProfileAvatar" class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}?t={{ time() }}" alt="Profile" class="w-full h-full object-cover" id="desktopProfileImage">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="text-left user-info">
                    <div class="font-semibold text-gray-900 text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-gray-500 text-xs">{{ Auth::user()->role ?? 'Pengguna' }}</div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</header>