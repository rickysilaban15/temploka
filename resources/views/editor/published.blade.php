<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $userTemplate->template->name }} - Published Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-primary {
            background: #009689;
        }
        
        .text-primary {
            color: #009689;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Published Template Header -->
    @if(auth()->id() == $userTemplate->user_id)
    <div class="bg-white border-b border-gray-300 px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard.templates') }}" class="text-sm text-gray-600 hover:text-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Published
                </span>
                <span class="text-sm text-gray-600">
                    <i class="fas fa-eye mr-1"></i>{{ $userTemplate->views }} views
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Published Template Content -->
    <div id="published-template">
        @php
            $customData = json_decode($userTemplate->custom_data, true);
        @endphp
        @if($customData && isset($customData['html']))
            {!! $customData['html'] !!}
        @else
            <div class="container mx-auto px-4 py-20 text-center">
                <i class="fas fa-file text-primary text-6xl mb-4"></i>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $userTemplate->template->name }}</h1>
                <p class="text-gray-600 mb-6">Template by {{ $userTemplate->user->name }}</p>
                <p class="text-sm text-gray-500">Template content will appear here.</p>
            </div>
        @endif
    </div>

    <!-- Published Template Footer -->
    @if(auth()->id() == $userTemplate->user_id)
    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Published on {{ $userTemplate->published_at->format('d M Y') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('editor.index', $userTemplate->template_id) }}" class="bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>Edit Template
                    </a>
                    <button onclick="window.print()" class="border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gray-50 border-t border-gray-200 px-6 py-8">
        <div class="container mx-auto text-center">
            <p class="text-sm text-gray-600">
                Template created by {{ $userTemplate->user->name }} using Temploka
            </p>
            <p class="text-xs text-gray-500 mt-2">
                &copy; {{ date('Y') }} Temploka. All rights reserved.
            </p>
        </div>
    </div>
    @endif

    <script>
        // Add analytics tracking
        document.addEventListener('DOMContentLoaded', function() {
            // Track time spent on page
            let startTime = new Date();
            
            window.addEventListener('beforeunload', function() {
                let endTime = new Date();
                let timeSpent = Math.round((endTime - startTime) / 1000); // in seconds
                
                // Send analytics data (simulated)
                console.log('Time spent on page:', timeSpent, 'seconds');
            });

            // Make external links open in new tab
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                if (link.href && !link.href.includes(window.location.hostname)) {
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                }
            });
        });
    </script>
</body>
</html>