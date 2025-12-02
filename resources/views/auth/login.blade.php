<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Temploka</title>
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

        /* ==============================================
            RESPONSIVE FIX – PERBAIKAN LAYOUT DESKTOP
        =============================================== */

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-container {
            width: 100%;
            padding: 2rem;
        }

        @media (max-width: 1024px) {
            .main-container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body class="bg-custom-gradient">

    <div class="main-container flex items-center justify-center">
        <div class="flex flex-col items-center w-full max-w-7xl px-4 lg:px-8">

            <!-- Wrapper -->
            <div class="w-full max-w-[420px] md:max-w-[480px] xl:max-w-[560px]">

                <!-- Brand -->
                <div class="text-center mb-6">
                    <h1 class="font-black text-2xl lg:text-[32px]">Temploka</h1>
                    <p class="text-sm lg:text-lg text-black">Transformasi Digital Usaha Anda</p>
                </div>

                <!-- Tabs -->
                <div class="flex bg-primary-100 rounded-[20px] p-1 lg:p-2 w-full max-w-[240px] mx-auto mb-6">
                    <a href="{{ route('login') }}" 
                       class="flex-1 bg-primary text-white py-3 px-4 rounded-[20px] text-center font-bold text-sm lg:text-[18px]">
                       Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="flex-1 text-primary py-3 px-4 rounded-[20px] text-center font-medium text-sm lg:text-[18px] hover:bg-primary-50 transition">
                       Daftar
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-[20px] p-6 shadow-md">

                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="font-semibold text-lg">Selamat Datang Kembali</h2>
                        <p class="text-sm text-gray-700">Masuk ke akun Temploka Anda</p>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="font-bold text-sm">Email</label>
                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-envelope text-gray-500 text-lg"></i>
                                <input 
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="nama@mail.com">
                            </div>

                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex justify-between items-center">
                                <label class="font-bold text-sm">Password</label>

                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="font-bold text-xs text-primary hover:underline">
                                   Lupa Password?
                                </a>
                                @endif
                            </div>

                            <div class="flex items-center bg-primary-50 rounded-[20px] px-5 py-4 mt-2">
                                <i class="far fa-lock text-gray-500 text-lg"></i>
                                <input 
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full bg-transparent ml-3 focus:outline-none"
                                    placeholder="******">

                                <button type="button" class="toggle-password text-gray-500 hover:text-gray-700">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember -->
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="w-4 h-4">
                            <label for="remember" class="ml-2 text-sm text-gray-700">Ingat saya</label>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-primary hover:bg-teal-700 text-white py-4 rounded-[20px] font-bold text-lg transition">
                            Masuk
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password')?.addEventListener('click', function () {
            const input = document.querySelector('input[name="password"]');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>

</body>
</html>
