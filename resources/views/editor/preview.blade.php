<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Template - Temploka</title>
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
<body class="bg-gray-50 min-h-screen">
    <!-- Preview Header -->
    <div class="bg-white border-b border-gray-300 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('editor.index', $userTemplate->template_id) }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Preview Template</h1>
                    <p class="text-sm text-gray-600">{{ $userTemplate->template->name ?? 'Template Preview' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    <i class="fas fa-eye mr-1"></i>Preview Mode
                </span>
                <button onclick="window.print()" class="border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-print text-sm"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Preview Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Template Preview -->
            <div id="template-preview" class="min-h-screen">
                @php
                    $customData = json_decode($userTemplate->custom_data, true);
                @endphp
                @if($customData && isset($customData['html']))
                    {!! $customData['html'] !!}
                @else
                    <!-- Default Preview Content -->
                    <div class="text-center py-20">
                        <i class="fas fa-file text-primary text-6xl mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Template Preview</h2>
                        <p class="text-gray-600 mb-6">Belum ada konten yang disimpan untuk template ini.</p>
                        <p class="text-sm text-gray-500">Kembali ke editor untuk menambahkan komponen.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Preview Info -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-900 mb-2">Informasi Preview</h3>
                    <p class="text-blue-700 text-sm">
                        Ini adalah preview dari template Anda. Perubahan yang dibuat di editor akan ditampilkan di sini setelah disimpan.
                        Untuk mengedit template, kembali ke editor.
                    </p>
                    <div class="flex gap-3 mt-3">
                        <a href="{{ route('editor.index', $userTemplate->template_id) }}" class="bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i>Kembali ke Editor
                        </a>
                        <button onclick="window.close()" class="border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>Tutup Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive features to the preview
        document.addEventListener('DOMContentLoaded', function() {
            // Make all links open in new tab
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                if (link.href && !link.href.startsWith('javascript:')) {
                    link.target = '_blank';
                }
            });

            // Add loading state for images
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.3s ease';
            });

            // Handle form submissions in preview
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Form submission is disabled in preview mode. This would work in the published version.');
                });
            });

            // Add hover effects to interactive elements
            const buttons = document.querySelectorAll('button, [onclick]');
            buttons.forEach(button => {
                button.style.cursor = 'pointer';
                button.addEventListener('click', function(e) {
                    if (!this.getAttribute('onclick') && this.tagName === 'BUTTON') {
                        e.preventDefault();
                        alert('Button click is simulated in preview mode.');
                    }
                });
            });
        });

        // Print functionality
        function printPreview() {
            const printContent = document.getElementById('template-preview').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // ESC to close
            if (e.key === 'Escape') {
                window.close();
            }
            // Ctrl+P or Cmd+P to print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                printPreview();
            }
        });
    </script>
</body>
</html>