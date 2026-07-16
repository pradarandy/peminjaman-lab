<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Ruang Lab - Peminjaman Lab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <div class="flex items-center gap-4">
                    <a href="/dashboard" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="block sm:inline font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4" x-data="{ isAddModalOpen: false }">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    Manajemen Ruang Lab
                </h1>
                <p class="text-sm text-slate-500 mt-2">Kelola daftar ruang laboratorium yang tersedia untuk dipinjam.</p>
            </div>
            
            <button @click="isAddModalOpen = true" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary/90 text-white font-semibold rounded-xl shadow-sm transition-all gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Ruang Lab
            </button>

            <!-- Modal Tambah Lab -->
            <div x-show="isAddModalOpen" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div x-show="isAddModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="isAddModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="isAddModalOpen = false" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-xl font-bold leading-6 text-slate-900" id="modal-title">Tambah Ruang Lab</h3>
                                    <button @click="isAddModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <form action="{{ route('labs.store') }}" method="POST">
                                @csrf
                                <div class="px-6 py-4 space-y-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Nama Lab</label>
                                        <input type="text" name="nama" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" placeholder="Misal: Lab Komputer Dasar" required>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Status Awal</label>
                                        <select name="status" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                                            <option value="tersedia">Tersedia</option>
                                            <option value="digunakan">Digunakan</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl">
                                    <button type="button" @click="isAddModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 rounded-xl transition-all">Simpan</button>
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
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lab</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($labs as $lab)
                            <tr class="hover:bg-slate-50/80 transition-colors" x-data="{ isEditModalOpen: false }">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center">{{ $lab->id_lab }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">{{ $lab->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($lab->status == 'tersedia')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">Tersedia</span>
                                    @elseif($lab->status == 'digunakan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Digunakan</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200">Maintenance</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Edit -->
                                        <button @click="isEditModalOpen = true" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('labs.destroy', $lab->id_lab) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus ruang lab ini? Data yang terhubung mungkin akan bermasalah.')" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Edit Lab -->
                                    <div x-show="isEditModalOpen" style="display: none;" class="relative z-50 text-left" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div x-show="isEditModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
                                        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="isEditModalOpen = false" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                                                        <div class="flex items-center justify-between">
                                                            <h3 class="text-xl font-bold leading-6 text-slate-900" id="modal-title">Edit Ruang Lab</h3>
                                                            <button @click="isEditModalOpen = false" type="button" class="text-slate-400 hover:text-slate-500">
                                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('labs.update', $lab->id_lab) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="px-6 py-4 space-y-4">
                                                            <div class="space-y-2">
                                                                <label class="block text-sm font-semibold text-slate-700">Nama Lab</label>
                                                                <input type="text" name="nama" value="{{ $lab->nama }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                                                            </div>
                                                            <div class="space-y-2">
                                                                <label class="block text-sm font-semibold text-slate-700">Status</label>
                                                                <select name="status" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                                                                    <option value="tersedia" {{ $lab->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                                    <option value="digunakan" {{ $lab->status == 'digunakan' ? 'selected' : '' }}>Digunakan</option>
                                                                    <option value="maintenance" {{ $lab->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl">
                                                            <button type="button" @click="isEditModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors">Batal</button>
                                                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 rounded-xl transition-all">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <p class="font-medium">Belum ada data ruang lab.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
