<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun - Sistem Peminjaman Lab</title>
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
    <!-- Alpine.js for Modal -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">
    
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/dashboard" class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-bold text-lg tracking-tight text-primary">Sistem Peminjaman Lab</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <a href="/dashboard" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8" x-data="{ isModalOpen: false }">
        
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 p-4 border border-emerald-200 flex items-start">
                <svg class="h-5 w-5 text-emerald-400 mt-0.5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <div>
                    <h3 class="text-sm font-semibold text-emerald-800">Sukses!</h3>
                    <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-rose-50 p-4 border border-rose-200 flex items-start">
                <svg class="h-5 w-5 text-rose-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="text-sm font-semibold text-rose-800">Terdapat Error!</h3>
                    <p class="text-sm text-rose-700 mt-1">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    Manajemen Akun
                </h1>
                <p class="text-slate-500 mt-2">Daftar pengguna (User Management) yang memiliki akses ke sistem lab.</p>
            </div>
            
            <button @click="isModalOpen = true" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white font-semibold rounded-xl shadow-lg shadow-primary/20 transition-all gap-2 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Tambah Akun
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status RFID</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-48">Akses Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                            {{ substr($user->username, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $user->username }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($user->rfid_uid)
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
                                    @if($user->role == 'mahasiswa')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">Mahasiswa</span>
                                    @elseif($user->role == 'laboran')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200 uppercase">Laboran</span>
                                    @elseif($user->role == 'kajur')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200 uppercase">Kajur</span>
                                    @elseif($user->role == 'wadir')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Wadir</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 uppercase">{{ $user->role }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
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
        
    </main>

</body>
</html>
