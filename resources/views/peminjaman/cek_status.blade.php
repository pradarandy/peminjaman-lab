<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Ruangan - Sistem Peminjaman Lab</title>
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Navbar -->
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
                        <a href="/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                            Dashboard
                        </a>
                        <a href="/cek-status" class="inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-semibold text-gray-900">
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
    </nav>

    <main class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-primary tracking-tight">Cek Status Laboratorium</h1>
            <p class="text-slate-500 mt-2">Cari dan lihat ketersediaan jadwal laboratorium pada tanggal tertentu.</p>
        </div>

        <!-- Filter/Search Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8">
            <form action="{{ route('peminjaman.cek_status') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-5/12">
                    <label class="block text-sm font-semibold text-slate-600 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Pilih Laboratorium
                    </label>
                    <select name="id_lab" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all">
                        <option value="">Semua Ruangan</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id_lab }}" {{ $id_lab == $lab->id_lab ? 'selected' : '' }}>
                                Lab {{ $lab->id_lab }} {{ $lab->nama ? '- '.$lab->nama : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-5/12">
                    <label class="block text-sm font-semibold text-slate-600 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Hari/Tanggal
                    </label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:bg-white outline-none transition-all" required>
                </div>
                
                <div class="w-full md:w-2/12">
                    <button type="submit" class="w-full py-3 px-4 bg-primary hover:bg-primary/90 text-white font-semibold rounded-2xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
                        Cari
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50/80 border-b border-slate-200 px-6 py-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-primary shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Jadwal Pemakaian Ruangan</h2>
                    <p class="text-sm font-medium text-primary">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Laboratorium</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pemakaian</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Jam Mulai</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Jam Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($jadwal as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 text-center">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs border border-teal-100">
                                            L{{ $item->id_lab }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">Lab {{ $item->id_lab }} {{ $item->lab && $item->lab->nama ? '- '.$item->lab->nama : '' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(isset($item->tipe_jadwal) && $item->tipe_jadwal == 'kuliah')
                                        <div class="flex flex-col gap-1.5">
                                            <span class="inline-flex w-max items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                Mata Kuliah: {{ $item->mata_kuliah }}
                                            </span>
                                            <div class="text-xs text-slate-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $item->dosen }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col gap-1.5">
                                            @if($item->status == 'approved')
                                                <span class="inline-flex w-max items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                    Digunakan (Peminjaman)
                                                </span>
                                            @else
                                                <span class="inline-flex w-max items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                    Dibooking (Pending)
                                                </span>
                                            @endif
                                            <div class="text-xs text-slate-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $item->keterangan }}
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-rose-600">{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <h5 class="text-lg font-bold text-slate-700 mb-1">Ruangan Kosong</h5>
                                        <p class="text-sm text-slate-500 max-w-sm">Tidak ada jadwal penggunaan lab pada tanggal ini. Seluruh ruangan tersedia untuk dipinjam.</p>
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
