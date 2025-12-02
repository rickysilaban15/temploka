<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Editor - Temploka</title>
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
        
        .bg-primary-100 {
            background: rgba(0, 150, 137, 0.1);
        }
        
        .main-gradient {
            background: linear-gradient(262.34deg, #EEFDFC 0%, #EEFDFC 98.99%);
        }
        
        .component-placeholder {
            border: 2px dashed #009689;
            background: rgba(0, 150, 137, 0.05);
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #009689;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .component-placeholder:hover {
            background: rgba(0, 150, 137, 0.1);
        }
        
        .component {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .component:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .component.selected {
            border-color: #009689 !important;
            box-shadow: 0 0 0 2px rgba(0, 150, 137, 0.2);
        }
        
        .component-actions {
            position: absolute;
            top: -10px;
            right: -10px;
            display: none;
            z-index: 10;
        }
        
        .component:hover .component-actions {
            display: flex;
        }
        
        .dragging {
            opacity: 0.5;
            transform: rotate(5deg);
        }
        
        .drop-zone.active {
            background: rgba(0, 150, 137, 0.1);
            border-color: #009689;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .mobile-hidden {
                display: none !important;
            }
            
            .mobile-flex-col {
                flex-direction: column !important;
            }
            
            .mobile-w-full {
                width: 100% !important;
            }
            
            .mobile-p-2 {
                padding: 0.5rem !important;
            }
            
            .mobile-text-sm {
                font-size: 0.875rem !important;
            }
            
            .mobile-text-xs {
                font-size: 0.75rem !important;
            }
            
            .mobile-gap-2 {
                gap: 0.5rem !important;
            }
            
            .mobile-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                background: white;
                z-index: 1000;
                transition: left 0.3s ease;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }
            
            .mobile-sidebar.active {
                left: 0;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .mobile-overlay.active {
                display: block;
            }
            
            .mobile-canvas-full {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .mobile-sidebar-right {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: white;
                z-index: 1000;
                transition: right 0.3s ease;
                box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            }
            
            .mobile-sidebar-right.active {
                right: 0;
            }
        }

        /* Touch device improvements */
        @media (hover: none) and (pointer: coarse) {
            .component-actions {
                display: flex !important;
                opacity: 0.9;
            }
            
            .component {
                border-width: 3px;
            }
            
            .draggable {
                padding: 12px;
                margin: 8px 0;
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="main-gradient h-screen overflow-hidden">
    <!-- Header -->
    <div class="bg-white border-b border-gray-300 px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between mobile-flex-col mobile-gap-2">
            <!-- Logo & Back Section -->
            <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto justify-between sm:justify-start">
                <div class="flex items-center gap-3">
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition sm:hidden">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    
                    <a href="{{ route('dashboard.templates') }}" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition mobile-hidden sm:flex">
                        <i class="fas fa-arrow-left text-gray-600"></i>
                    </a>
                    
                    <div class="mobile-text-sm">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Editor</h1>
                        <p class="text-xs sm:text-sm text-gray-600 truncate max-w-[150px] sm:max-w-none">
                            {{ $currentTemplate->name ?? 'Pilih Template' }}
                        </p>
                    </div>
                </div>
                
                <!-- Mobile Actions -->
                <div class="flex items-center gap-2 sm:hidden">
                    <button id="mobileSettingsBtn" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
                        <i class="fas fa-cog text-gray-600"></i>
                    </button>
                    <button id="mobilePreviewBtn" class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-gray-200 transition">
                        <i class="fas fa-eye text-gray-600"></i>
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <!-- Template Selector -->
                @if($userTemplates->count() > 0)
                <select id="templateSelector" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary mobile-hidden sm:block mobile-w-full">
                    <option value="">Pilih Template...</option>
                    @foreach($userTemplates as $ut)
                        <option value="{{ $ut->template->id }}" {{ $currentTemplate && $currentTemplate->id == $ut->template->id ? 'selected' : '' }}>
                            {{ $ut->template->name }}
                        </option>
                    @endforeach
                </select>
                @endif
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-1 sm:gap-2">
                    <button id="saveBtn" class="border border-gray-300 hover:bg-gray-50 px-3 py-2 rounded-xl text-sm font-medium flex items-center gap-2 mobile-text-xs">
                        <i class="fas fa-save text-sm"></i>
                        <span class="mobile-hidden sm:inline">Simpan</span>
                    </button>
                    
                    <button id="previewBtn" class="border border-gray-300 hover:bg-gray-50 px-3 py-2 rounded-xl text-sm font-medium flex items-center gap-2 mobile-hidden sm:flex">
                        <i class="fas fa-eye text-sm"></i>
                        <span>Preview</span>
                    </button>
                    
                    <!-- Checkout Button untuk Template Berbayar yang Belum Dibeli -->
                    @if($currentTemplate && $currentTemplate->price > 0 && !$userHasPaid)
                    <a href="{{ route('payment.checkout', $currentTemplate) }}" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-xl text-sm font-medium flex items-center gap-2 mobile-text-xs">
                        <i class="fas fa-shopping-cart text-sm"></i>
                        <span class="mobile-hidden sm:inline">Checkout</span>
                    </a>
                    @endif
                    
                    <button id="publishBtn" class="bg-primary hover:bg-teal-700 text-white px-3 py-2 rounded-xl text-sm font-medium flex items-center gap-2 mobile-text-xs" 
                            data-template-price="{{ $currentTemplate->price ?? 0 }}"
                            data-user-has-paid="{{ $userHasPaid ? 'true' : 'false' }}">
                        <i class="fas fa-cloud-upload-alt text-sm"></i>
                        <span class="mobile-hidden sm:inline">Publish</span>
                    </button>

                    <button id="resetBtn" class="border border-red-300 hover:bg-red-50 text-red-600 px-3 py-2 rounded-xl text-sm font-medium flex items-center gap-2 mobile-hidden sm:flex">
                        <i class="fas fa-undo text-sm"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Editor Area -->
    <div class="flex h-[calc(100vh-80px)] sm:h-[calc(100vh-80px)]">
        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="mobile-overlay"></div>
        
        <!-- Left Sidebar - Components -->
        <div id="leftSidebar" class="mobile-sidebar sm:relative sm:left-0 sm:w-80 bg-white border-r border-gray-300 flex flex-col z-40">
            <!-- Components Header -->
            <div class="p-4 border-b border-gray-300 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Komponen</h2>
                    <p class="text-sm text-gray-600">Drag & drop untuk menambahkan</p>
                </div>
                <button id="closeLeftSidebar" class="sm:hidden w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Components List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                @foreach([
                    ['icon' => 'fa-heading', 'name' => 'Header', 'desc' => 'Navigasi & Logo', 'type' => 'header'],
                    ['icon' => 'fa-image', 'name' => 'Hero', 'desc' => 'Banner utama', 'type' => 'hero'],
                    ['icon' => 'fa-grip', 'name' => 'Product Grid', 'desc' => 'Grid Produk', 'type' => 'product_grid'],
                    ['icon' => 'fa-users', 'name' => 'About', 'desc' => 'Tentang Kami', 'type' => 'about'],
                    ['icon' => 'fa-bullhorn', 'name' => 'CTA', 'desc' => 'Call To Action', 'type' => 'cta'],
                    ['icon' => 'fa-envelope', 'name' => 'Form Kontak', 'desc' => 'Formulir Kontak', 'type' => 'contact_form'],
                    ['icon' => 'fa-map-marker-alt', 'name' => 'Lokasi', 'desc' => 'Peta & Alamat', 'type' => 'location'],
                    ['icon' => 'fa-bars', 'name' => 'Footer', 'desc' => 'Footer Situs', 'type' => 'footer'],
                    ['icon' => 'fa-text-width', 'name' => 'Text Block', 'desc' => 'Blok Teks', 'type' => 'text_block'],
                    ['icon' => 'fa-image', 'name' => 'Image Block', 'desc' => 'Blok Gambar', 'type' => 'image_block'],
                    ['icon' => 'fa-video', 'name' => 'Video', 'desc' => 'Embed Video', 'type' => 'video'],
                    ['icon' => 'fa-quote-right', 'name' => 'Testimonial', 'desc' => 'Testimoni Pelanggan', 'type' => 'testimonial'],
                ] as $component)
                <div class="component-item border border-gray-300 rounded-lg p-3 hover:border-primary hover:bg-primary-100 cursor-pointer transition draggable" 
                     draggable="true"
                     data-type="{{ $component['type'] }}"
                     data-name="{{ $component['name'] }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $component['icon'] }} text-primary text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $component['name'] }}</h3>
                            <p class="text-xs text-gray-600">{{ $component['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Canvas Area -->
<div class="flex-1 bg-gray-50 p-2 sm:p-6 overflow-auto">
    <div id="canvas" class="bg-white rounded-xl shadow-sm min-h-full border-2 border-dashed border-gray-300 p-4 sm:p-8 drop-zone custom-scrollbar">
        @if($currentTemplate && $templateContent)
            <!-- Tampilkan template content -->
            <div id="template-content">
                {!! $templateContent !!}
            </div>
        @else
            <!-- Canvas Kosong -->
            <div class="text-center py-10 sm:py-20">
                <i class="fas fa-file text-primary text-4xl sm:text-6xl mb-4"></i>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Kanvas Kosong</h2>
                <p class="text-gray-600 text-sm sm:text-base mb-4 sm:mb-6">Drag komponen dari sidebar kiri untuk memulai</p>
                
                <!-- Mobile Instruction -->
                <div class="sm:hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-blue-700 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tap tombol menu di atas untuk membuka komponen
                    </p>
                </div>
                
                <!-- Placeholder untuk drop components -->
                <div class="component-placeholder rounded-lg mb-4" data-drop-zone="main">
                    <div class="text-center">
                        <i class="fas fa-plus text-xl sm:text-2xl mb-2"></i>
                        <p class="text-xs sm:text-sm">Drop komponen di sini</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
        
        <!-- Right Sidebar - Settings -->
        <div id="rightSidebar" class="mobile-sidebar-right sm:relative sm:right-0 sm:w-80 bg-white border-l border-gray-300 flex flex-col z-40">
            <!-- Settings Header -->
            <div class="p-4 border-b border-gray-300 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Pengaturan</h2>
                    <p class="text-sm text-gray-600" id="settings-title">Pilih komponen untuk mengedit</p>
                </div>
                <button id="closeRightSidebar" class="sm:hidden w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>
            
            <!-- Settings Content -->
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                <div id="component-settings" class="space-y-4">
                    <p class="text-sm text-gray-500 text-center">Pilih komponen di canvas untuk mengedit pengaturannya</p>
                </div>

                <!-- Global Settings -->
                <div id="global-settings" class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">Pengaturan Global</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Warna Primer</label>
                            <input type="color" id="globalPrimaryColor" class="w-full h-10 rounded-lg border border-gray-300" value="#009689">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Font Keluarga</label>
                            <select id="globalFontFamily" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="Inter">Inter</option>
                                <option value="Arial">Arial</option>
                                <option value="Helvetica">Helvetica</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Times New Roman">Times New Roman</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Background Halaman</label>
                            <input type="color" id="globalBackgroundColor" class="w-full h-10 rounded-lg border border-gray-300" value="#ffffff">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Lebar Maksimum</label>
                            <select id="globalMaxWidth" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="100%">Full Width</option>
                                <option value="1200px">1200px</option>
                                <option value="1000px">1000px</option>
                                <option value="800px">800px</option>
                            </select>
                        </div>
                        <button id="applyGlobalSettings" class="w-full bg-primary text-white py-2 rounded-lg font-medium hover:bg-teal-700 transition">
                            Terapkan Pengaturan Global
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-11/12 sm:w-96 mx-4">
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-shopping-cart text-orange-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Pembayaran Diperlukan</h3>
            <p class="text-gray-600 text-sm">
                Template "<span id="templateNameModal"></span>" adalah template premium yang memerlukan pembayaran sebelum dapat dipublish.
            </p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-700">Harga Template:</span>
                <span class="font-bold text-primary" id="templatePriceModal"></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">Status:</span>
                <span class="font-semibold text-orange-500">Belum Dibayar</span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2">
            <button id="cancelPayment" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                Nanti Saja
            </button>
            <a href="#" id="checkoutButton" class="flex-1 bg-primary hover:bg-teal-700 text-white px-4 py-3 rounded-lg font-medium text-center transition">
                <i class="fas fa-credit-card mr-2"></i>Checkout Sekarang
            </a>
        </div>
    </div>
</div>
    <!-- Image Upload Modal -->
    <div id="imageUploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-11/12 sm:w-96 mx-4">
            <h3 class="text-lg font-bold mb-4">Upload Gambar</h3>
            <input type="file" id="imageInput" accept="image/*" class="w-full mb-4 border border-gray-300 rounded-lg p-2">
            <div class="flex gap-2 justify-end">
                <button id="cancelUpload" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button id="confirmUpload" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-teal-700 transition">Upload</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 w-11/12 sm:w-96 mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2" id="successTitle">Berhasil!</h3>
            <p class="text-gray-600 mb-6" id="successMessage">Operasi berhasil dilakukan.</p>
            <button id="closeSuccessModal" class="w-full bg-primary text-white py-3 rounded-lg font-medium hover:bg-teal-700 transition">
                Tutup
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let customData = {};
            let selectedComponent = null;
            const currentTemplateId = {{ $currentTemplate->id ?? 'null' }};
            const currentTemplatePrice = {{ $currentTemplate->price ?? 0 }};
            const userHasPaid = {{ $userHasPaid ? 'true' : 'false' }};
            
            // Load template content jika ada
            if ({{ $currentTemplate ? 'true' : 'false' }}) {
                console.log('Loading template content for ID:', {{ $currentTemplate->id ?? 'null' }});
                
                // Jika sudah ada userTemplate dengan custom_data, gunakan itu
                @if($userTemplate && !empty($userTemplate->custom_data))
                    console.log('Using saved custom data');
                    // customData sudah di-set dari userTemplate
                @else
                    console.log('Using template content');
                    // customData akan menggunakan templateContent
                @endif
            }
            
            // Function untuk refresh template content
            function refreshTemplateContent(templateId) {
                if (!templateId) return;
                
                fetch(`/editor/get-template-content/${templateId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.content) {
                        const canvas = document.getElementById('canvas');
                        const templateContentDiv = document.getElementById('template-content');
                        
                        if (!templateContentDiv) {
                            canvas.innerHTML = `<div id="template-content">${data.content.html}</div>`;
                        } else {
                            templateContentDiv.innerHTML = data.content.html;
                        }
                        
                        // Re-initialize component events
                        const components = canvas.querySelectorAll('.component');
                        components.forEach(component => {
                            const type = component.dataset.type;
                            const settings = getSettingsByType(type);
                            initializeComponentEvents(component, settings);
                        });
                        
                        showNotification('Template content loaded successfully!', 'success');
                    } else {
                        showNotification('Failed to load template content', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading template content:', error);
                    showNotification('Error loading template content', 'error');
                });
            }
            
            // Panggil refresh saat template selector berubah
            document.getElementById('templateSelector')?.addEventListener('change', function() {
                if (this.value) {
                    refreshTemplateContent(this.value);
                }
            });
            
            // Mobile Menu Functionality
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileSettingsBtn = document.getElementById('mobileSettingsBtn');
            const mobilePreviewBtn = document.getElementById('mobilePreviewBtn');
            const closeLeftSidebar = document.getElementById('closeLeftSidebar');
            const closeRightSidebar = document.getElementById('closeRightSidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const leftSidebar = document.getElementById('leftSidebar');
            const rightSidebar = document.getElementById('rightSidebar');
            
            // Mobile menu handlers
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    leftSidebar.classList.add('active');
                    mobileOverlay.classList.add('active');
                });
            }
            
            if (mobileSettingsBtn) {
                mobileSettingsBtn.addEventListener('click', function() {
                    rightSidebar.classList.add('active');
                    mobileOverlay.classList.add('active');
                });
            }
            
            if (mobilePreviewBtn) {
                mobilePreviewBtn.addEventListener('click', function() {
                    if (currentTemplateId) {
                        window.open(`/editor/preview/${currentTemplateId}`, '_blank');
                    } else {
                        showNotification('Pilih template terlebih dahulu!', 'warning');
                    }
                });
            }
            
            if (closeLeftSidebar) {
                closeLeftSidebar.addEventListener('click', closeAllMobileMenus);
            }
            
            if (closeRightSidebar) {
                closeRightSidebar.addEventListener('click', closeAllMobileMenus);
            }
            
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeAllMobileMenus);
            }
            
            function closeAllMobileMenus() {
                leftSidebar.classList.remove('active');
                rightSidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            }
            
            // Payment Modal Logic
            const paymentModal = document.getElementById('paymentModal');
            const cancelPayment = document.getElementById('cancelPayment');
            const checkoutButton = document.getElementById('checkoutButton');
            const templateNameModal = document.getElementById('templateNameModal');
            const templatePriceModal = document.getElementById('templatePriceModal');
            
            if (cancelPayment) {
                cancelPayment.addEventListener('click', function() {
                    paymentModal.classList.add('hidden');
                });
            }
            
            // Success Modal
            const successModal = document.getElementById('successModal');
            const closeSuccessModal = document.getElementById('closeSuccessModal');
            const successTitle = document.getElementById('successTitle');
            const successMessage = document.getElementById('successMessage');
            
            if (closeSuccessModal) {
                closeSuccessModal.addEventListener('click', function() {
                    successModal.classList.add('hidden');
                });
            }
            
            // Global Settings
            const applyGlobalSettings = document.getElementById('applyGlobalSettings');
            if (applyGlobalSettings) {
                applyGlobalSettings.addEventListener('click', function() {
                    const primaryColor = document.getElementById('globalPrimaryColor').value;
                    const fontFamily = document.getElementById('globalFontFamily').value;
                    const backgroundColor = document.getElementById('globalBackgroundColor').value;
                    const maxWidth = document.getElementById('globalMaxWidth').value;
                    
                    // Apply global settings to all components
                    document.querySelectorAll('.component').forEach(component => {
                        component.style.fontFamily = fontFamily;
                        component.style.maxWidth = maxWidth;
                    });
                    
                    // Update CSS variables
                    document.documentElement.style.setProperty('--primary-color', primaryColor);
                    document.documentElement.style.setProperty('--background-color', backgroundColor);
                    
                    showNotification('Pengaturan global berhasil diterapkan!', 'success');
                    updateCustomData();
                });
            }
            
            // Template Selector
            const templateSelector = document.getElementById('templateSelector');
            if (templateSelector) {
                templateSelector.addEventListener('change', function() {
                    if (this.value) {
                        window.location.href = `/editor/${this.value}`;
                    }
                });
            }
            
            // Save Button
            document.getElementById('saveBtn').addEventListener('click', function() {
                saveTemplate();
            });
            
            document.getElementById('previewBtn').addEventListener('click', function() {
    if (currentTemplateId) {
        // Ganti dengan route yang benar
        window.open(`{{ url('/editor/preview') }}/${currentTemplateId}`, '_blank');
    } else {
        showNotification('Pilih template terlebih dahulu!', 'warning');
    }
});

// Mobile Preview Button - baris sekitar 142
if (mobilePreviewBtn) {
    mobilePreviewBtn.addEventListener('click', function() {
        if (currentTemplateId) {
            // Ganti dengan route yang benar
            window.open(`{{ url('/editor/preview') }}/${currentTemplateId}`, '_blank');
        } else {
            showNotification('Pilih template terlebih dahulu!', 'warning');
        }
    });
}
            const publishBtn = document.getElementById('publishBtn');
if (publishBtn) {
    publishBtn.addEventListener('click', function() {
        if (!currentTemplateId) {
            showNotification('Pilih template terlebih dahulu!', 'warning');
            return;
        }
        
        // Check if template is paid and user hasn't paid
        const templatePrice = parseFloat(this.dataset.templatePrice) || 0;
        const hasPaid = this.dataset.userHasPaid === 'true';
        
        if (templatePrice > 0 && !hasPaid) {
            // Show payment modal
            const modal = document.getElementById('paymentModal');
            const nameEl = document.getElementById('templateNameModal');
            const priceEl = document.getElementById('templatePriceModal');
            const checkoutBtn = document.getElementById('checkoutButton');
            
            // Set nama template
            if (nameEl) {
                nameEl.textContent = '{{ $currentTemplate->name ?? "Template" }}';
            }
            
            // Set harga template
            if (priceEl) {
                const formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(templatePrice);
                priceEl.textContent = formattedPrice;
            }
            
            // ✅ PERBAIKAN: Set href checkout button dengan URL yang benar
            if (checkoutBtn) {
                checkoutBtn.href = `/payment/checkout/${currentTemplateId}`;
            }
            
            // Show modal
            if (modal) {
                modal.classList.remove('hidden');
            }
            return;
        }
        
        // If free template or already paid, proceed with publish
        publishTemplate();
    });
}
            
            // Image Upload Modal
            const imageUploadModal = document.getElementById('imageUploadModal');
            const cancelUpload = document.getElementById('cancelUpload');
            const confirmUpload = document.getElementById('confirmUpload');
            
            if (cancelUpload) {
                cancelUpload.addEventListener('click', function() {
                    imageUploadModal.classList.add('hidden');
                });
            }
            
            if (confirmUpload) {
                confirmUpload.addEventListener('click', function() {
                    const fileInput = document.getElementById('imageInput');
                    if (fileInput.files.length > 0) {
                        uploadImage(fileInput.files[0]);
                    } else {
                        showNotification('Pilih file gambar terlebih dahulu!', 'warning');
                    }
                });
            }
            
            // Reset Button
            document.getElementById('resetBtn').addEventListener('click', function() {
                if (!currentTemplateId) {
                    showNotification('Pilih template terlebih dahulu!', 'warning');
                    return;
                }
                
                if (!confirm('Yakin ingin mereset template? Semua perubahan akan hilang!')) {
                    return;
                }
                
                const btn = this;
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mereset...';
                btn.disabled = true;
                
                fetch('{{ route("editor.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        template_id: currentTemplateId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showSuccessModal('Reset Berhasil', 'Template berhasil direset ke keadaan semula!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification(data.message || 'Gagal mereset template', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat reset: ' + error.message, 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
            
            // Drag & Drop Implementation
            initializeDragAndDrop();
            
            // Initialize from saved data
            if (document.getElementById('saved-content')) {
                initializeFromSavedData();
            }
            
            // Touch device improvements
            function initializeTouchEvents() {
                if ('ontouchstart' in window) {
                    // Better touch handling for components
                    const components = document.querySelectorAll('.component');
                    components.forEach(component => {
                        component.style.cursor = 'pointer';
                        
                        let tapTimer;
                        component.addEventListener('touchstart', function(e) {
                            tapTimer = setTimeout(() => {
                                // Long press - show actions
                                this.classList.add('selected');
                                const settings = getSettingsByType(this.dataset.type);
                                showComponentSettings(settings);
                            }, 500);
                        });
                        
                        component.addEventListener('touchend', function(e) {
                            clearTimeout(tapTimer);
                        });
                        
                        component.addEventListener('touchmove', function(e) {
                            clearTimeout(tapTimer);
                        });
                    });
                    
                    // Improve draggable touch experience
                    const draggables = document.querySelectorAll('.draggable');
                    draggables.forEach(draggable => {
                        draggable.style.cursor = 'grab';
                    });
                }
            }
            
            // Initialize touch events
            initializeTouchEvents();
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeAllMobileMenus();
                }
            });
            
            // Prevent zoom on double tap (mobile)
            document.addEventListener('touchend', function(e) {
                if (e.touches && e.touches.length < 2) {
                    return;
                }
                
                e.preventDefault();
                e.stopPropagation();
            }, { passive: false });
            
            function saveTemplate() {
                if (!currentTemplateId) {
                    showNotification('Pilih template terlebih dahulu!', 'warning');
                    return;
                }
                
                const btn = document.getElementById('saveBtn');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                btn.disabled = true;
                
                updateCustomData();
                
                fetch('{{ route("editor.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        template_id: currentTemplateId,
                        custom_data: customData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal('Simpan Berhasil', 'Perubahan berhasil disimpan!');
                    } else {
                        showNotification('Gagal menyimpan perubahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menyimpan', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
            
            function publishTemplate() {
                if (!currentTemplateId) {
                    showNotification('Pilih template terlebih dahulu!', 'warning');
                    return;
                }
                
                if (!confirm('Yakin ingin mempublish template ini?')) {
                    return;
                }
                
                const btn = document.getElementById('publishBtn');
                const originalText = btn.innerHTML;
                
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Publishing...';
                btn.disabled = true;
                
                fetch('{{ route("editor.publish") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        template_id: currentTemplateId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal('Publish Berhasil', data.message || 'Template berhasil dipublish!');
                        if (data.redirect_url) {
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 2000);
                        }
                    } else {
                        showNotification('Gagal mempublish template', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat mempublish', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
            
            function uploadImage(file) {
                const formData = new FormData();
                formData.append('image', file);
                
                fetch('{{ route("editor.upload-image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (selectedComponent) {
                            // Update image in selected component
                            const imgElement = selectedComponent.querySelector('img');
                            if (imgElement) {
                                imgElement.src = data.url;
                            }
                        }
                        imageUploadModal.classList.add('hidden');
                        showNotification('Gambar berhasil diupload!', 'success');
                        updateCustomData();
                    } else {
                        showNotification('Gagal mengupload gambar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat upload', 'error');
                });
            }
            
            function initializeDragAndDrop() {
                const draggables = document.querySelectorAll('.draggable');
                const dropZones = document.querySelectorAll('.drop-zone');
                
                draggables.forEach(draggable => {
                    draggable.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify({
                            type: this.dataset.type,
                            name: this.dataset.name
                        }));
                        this.classList.add('dragging');
                    });
                    
                    draggable.addEventListener('dragend', function() {
                        this.classList.remove('dragging');
                    });
                });
                
                dropZones.forEach(zone => {
                    zone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('active');
                    });
                    
                    zone.addEventListener('dragleave', function() {
                        this.classList.remove('active');
                    });
                    
                    zone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('active');
                        
                        const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                        addComponentToCanvas(data.type, data.name, this);
                    });
                });
            }
            
            function addComponentToCanvas(type, name, zone) {
                const components = {
                    header: {
                        html: `
                            <div class="component border-2 border-primary rounded-lg p-4 mb-4 relative" data-type="header">
                                <div class="component-actions flex gap-1">
                                    <button class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs edit-component">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs delete-component">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="bg-gray-100 rounded p-6 text-center">
                                    <h3 class="text-lg font-semibold mb-2">Header Section</h3>
                                    <p class="text-gray-600">Navigation menu and logo area</p>
                                    <div class="mt-4 flex justify-center space-x-4">
                                        <span class="text-sm text-gray-500">Home</span>
                                        <span class="text-sm text-gray-500">About</span>
                                        <span class="text-sm text-gray-500">Services</span>
                                        <span class="text-sm text-gray-500">Contact</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        settings: generateHeaderSettings()
                    },
                    hero: {
                        html: `
                            <div class="component border-2 border-blue-500 rounded-lg p-4 mb-4 relative" data-type="hero">
                                <div class="component-actions flex gap-1">
                                    <button class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs edit-component">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs delete-component">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="bg-gradient-to-r from-blue-500 to-teal-600 rounded-lg p-12 text-center text-white">
                                    <h2 class="text-4xl font-bold mb-4">Welcome to Our Website</h2>
                                    <p class="text-xl mb-6 opacity-90">Build amazing websites with our easy-to-use template editor</p>
                                    <div class="flex justify-center space-x-4">
                                        <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Get Started</button>
                                        <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">Learn More</button>
                                    </div>
                                </div>
                            </div>
                        `,
                        settings: generateHeroSettings()
                    },
                    text_block: {
                        html: `
                            <div class="component border-2 border-green-500 rounded-lg p-4 mb-4 relative" data-type="text_block">
                                <div class="component-actions flex gap-1">
                                    <button class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs edit-component">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs delete-component">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="prose max-w-none">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Our Story</h3>
                                    <p class="text-gray-700 mb-4 leading-relaxed">
                                        This is a sample text block that you can customize with your own content. 
                                        You can edit title, text, and styling through settings panel on the right.
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        Add multiple paragraphs, change fonts, adjust colors, and make this text block 
                                        perfectly match your website's design and tone.
                                    </p>
                                </div>
                            </div>
                        `,
                        settings: generateTextBlockSettings()
                    },
                    product_grid: {
                        html: `
                            <div class="component border-2 border-purple-500 rounded-lg p-4 mb-4 relative" data-type="product_grid">
                                <div class="component-actions flex gap-1">
                                    <button class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs edit-component">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs delete-component">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">Featured Products</h3>
                                    <p class="text-gray-600">Amazing products waiting for you</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    ${Array.from({length: 3}, (_, i) => `
                                        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                            <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Product ${i + 1}</h4>
                                            <p class="text-gray-600 text-sm mb-3">Amazing product description</p>
                                            <div class="text-primary font-bold">$99.99</div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `,
                        settings: generateProductGridSettings()
                    },
                    about: {
                        html: `
                            <div class="component border-2 border-orange-500 rounded-lg p-4 mb-4 relative" data-type="about">
                                <div class="component-actions flex gap-1">
                                    <button class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs edit-component">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs delete-component">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                    <div>
                                        <h3 class="text-3xl font-bold text-gray-900 mb-4">About Our Company</h3>
                                        <p class="text-gray-700 mb-4 leading-relaxed">
                                            We are dedicated to providing best services and solutions for our customers. 
                                            Our team of experts works tirelessly to ensure your success.
                                        </p>
                                        <p class="text-gray-700 leading-relaxed">
                                            With years of experience and a passion for innovation, we strive to exceed 
                                            expectations and deliver outstanding results.
                                        </p>
                                    </div>
                                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                                        <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600">About us section image</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        settings: generateAboutSettings()
                    }
                };
                
                if (components[type]) {
                    // Remove placeholder jika ada
                    const placeholder = zone.querySelector('.component-placeholder');
                    if (placeholder) {
                        placeholder.remove();
                    }
                    
                    zone.insertAdjacentHTML('beforeend', components[type].html);
                    
                    const newComponent = zone.lastElementChild;
                    initializeComponentEvents(newComponent, components[type].settings);
                    updateCustomData();
                    
                    showNotification(`Komponen "${name}" berhasil ditambahkan!`, 'success');
                } else {
                    console.warn('Component type not found:', type);
                    showNotification('Komponen tidak ditemukan', 'error');
                }
            }
            
            function initializeComponentEvents(component, settings) {
                // Click to select
                component.addEventListener('click', function(e) {
                    if (!e.target.closest('.component-actions')) {
                        selectComponent(this, settings);
                    }
                });
                
                // Delete button
                const deleteBtn = component.querySelector('.delete-component');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (confirm('Hapus komponen ini?')) {
                            component.remove();
                            selectedComponent = null;
                            showDefaultSettings();
                            updateCustomData();
                            showNotification('Komponen berhasil dihapus', 'success');
                        }
                    });
                }
                
                // Edit button
                const editBtn = component.querySelector('.edit-component');
                if (editBtn) {
                    editBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectComponent(component, settings);
                    });
                }
            }
            
            function selectComponent(component, settings) {
                console.log('Selecting component:', component.dataset.type);
                
                // Remove selection from all components
                document.querySelectorAll('.component').forEach(comp => {
                    comp.classList.remove('selected');
                });
                
                // Add selection to clicked component
                component.classList.add('selected');
                selectedComponent = component;
                
                // Show settings
                showComponentSettings(settings);
            }
            
            function showComponentSettings(settingsHtml) {
                const settingsPanel = document.getElementById('component-settings');
                const settingsTitle = document.getElementById('settings-title');
                
                if (settingsPanel && settingsTitle) {
                    settingsPanel.innerHTML = settingsHtml;
                    settingsTitle.textContent = 'Edit Komponen';
                    
                    // Initialize event listeners for settings inputs
                    initializeSettingsEvents();
                }
            }
            
            function showDefaultSettings() {
                const settingsPanel = document.getElementById('component-settings');
                const settingsTitle = document.getElementById('settings-title');
                
                if (settingsPanel && settingsTitle) {
                    settingsPanel.innerHTML = '<p class="text-sm text-gray-500 text-center">Pilih komponen di canvas untuk mengedit pengaturannya</p>';
                    settingsTitle.textContent = 'Pengaturan';
                }
            }
            
            function initializeSettingsEvents() {
                // Add real-time updates for settings inputs
                const inputs = document.querySelectorAll('#component-settings input, #component-settings textarea, #component-settings select');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        updateComponentFromSettings();
                    });
                    
                    input.addEventListener('change', function() {
                        updateComponentFromSettings();
                    });
                });
            }
            
            function updateComponentFromSettings() {
                if (!selectedComponent) return;
                
                // Implement real-time updates based on settings
                // This would update component based on settings panel values
                console.log('Updating component from settings');
                updateCustomData();
            }
            
            function updateCustomData() {
                const canvas = document.getElementById('canvas');
                if (canvas) {
                    customData = canvas.innerHTML;
                    console.log('Custom data updated');
                }
            }
            
            function initializeFromSavedData() {
                const canvas = document.getElementById('canvas');
                const components = canvas.querySelectorAll('.component');
                
                console.log('Initializing from saved data:', components.length, 'components');
                
                components.forEach(component => {
                    const type = component.dataset.type;
                    const settings = getSettingsByType(type);
                    initializeComponentEvents(component, settings);
                });
            }
            
            function getSettingsByType(type) {
                const settingsMap = {
                    header: generateHeaderSettings(),
                    hero: generateHeroSettings(),
                    text_block: generateTextBlockSettings(),
                    product_grid: generateProductGridSettings(),
                    about: generateAboutSettings()
                };
                
                return settingsMap[type] || '<p class="text-sm text-gray-500 text-center">Settings not available for this component</p>';
            }
            
            // Settings generators
            function generateHeaderSettings() {
                return `
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Header Settings</h3>
                        <div>
                            <label class="block text-sm font-medium mb-1">Logo Text</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="My Logo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Background Color</label>
                            <input type="color" class="w-full h-10 rounded-lg border border-gray-300" value="#ffffff">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Menu Items</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" rows="3">Home, About, Services, Contact</textarea>
                            <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                        </div>
                    </div>
                `;
            }
            
            function generateHeroSettings() {
                return `
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Hero Settings</h3>
                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Welcome to Our Website">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" rows="3">Build amazing websites with our easy-to-use template editor</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Primary Button Text</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Get Started">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Secondary Button Text</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Learn More">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Background Image</label>
                            <button class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-left bg-white hover:bg-gray-50 transition" onclick="openImageUpload()">
                                <i class="fas fa-image mr-2 text-gray-400"></i>Upload Image
                            </button>
                        </div>
                    </div>
                `;
            }
            
            function generateTextBlockSettings() {
                return `
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Text Block Settings</h3>
                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Welcome to Our Story">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Content</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" rows="5">This is a sample text block that you can customize with your own content. You can edit title, text, and styling through settings panel on the right.</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Text Alignment</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="left">Left</option>
                                <option value="center">Center</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                    </div>
                `;
            }
            
            function generateProductGridSettings() {
                return `
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Product Grid Settings</h3>
                        <div>
                            <label class="block text-sm font-medium mb-1">Section Title</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Featured Products">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Section Description</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="Amazing products waiting for you">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Number of Products</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="3">3 Products</option>
                                <option value="4">4 Products</option>
                                <option value="6">6 Products</option>
                            </select>
                        </div>
                    </div>
                `;
            }
            
            function generateAboutSettings() {
                return `
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">About Section Settings</h3>
                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" value="About Our Company">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Content</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" rows="4">We are dedicated to providing best services and solutions for our customers. Our team of experts works tirelessly to ensure your success.</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Image Position</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="left">Image on Left</option>
                                <option value="right">Image on Right</option>
                            </select>
                        </div>
                    </div>
                `;
            }
            
            function showNotification(message, type = 'info') {
                // Buat elemen notifikasi
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 flex items-center space-x-2 transform transition-all duration-500 translate-x-full`;
                
                // Tentukan ikon dan warna berdasarkan tipe
                let icon = '';
                let bgColor = '';
                switch(type) {
                    case 'success':
                        icon = 'fa-check-circle';
                        bgColor = 'bg-green-500';
                        break;
                    case 'error':
                        icon = 'fa-exclamation-circle';
                        bgColor = 'bg-red-500';
                        break;
                    case 'warning':
                        icon = 'fa-exclamation-triangle';
                        bgColor = 'bg-yellow-500';
                        break;
                    default: // info
                        icon = 'fa-info-circle';
                        bgColor = 'bg-blue-500';
                }
                
                notification.classList.add(bgColor);
                notification.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                // Trigger animasi masuk
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                    notification.classList.add('translate-x-0');
                }, 100);
                
                // Hapus notifikasi setelah 3 detik
                setTimeout(() => {
                    notification.classList.remove('translate-x-0');
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }
            
            function showSuccessModal(title, message) {
                successTitle.textContent = title;
                successMessage.textContent = message;
                successModal.classList.remove('hidden');
            }
            
            // Global function for image upload
            window.openImageUpload = function() {
                imageUploadModal.classList.remove('hidden');
            };
        });
    </script>
</body>
</html>