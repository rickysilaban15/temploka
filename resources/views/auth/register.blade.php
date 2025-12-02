<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Temploka</title>
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

        .bg-primary-50 { background: rgba(0, 150, 137, 0.05); }
        .bg-primary-100 { background: rgba(0, 150, 137, 0.1); }
        .bg-primary { background: #009689; }
        .text-primary { color: #009689; }

        /* ================================
           RESPONSIVENESS FIXED — UPDATED
        ================================ */

        /* Hapus height full agar tidak memaksa konten */
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container kini fleksibel dan tidak overflow */
        .main-container {
            width: 100%;
            padding: 2rem;
        }

        /* Mobile Optimization */
        @media (max-width: 1024px) {
            .main-container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body class="bg-custom-gradient">

    <div class="main-container flex items-center justify-center">
        <div class="flex flex-col items-center justify-center w-full max-w-7xl px-4 lg:px-8">

            <!-- FORM WRAPPER -->
            <div class="w-full max-w-[420px] md:max-w-[480px] xl:max-w-[560px] /* UPDATED */">

                <!-- Brand -->
                <div class="text-center mb-4">
                    <h1 class="font-black text-2xl lg:text-[32px]">Temploka</h1>
                    <p class="text-sm lg:text-lg text-black">Transformasi Digital Usaha Anda</p>
                </div>

                <!-- Tabs -->
                <div class="flex bg-primary-100 rounded-[20px] p-1 lg:p-2 w-full max-w-[240px] mx-auto mb-4">
                    <a href="{{ route('login') }}" class="flex-1 text-primary py-3 px-4 rounded-[20px] text-center font-medium text-sm lg:text-[18px] hover:bg-primary-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 bg-primary text-white py-3 px-4 rounded-[20px] text-center font-bold text-sm lg:text-[18px]">
                        Daftar
                    </a>
                </div>

                <!-- Form -->
                <div class="bg-white rounded-[20px] p-6 shadow-md /* UPDATED */">

                    <div class="mb-6">
                        <h2 class="font-semibold text-lg">Buat Akun Baru</h2>
                        <p class="text-gray-700 text-sm">Bergabung dengan Temploka sekarang</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label class="font-bold text-sm">Nama Lengkap</label>
                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-user text-gray-500 text-lg"></i>
                                <input 
                                    type="text" name="name" value="{{ old('name') }}" 
                                    required autocomplete="name"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="Nama lengkap"
                                >
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="font-bold text-sm">Email</label>
                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-envelope text-gray-500 text-lg"></i>
                                <input 
                                    type="email" name="email" value="{{ old('email') }}"
                                    required autocomplete="email"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="nama@mail.com"
                                >
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="font-bold text-sm">Password</label>
                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-lock text-gray-500 text-lg"></i>
                                <input 
                                    type="password" name="password"
                                    required autocomplete="new-password"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="******"
                                >
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="font-bold text-sm">Konfirmasi Password</label>
                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-lock text-gray-500 text-lg"></i>
                                <input 
                                    type="password" name="password_confirmation"
                                    required autocomplete="new-password"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="******"
                                >
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start space-x-3">
                            <input id="terms" type="checkbox" required class="w-4 h-4 mt-1">
                            <label for="terms" class="text-sm text-gray-600">
                                Saya setuju dengan 
                                <a href="#" class="text-primary font-semibold hover:underline">Syarat & Ketentuan</a> 
                                dan 
                                <a href="#" class="text-primary font-semibold hover:underline">Kebijakan Privasi</a>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full bg-primary hover:bg-teal-700 text-white py-4 rounded-[20px] font-bold text-lg transition duration-150">
                            Daftar
                        </button>
                    </form>

                    <p class="mt-6 text-center text-gray-600 text-sm">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Masuk di sini</a>
                    </p>

                </div>
            </div>

        </div>
    </div>

</body>
</html>
