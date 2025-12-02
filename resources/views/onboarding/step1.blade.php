<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding - Temploka</title>
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
        
        .business-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .business-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .business-card.selected {
            border-color: #009689;
            border-width: 2px;
        }
    </style>
</head>
<body class="bg-custom-gradient min-h-screen">
    <!-- Main Container -->
    <div class="flex flex-col items-start p-0 w-full min-h-screen">
        <!-- Frame 8 -->
        <div class="flex flex-col lg:flex-row justify-between items-center px-4 sm:px-6 lg:px-[100px] py-8 lg:py-[150px] gap-8 lg:gap-[136px] w-full">
            
            <!-- Center Content - Onboarding Form -->
            <div class="flex-1 flex justify-center w-full">
                <!-- Frame 6 -->
                <div class="flex flex-col items-center gap-6 lg:gap-8 w-full max-w-4xl">
                    <!-- Brand Header -->
                    <div class="text-center">
                        <h1 class="font-black text-2xl lg:text-[29px] leading-[30px] lg:leading-[35px] text-black mb-2">Temploka</h1>
                        <p class="text-sm lg:text-[16px] leading-[16px] lg:leading-[19px] text-black">Personalisasi Pengalaman Anda</p>
                    </div>

                    <!-- Progress Section -->
                    <div class="w-full max-w-2xl">
                        <!-- Progress Info -->
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm lg:text-[16px] leading-[16px] lg:leading-[19px] text-black">Langkah 1 dari 3</span>
                            <span class="text-sm lg:text-[16px] leading-[16px] lg:leading-[19px] text-black">33%</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="w-full bg-primary-100 rounded-full h-2 lg:h-[10px] relative">
                            <div class="bg-primary rounded-full h-2 lg:h-[10px] absolute left-0 top-0" style="width: 33%"></div>
                        </div>
                    </div>

                    <!-- Onboarding Form Container -->
                    <div class="bg-white rounded-[20px] w-full p-6 lg:p-8">
                        <!-- Form Header -->
                        <div class="mb-6 lg:mb-8">
                            <h2 class="font-semibold text-lg lg:text-xl text-black mb-2">
                                Pilih Jenis Usaha Anda
                            </h2>
                            <p class="text-sm lg:text-[16px] text-gray-600">
                                Pilih kategori yang paling sesuai dengan bisnis Anda
                            </p>
                        </div>

                        <!-- Business Type Selection -->
                        <form method="POST" action="{{ route('onboarding.storeBusinessType') }}" class="space-y-6">
                            @csrf
                            
                            <!-- First Row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                                <!-- Retail Card -->
                                <div class="business-card border border-gray-200 rounded-[20px] shadow-card overflow-hidden bg-white cursor-pointer" onclick="selectBusinessType('retail')">
                                    <div class="h-32 lg:h-48 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-store text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 lg:p-6">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                                <i class="fas fa-store text-primary text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">Retail</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Toko serba ada, minimarket</p>
                                    </div>
                                </div>

                                <!-- Culinary Card -->
                                <div class="business-card border border-gray-200 rounded-[20px] shadow-card overflow-hidden bg-white cursor-pointer" onclick="selectBusinessType('culinary')">
                                    <div class="h-32 lg:h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-utensils text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 lg:p-6">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                                <i class="fas fa-utensils text-primary text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">Kuliner</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Restoran, kafe, catering</p>
                                    </div>
                                </div>

                                <!-- Fashion Card -->
                                <div class="business-card border border-gray-200 rounded-[20px] shadow-card overflow-hidden bg-white cursor-pointer" onclick="selectBusinessType('fashion')">
                                    <div class="h-32 lg:h-48 bg-gradient-to-br from-pink-50 to-pink-100 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-tshirt text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 lg:p-6">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                                <i class="fas fa-tshirt text-primary text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">Fashion</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Pakaian, aksesoris, sepatu</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <!-- Services Card -->
                                <div class="business-card border border-gray-200 rounded-[20px] shadow-card overflow-hidden bg-white cursor-pointer" onclick="selectBusinessType('services')">
                                    <div class="h-32 lg:h-48 bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-tools text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 lg:p-6">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                                <i class="fas fa-tools text-primary text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">Jasa</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Konsultan, servis, freelance</p>
                                    </div>
                                </div>

                                <!-- Other Card -->
                                <div class="business-card border border-gray-200 rounded-[20px] shadow-card overflow-hidden bg-white cursor-pointer" onclick="selectBusinessType('other')">
                                    <div class="h-32 lg:h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-bars text-white text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 lg:p-6">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                                <i class="fas fa-bars text-primary text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-900">Lainnya</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Industri lainnya</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input untuk business type -->
                            <input type="hidden" name="business_type" id="business_type" required>

                            <!-- Continue Button -->
                            <div class="flex justify-end pt-4">
                                <button 
                                    type="submit" 
                                    id="continue-btn"
                                    class="bg-primary hover:bg-teal-700 text-white py-3 lg:py-[15px] px-6 lg:px-8 rounded-[20px] font-bold text-sm lg:text-[18px] transition duration-150 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    disabled
                                >
                                    Lanjutkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedBusinessType = null;

        function selectBusinessType(type) {
            // Remove selected class from all cards
            document.querySelectorAll('.business-card').forEach(card => {
                card.classList.remove('selected', 'border-primary', 'border-2');
                card.classList.add('border-gray-200');
            });

            // Add selected class to clicked card
            const clickedCard = event.currentTarget;
            clickedCard.classList.remove('border-gray-200');
            clickedCard.classList.add('selected', 'border-primary', 'border-2');

            // Set the selected business type
            selectedBusinessType = type;
            document.getElementById('business_type').value = type;

            // Enable continue button
            document.getElementById('continue-btn').disabled = false;
        }

        // Optional: Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && selectedBusinessType && !document.getElementById('continue-btn').disabled) {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>