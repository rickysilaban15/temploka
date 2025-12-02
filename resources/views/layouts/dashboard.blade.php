<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Temploka')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-custom-gradient {
            background: linear-gradient(262.34deg, #EEFDFC 0%, #EEFDFC 98.99%);
        }
        
        .bg-primary-50 {
            background: rgba(0, 150, 137, 0.05);
        }
        
        .bg-primary-100 {
            background: rgba(0, 150, 137, 0.1);
        }
        
        .bg-primary {
            background: #009689;
        }
        
        .text-primary {
            color: #009689;
        }
        
        .border-primary {
            border-color: #009689;
        }
        
        .shadow-card {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-gradient {
            background: linear-gradient(262.34deg, #EEFDFC 0%, #EEFDFC 98.99%);
        }
        
        .main-gradient {
            background: linear-gradient(262.34deg, #EEFDFC 0%, #EEFDFC 98.99%);
        }
        
        /* Animations for sidebar toggle */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar-hidden {
            transform: translateX(-100%);
        }
        
        @media (min-width: 1024px) {
            .sidebar-hidden {
                transform: translateX(0);
            }
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
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
    <div class="flex h-screen relative">
        <!-- Mobile Menu Toggle Button -->
        <button id="sidebarToggle" class="lg:hidden fixed top-4 left-4 z-50 bg-primary text-white p-3 rounded-lg shadow-md">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col main-gradient w-full">
            <!-- Header -->
            @include('layouts.header')
            
            <!-- Main Content -->
            <div class="flex-1 bg-gray-50 p-4 lg:p-6 overflow-auto">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-hidden');
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target) &&
                    !sidebar.classList.contains('sidebar-hidden')) {
                    sidebar.classList.add('sidebar-hidden');
                }
            });
            
            // Simple animations
            // Animate stats cards on load
            const cards = document.querySelectorAll('.shadow-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-fade-in-up');
            });

            // Add hover effects
            const quickAccessItems = document.querySelectorAll('.cursor-pointer');
            quickAccessItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>