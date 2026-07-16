<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Peminjaman Lab</title>
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
    <!-- Tambahan untuk styling chart tooltip -->
    <style>
        #statusPieChart, #labBarChart { font-family: 'Inter', sans-serif !important; }
    </style>
    <!-- Alpine.js for Modal -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo / Brand -->
                    <a href="/dashboard" class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-bold text-lg tracking-tight text-primary">Sistem Peminjaman Lab</span>
                    </a>
                    
                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex md:ml-10 space-x-8">
                        <a href="/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-semibold text-gray-900">
                            Dashboard
                        </a>
                        <a href="/cek-status" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                            Cek Status Ruangan
                        </a>
                    </div>
                </div>

                <div class="flex items-center">
                    @auth
                        <div class="hidden md:flex items-center mr-4">
                            <span class="text-sm text-gray-600 mr-2">Halo,</span>
                            <span class="text-sm font-bold text-primary">{{ auth()->user()->username }}</span>
                        </div>
                        <form action="{{ url('/logout-web') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="/login" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-primary bg-accent hover:bg-accent/90 transition-all">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        <!-- Mobile Nav (Simple fallback for demo) -->
        <div class="md:hidden border-t border-slate-200 px-4 py-2 flex gap-4 overflow-x-auto">
             <a href="/dashboard" class="text-sm font-semibold text-primary">Dashboard</a>
             <a href="/cek-status" class="text-sm font-medium text-gray-500">Cek Status</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                @auth
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang, {{ auth()->user()->username }}!</h1>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-slate-500">Anda login sebagai:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-teal-100 text-teal-800 border border-teal-200">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                @else
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Sistem Informasi Peminjaman Lab</h1>
                    <p class="mt-2 text-slate-500">Silakan login untuk mengajukan formulir peminjaman ruangan.</p>
                @endauth
            </div>
            
            <div class="flex gap-3">
                @if(auth()->check() && in_array(auth()->user()->role, ['laboran', 'kajur', 'wadir']))
                    <a href="/labs" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Lab
                    </a>
                    <a href="/assets" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Asset
                    </a>
                    <a href="/jadwal" class="inline-flex items-center justify-center px-4 py-2 border border-secondary text-sm font-medium rounded-xl text-secondary bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Kuliah
                    </a>
                @endif
                @if(!auth()->check() || auth()->user()->role !== 'admin')
                    <a href="/peminjaman/create" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-xl text-primary bg-accent hover:bg-accent/90 shadow-lg shadow-accent/20 transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Peminjaman
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-primary to-secondary shadow-lg shadow-primary/20 flex items-center justify-between group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <p class="text-teal-100 font-medium text-sm md:text-base mb-1">Total Pengajuan Bulan Ini</p>
                    <h2 class="text-white text-4xl md:text-5xl font-bold tracking-tight">{{ $totalBulanIni }}</h2>
                </div>
                <div class="relative z-10 w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            
            <div class="relative overflow-hidden rounded-3xl p-6 bg-white border border-slate-200 shadow-md shadow-slate-200/50 flex items-center justify-between group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-orange-100 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <p class="text-slate-500 font-medium text-sm md:text-base mb-1">Menunggu Persetujuan</p>
                    <h2 class="text-slate-900 text-4xl md:text-5xl font-bold tracking-tight">{{ $menungguPersetujuan }}</h2>
                </div>
                <div class="relative z-10 w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center border border-orange-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>



        @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Manajemen Akun Section (Untuk Admin BAA) -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 mt-12" x-data="{ isModalOpen: false, rfidModal: false }">
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    Manajemen Akun
                </h3>
                <p class="text-sm text-slate-500 mt-2">Daftar pengguna (User Management) yang memiliki akses ke sistem lab.</p>
            </div>
            
            <div class="flex gap-2">
                <button @click="rfidModal = true" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-xl shadow-sm transition-all gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    Daftar RFID Mahasiswa
                </button>
                <button @click="isModalOpen = true" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary/90 text-white font-semibold rounded-xl shadow-sm transition-all gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Akun
                </button>
            </div>

            <!-- Modal Tambah Akun (Alpine.js) -->
            <div x-show="isModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="isModalOpen" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             @click.away="isModalOpen = false"
                             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold leading-6 text-slate-900" id="modal-title">Pendaftaran Akun Baru</h3>
                                    <button @click="isModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <p class="text-slate-500 text-sm mt-2">Lengkapi data profil akun pengguna, tentukan role secara bijak.</p>
                            </div>

                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                <div class="px-6 py-4 space-y-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Username Lengkap</label>
                                        <input type="text" name="username" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" placeholder="Misal: Admin Laboratorium" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Nomor Induk Mahasiswa (NIM)</label>
                                        <input type="text" name="nim" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all font-mono" placeholder="Opsional untuk Non-Mahasiswa">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                                        <input type="email" name="email" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" placeholder="Misal: admin@lab.pcr.ac.id" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Password Akses</label>
                                        <input type="password" name="password" minlength="6" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" placeholder="Minimal 6 karakter" required>
                                    </div>
                                    <div class="space-y-2 pb-2">
                                        <label class="block text-sm font-semibold text-slate-700">Pilih Jabatan (Akses Role)</label>
                                        <select name="role" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                                            <option value="" selected disabled>-- Menentukan Hak Akses --</option>
                                            <option value="mahasiswa">Mahasiswa Peminjam</option>
                                            <option value="laboran">Staf Laboran (Level 1)</option>
                                            <option value="kajur">Kepala Jurusan (Level 2)</option>
                                            <option value="wadir">Wakil Direktur (Level 3)</option>
                                            <option value="admin">Admin BAA (Manajemen Akun)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2 pb-2 border-t border-slate-100 pt-4">
                                        <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            UID RFID (Opsional)
                                        </label>
                                        <input type="text" name="rfid_uid" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all font-mono" placeholder="Tap kartu di alat scanner...">
                                        <p class="text-xs text-slate-500">Arahkan kursor ke sini dan tap kartu ke alat RFID Reader USB.</p>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl">
                                    <button type="button" @click="isModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 rounded-xl transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Buat Akun
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal RFID (Alpine.js) -->
            <div x-show="rfidModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div x-show="rfidModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="rfidModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             @click.away="rfidModal = false"
                             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                            
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold leading-6 text-slate-900" id="modal-title">Pendaftaran RFID Mahasiswa</h3>
                                    <button @click="rfidModal = false" type="button" class="text-slate-400 hover:text-slate-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <p class="text-slate-500 text-sm mt-2">Pilih mahasiswa dan arahkan kursor ke kolom UID, lalu tempelkan KTM ke alat scanner USB.</p>
                            </div>

                            <form action="{{ route('admin.rfid.update') }}" method="POST">
                                @csrf
                                <div class="px-6 py-6 space-y-4">
                                    <div class="space-y-2 pb-2">
                                        <label class="block text-sm font-semibold text-slate-700">Pilih Mahasiswa</label>
                                        <select name="id_user" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                                            <option value="" selected disabled>-- Pilih Mahasiswa --</option>
                                            @foreach($allUsers->where('role', 'mahasiswa') as $mhs)
                                                <option value="{{ $mhs->id_user }}">{{ $mhs->username }} {{ $mhs->nim ? '('.$mhs->nim.')' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2 pb-2">
                                        <label class="block text-sm font-semibold text-slate-700">UID RFID Kartu</label>
                                        <input type="text" name="rfid_uid" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all font-mono text-center tracking-widest text-lg" placeholder="Tap kartu..." required>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl">
                                    <button type="button" @click="rfidModal = false" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 rounded-xl transition-all">
                                        Simpan Kartu
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status RFID</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-48">Akses Role</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($allUsers as $index => $u)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                            {{ substr($u->username, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ $u->username }}</span>
                                            @if($u->nim)
                                                <span class="text-xs text-slate-500 font-mono">{{ $u->nim }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $u->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($u->rfid_uid)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <svg class="mr-1.5 h-2 w-2 text-emerald-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            Terdaftar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            <svg class="mr-1.5 h-2 w-2 text-slate-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            Belum Terdaftar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($u->role == 'mahasiswa')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">Mahasiswa</span>
                                    @elseif($u->role == 'laboran')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase">Laboran</span>
                                    @elseif($u->role == 'kajur')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200 uppercase">Kajur</span>
                                    @elseif($u->role == 'wadir')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Wadir</span>
                                    @elseif($u->role == 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200 uppercase">Admin BAA</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">{{ $u->role }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form action="{{ route('users.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->username }} ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Akun">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <p class="font-medium">Belum ada akun terdaftar dalam sistem.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Pie Chart -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md shadow-slate-200/40 p-6 flex flex-col">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-6">Rasio Status Peminjaman</h3>
                <div class="flex-1 relative w-full" style="min-height: 250px;">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
            <!-- Bar Chart -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-md shadow-slate-200/40 p-6 flex flex-col">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-6">Frekuensi Peminjaman Lab</h3>
                <div class="flex-1 relative w-full" style="min-height: 250px;">
                    <canvas id="labBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- History Table Section -->
        <div class="mb-4">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Riwayat Peminjaman </h3>
            <p class="text-sm text-slate-500">Daftar pengajuan peminjaman ruang laboratorium terbaru.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID Lab</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal & Jam</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center">
                                    {{ $riwayat->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs border border-teal-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">{{ Str::limit($item->labs->pluck('nama')->implode(', '), 30) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-800 font-medium">{{ \Carbon\Carbon::parse($item->tgl_mulai)->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($item->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                        </span>
                                    @elseif($item->status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Detail Button -->
                                        <a href="/peminjaman/detail/{{ $item->id }}" class="p-2 text-slate-400 hover:text-primary hover:bg-slate-100 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        
                                        <!-- Approver Actions -->
                                        @php
                                            $canApprove = false;
                                            if (auth()->check()) {
                                                $userRole = auth()->user()->role;
                                                if ($item->level == '1' && $userRole == 'laboran') $canApprove = true;
                                                elseif ($item->level == '2' && $userRole == 'kajur') $canApprove = true;
                                                elseif ($item->level == '3' && $userRole == 'wadir') $canApprove = true;
                                            }
                                        @endphp
                                        @if($canApprove && $item->status == 'pending')
                                            <div class="h-6 w-px bg-slate-200 mx-1"></div>
                                            <form action="/peminjaman/{{ $item->id }}/approval-web" method="POST" class="m-0 inline-block">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="p-1.5 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-lg transition-colors" title="Setujui">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            
                                            <form action="/peminjaman/{{ $item->id }}/approval-web" method="POST" class="m-0 inline-block">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 rounded-lg transition-colors" title="Tolak">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 border border-slate-200">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada riwayat peminjaman.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Wrapper -->
            @if($riwayat->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $riwayat->links() }}
            </div>
            @endif
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup Chart Defaults for Tailwind Colors
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b'; // slate-500
            
            // Chart 1: Status Peminjaman (Doughnut Chart)
            const statusCtx = document.getElementById('statusPieChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['pending'] ?? 0 }},
                            {{ $statusCounts['approved'] ?? 0 }},
                            {{ $statusCounts['rejected'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#f59e0b', // amber-500
                            '#10b981', // emerald-500
                            '#f43f5e'  // rose-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                boxWidth: 8
                            }
                        }
                    },
                    cutout: '75%'
                }
            });

            // Chart 2: Lab Sering Dipinjam (Bar Chart)
            const labCtx = document.getElementById('labBarChart').getContext('2d');
            const labLabels = {!! json_encode(array_keys($labCounts)) !!};
            const labData = {!! json_encode(array_values($labCounts)) !!};

            // Create gradient for bars
            let gradient = labCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, '#005C6E'); // teal-600
            gradient.addColorStop(1, '#0B2B36'); // navy-900

            new Chart(labCtx, {
                type: 'bar',
                data: {
                    labels: labLabels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: labData,
                        backgroundColor: gradient,
                        borderRadius: 8,
                        barThickness: 'flex',
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false }, // border-slate-100
                            ticks: { precision: 0, stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', // slate-800
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.parsed.y + ' kali dipinjam';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>