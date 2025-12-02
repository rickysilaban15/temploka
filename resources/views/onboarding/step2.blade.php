<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Step 2 - Temploka</title>
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
        
        .goal-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .goal-card:hover {
            transform: translateY(-2px);
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        .goal-card.selected {
            border-color: #009689;
            border-width: 2px;
            background: rgba(0, 150, 137, 0.02);
        }
        
        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 1px solid #D9D9D9;
            border-radius: 5px;
            background: #F9FAFB;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .goal-card.selected .checkbox-custom {
            background: #009689;
            border-color: #009689;
        }
        
        .goal-card.selected .checkbox-custom::after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-custom-gradient min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <!-- Brand Header -->
            <div class="text-center">
                <h1 class="font-black text-2xl lg:text-[29px] text-black mb-2">Temploka</h1>
                <p class="text-sm lg:text-[16px] text-black">Personalisasi Pengalaman Anda</p>
            </div>

            <!-- Progress Section -->
            <div class="w-full max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm lg:text-[16px] text-black">Langkah 2 dari 3</span>
                    <span class="text-sm lg:text-[16px] text-black">66%</span>
                </div>
                
                <div class="w-full bg-primary-100 rounded-full h-2 relative">
                    <div class="bg-primary rounded-full h-2 absolute left-0 top-0" style="width: 66%"></div>
                </div>
            </div>

            <!-- Onboarding Form -->
            <div class="bg-white rounded-[20px] p-6 lg:p-8 shadow-card">
                <!-- Form Header -->
                <div class="mb-6 lg:mb-8">
                    <h2 class="font-semibold text-lg lg:text-xl text-black mb-2">
                        Tentukan Tujuan Anda
                    </h2>
                    <p class="text-sm lg:text-[16px] text-gray-600">
                        Pilih satu atau lebih tujuan yang ingin Anda capai (bisa lebih dari satu)
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Goals Form -->
                <form method="POST" action="{{ route('onboarding.storeGoals') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Finance Goal -->
                    <div class="goal-card border border-gray-200 rounded-[20px] p-5 bg-white cursor-pointer" onclick="toggleGoal('finance')">
                        <div class="flex items-start gap-4">
                            <div class="checkbox-custom mt-1 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-dollar-sign text-primary text-sm"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Kelola Keuangan</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-12">Atur arus kas, laporan laba rugi</p>
                            </div>
                        </div>
                        <input type="checkbox" name="goals[]" value="finance" class="hidden">
                    </div>

                    <!-- CRM Goal -->
                    <div class="goal-card border border-gray-200 rounded-[20px] p-5 bg-white cursor-pointer" onclick="toggleGoal('crm')">
                        <div class="flex items-start gap-4">
                            <div class="checkbox-custom mt-1 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-users text-primary text-sm"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Customer Relationship</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-12">Database pelanggan & riwayat</p>
                            </div>
                        </div>
                        <input type="checkbox" name="goals[]" value="crm" class="hidden">
                    </div>

                    <!-- E-commerce Goal -->
                    <div class="goal-card border border-gray-200 rounded-[20px] p-5 bg-white cursor-pointer" onclick="toggleGoal('ecommerce')">
                        <div class="flex items-start gap-4">
                            <div class="checkbox-custom mt-1 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-store text-primary text-sm"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Toko Online</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-12">Jual produk secara online</p>
                            </div>
                        </div>
                        <input type="checkbox" name="goals[]" value="ecommerce" class="hidden">
                    </div>

                    <!-- Inventory Goal -->
                    <div class="goal-card border border-gray-200 rounded-[20px] p-5 bg-white cursor-pointer" onclick="toggleGoal('inventory')">
                        <div class="flex items-start gap-4">
                            <div class="checkbox-custom mt-1 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-boxes-packing text-primary text-sm"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Katalog Produk</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-12">Kelola inventori & stok</p>
                            </div>
                        </div>
                        <input type="checkbox" name="goals[]" value="inventory" class="hidden">
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center pt-6">
                        <!-- Back Button -->
                        <a href="{{ route('onboarding.step1') }}" 
                           class="border border-primary text-primary py-3 px-6 rounded-2xl font-medium text-sm lg:text-[18px] transition duration-150 hover:bg-primary-50">
                            Kembali
                        </a>
                        
                        <!-- Continue Button -->
                        <button 
                            type="submit" 
                            id="continue-btn"
                            class="bg-primary hover:bg-teal-700 text-white py-3 px-6 rounded-2xl font-bold text-sm lg:text-[18px] transition duration-150 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled
                        >
                            Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedGoals = [];

        function toggleGoal(goalType) {
            const card = event.currentTarget;
            const checkbox = card.querySelector('input[type="checkbox"]');
            
            if (selectedGoals.includes(goalType)) {
                // Remove goal
                selectedGoals = selectedGoals.filter(g => g !== goalType);
                card.classList.remove('selected');
                checkbox.checked = false;
            } else {
                // Add goal
                selectedGoals.push(goalType);
                card.classList.add('selected');
                checkbox.checked = true;
            }
            
            // Enable/disable continue button
            const continueBtn = document.getElementById('continue-btn');
            continueBtn.disabled = selectedGoals.length === 0;
            
            console.log('Selected goals:', selectedGoals);
        }

        // Enable form submission with Enter key if goals are selected
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && selectedGoals.length > 0) {
                document.querySelector('form').submit();
            }
        });

        // Optional: Add some visual feedback when goals are selected
        document.querySelectorAll('.goal-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add subtle animation
                this.style.transform = 'translateY(-2px)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>