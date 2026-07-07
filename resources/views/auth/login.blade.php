<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Peminjaman Lab PCR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: '#0B2B36',
                        secondary: '#005C6E',
                        accent: '#4ADE80'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased selection:bg-accent selection:text-primary">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side: Branding -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-primary to-secondary text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-accent/20 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <!-- Icon/Logo -->
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 border border-white/20">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4 tracking-tight">
                    Sistem Peminjaman Laboratorium
                </h1>
                <p class="text-lg md:text-xl text-teal-100 font-light opacity-90">
                    Politeknik Caltex Riau
                </p>
            </div>
            
            <div class="relative z-10 text-sm text-teal-200/60">
                &copy; {{ date('Y') }} Politeknik Caltex Riau. All rights reserved.
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white relative">
            <div class="w-full max-w-md">
                
                <!-- Mobile Branding (Hidden on Desktop) -->
                <div class="md:hidden text-center mb-10">
                    <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary/30">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Sistem Peminjaman Lab</h2>
                    <p class="text-sm text-gray-500 mt-1">Politeknik Caltex Riau</p>
                </div>

                <div class="mb-10 text-left">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight">Selamat Datang </h2>
                    <p class="text-gray-500">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-sm font-semibold text-red-800">Login Gagal!</h3>
                            <p class="text-sm text-red-700 mt-1">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ url('/login-web') }}" method="POST" class="space-y-6">
                    @csrf 
                    
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all duration-200 outline-none" placeholder="email@mahasiswa.pcr.ac.id" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all duration-200 outline-none" placeholder="Masukkan password Anda" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 px-4 bg-primary hover:bg-primary/90 text-white font-semibold rounded-2xl shadow-lg shadow-primary/25 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        Masuk ke Sistem
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="/register" class="font-semibold text-secondary hover:text-primary transition-colors">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>