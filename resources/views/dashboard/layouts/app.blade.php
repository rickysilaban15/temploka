<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Temploka')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CHART.JS CDN - PASTIKAN INI DIMUAT -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary: #009689;
            --primary-50: rgba(0, 150, 137, 0.05);
            --primary-100: rgba(0, 150, 137, 0.1);
            --primary-200: rgba(0, 150, 137, 0.2);
        }
        
        .bg-primary {
            background: var(--primary);
        }
        
        .bg-primary-50 {
            background: var(--primary-50);
        }
        
        .text-primary {
            color: var(--primary);
        }
        
        .border-primary-200 {
            border-color: var(--primary-200);
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #EEFDFC 0%, #FFFFFF 100%);
        }
        
        .main-gradient {
            background: linear-gradient(180deg, #F9FAFB 0%, #FFFFFF 100%);
        }
        
        .shadow-sidebar {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile: Hidden by default */
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        
        /* Desktop: Collapsed state */
        #sidebar {
            width: 320px;
        }
        
        #sidebar.sidebar-collapsed {
            width: 80px;
        }
        
        /* Hide text elements when collapsed */
        #sidebar.sidebar-collapsed .logo-text,
        #sidebar.sidebar-collapsed .nav-text,
        #sidebar.sidebar-collapsed .logout-text {
            opacity: 0;
            visibility: hidden;
            width: 0;
            transition: opacity 0.2s, visibility 0s 0.2s, width 0.3s;
        }
        
        /* Show text when not collapsed */
        #sidebar:not(.sidebar-collapsed) .logo-text,
        #sidebar:not(.sidebar-collapsed) .nav-text,
        #sidebar:not(.sidebar-collapsed) .logout-text {
            opacity: 1;
            visibility: visible;
            width: auto;
            transition: opacity 0.3s 0.1s, visibility 0s, width 0.3s;
        }
        
        /* Center icons when collapsed */
        #sidebar.sidebar-collapsed nav a,
        #sidebar.sidebar-collapsed .logout-button {
            justify-content: center;
        }
        
        /* Adjust padding when collapsed */
        #sidebar.sidebar-collapsed .sidebar-header > div {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        #sidebar.sidebar-collapsed nav {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 1024px) {
            .sidebar-hidden {
                transform: translateX(0);
            }
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>
        
        <!-- Sidebar -->
        @include('dashboard.layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 sidebar-transition" id="mainContent">
            <!-- Header -->
            @include('dashboard.layouts.header')
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto main-gradient custom-scrollbar">
                <div class="container mx-auto p-4 lg:p-6">
                    <!-- Session Messages -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 animate-fade-in-up">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 animate-fade-in-up">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
            const sidebarClose = document.getElementById('sidebarClose');
            
            let isCollapsed = false;

            // Toggle sidebar for mobile (slide in/out)
            function toggleMobileSidebar() {
                sidebar.classList.toggle('sidebar-hidden');
                mobileOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }

            // Toggle sidebar collapse for desktop (collapse/expand)
            function toggleDesktopSidebar() {
                isCollapsed = !isCollapsed;
                sidebar.classList.toggle('sidebar-collapsed');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }

            // Initialize sidebar state from localStorage (desktop only)
            function initializeSidebar() {
                if (window.innerWidth >= 1024) {
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        isCollapsed = true;
                        sidebar.classList.add('sidebar-collapsed');
                    }
                }
            }

            // Event listeners
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', toggleMobileSidebar);
            }
            
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', toggleDesktopSidebar);
            }
            
            if (sidebarClose) {
                sidebarClose.addEventListener('click', toggleMobileSidebar);
            }
            
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', toggleMobileSidebar);
            }
            
            // Close sidebar when clicking on nav links in mobile
            const navLinks = document.querySelectorAll('#sidebar nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        toggleMobileSidebar();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    // Desktop: ensure mobile sidebar is visible and remove mobile states
                    sidebar.classList.remove('sidebar-hidden');
                    mobileOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    // Mobile: reset collapse state
                    if (isCollapsed) {
                        isCollapsed = false;
                        sidebar.classList.remove('sidebar-collapsed');
                    }
                }
            });
            
            // Add animation to cards
            const cards = document.querySelectorAll('.shadow-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fade-in-up');
            });
            
            // Initialize sidebar state
            initializeSidebar();
        });
    </script>
    
    @stack('scripts')
</body>
</html>