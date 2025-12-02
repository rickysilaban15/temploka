<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Step 3 - Temploka</title>
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
        
        .shadow-medium {
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .template-card {
            transition: all 0.3s ease;
        }
        
        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 35px rgba(0, 0, 0, 0.2);
        }
        
        .feature-badge {
            background: rgba(0, 150, 137, 0.1);
            color: #009689;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-custom-gradient min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl w-full space-y-8">
            <!-- Brand Header -->
            <div class="text-center">
                <h1 class="font-black text-2xl lg:text-[29px] text-black mb-2">Temploka</h1>
                <p class="text-sm lg:text-[16px] text-black">Personalisasi Pengalaman Anda</p>
            </div>

            <!-- Progress Section -->
            <div class="w-full max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm lg:text-[16px] text-black">Langkah 3 dari 3</span>
                    <span class="text-sm lg:text-[16px] text-black">100%</span>
                </div>
                
                <div class="w-full bg-primary-100 rounded-full h-2 relative">
                    <div class="bg-primary rounded-full h-2 absolute left-0 top-0" style="width: 100%"></div>
                </div>
            </div>

            <!-- Onboarding Form -->
            <div class="bg-white rounded-[20px] p-6 lg:p-8 shadow-card">
                <!-- Form Header -->
                <div class="mb-6 lg:mb-8 text-center">
                    <h2 class="font-semibold text-lg lg:text-xl text-black mb-2">
                        Rekomendasi Template untuk Anda
                    </h2>
                    <p class="text-sm lg:text-[16px] text-gray-600">
                        Berdasarkan pilihan Anda, berikut template yang kami rekomendasikan
                    </p>
                </div>

                <!-- Template Recommendations -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($recommendations as $template)
                    <div class="template-card border border-gray-200 rounded-[20px] overflow-hidden bg-white">
                        <!-- Header dengan gradient -->
                        <div class="bg-gradient-to-r {{ $template['color'] }} p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                    <i class="{{ $template['icon'] }} text-xl"></i>
                                </div>
                                <span class="text-xs bg-white bg-opacity-20 px-3 py-1 rounded-full">REKOMENDASI</span>
                            </div>
                            <h3 class="font-bold text-lg mb-2">{{ $template['title'] }}</h3>
                            <p class="text-white text-opacity-90 text-sm">{{ $template['description'] }}</p>
                        </div>
                        
                        <!-- Features -->
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                @foreach($template['features'] as $feature)
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Use Template Button -->
                            <button 
                                onclick="selectTemplate('{{ $template['id'] }}')"
                                class="w-full bg-primary hover:bg-teal-700 text-white py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 transform hover:scale-[1.02]"
                            >
                                Gunakan Template
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                    <!-- Back Button -->
                    <a href="{{ route('onboarding.step2') }}" 
                       class="order-2 sm:order-1 border border-primary text-primary py-3 px-6 rounded-2xl font-medium text-sm lg:text-[18px] transition duration-150 hover:bg-primary-50 w-full sm:w-auto text-center">
                        Kembali
                    </a>
                    
                    <!-- Explore More Button -->
                    <a href="{{ route('templates.index') }}" 
                       class="order-1 sm:order-2 border border-primary text-primary py-3 px-6 rounded-2xl font-medium text-sm lg:text-[18px] transition duration-150 hover:bg-primary-50 w-full sm:w-auto text-center">
                        Jelajahi Lainnya
                    </a>
                    
                    <!-- Start Now Button -->
                    <form method="POST" action="{{ route('onboarding.complete') }}" class="order-3 w-full sm:w-auto">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full bg-primary hover:bg-teal-700 text-white py-3 px-6 rounded-2xl font-bold text-sm lg:text-[18px] transition duration-150 transform hover:scale-[1.02] shadow-medium"
                        >
                            Mulai Sekarang
                        </button>
                    </form>
                </div>

                
            </div>
        </div>
    </div>

    <script>
        function selectTemplate(templateId) {
            // Simpan template yang dipilih (bisa ke session atau langsung proses)
            console.log('Template selected:', templateId);
            
            // Tampilkan konfirmasi atau langsung proses
            showTemplateModal(templateId);
        }

        function showTemplateModal(templateId) {
            // Buat modal konfirmasi sederhana
            const templateNames = {
                'finance-pro': 'Dashboard Keuangan Pro',
                'crm-pro': 'CRM & Database Pelanggan', 
                'ecommerce-pro': 'Toko Online Lengkap',
                'starter': 'Template Starter Pack'
            };
            
            const templateName = templateNames[templateId] || 'Template';
            
            if (confirm(`Apakah Anda yakin ingin menggunakan "${templateName}"?`)) {
                // Redirect ke halaman template atau proses penggunaan template
                window.location.href = `/templates/${templateId}`;
            }
        }

        // Animasi untuk cards
        document.querySelectorAll('.template-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-fade-in-up');
        });

        // Tambahkan CSS animation
        const style = document.createElement('style');
        style.textContent = `
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
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>