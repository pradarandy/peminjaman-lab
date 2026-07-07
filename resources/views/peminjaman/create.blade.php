<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman - Sistem Peminjaman Lab</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
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

    <!-- Top Navigation -->
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

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50/50 border-b border-slate-200 px-8 py-6 text-center">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Form Pengajuan Peminjaman</h2>
                <p class="text-slate-500 mt-1 text-sm">Silakan lengkapi data peminjaman di bawah ini</p>
            </div>
            
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{  $error }} </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="/peminjaman/store-web" method="POST" class="space-y-6">
                    @csrf 
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Level Persetujuan</label>
                            <input type="text" id="levelPreview" class="block w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-bold text-center outline-none" readonly value="3" title="Otomatis dihitung berdasarkan tanggal dan jam">
                            <p class="text-xs text-slate-500 mt-1">Dihitung otomatis (1: Laboran, 2: Kajur, 3: Wadir)</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Tanggal Peminjaman</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" class="block w-full pl-11 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-medium outline-none" value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Pilih Ruang Lab <span class="text-red-500">*</span></label>
                        <select name="id_lab" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" required>
                            <option value="" disabled selected>-- Pilih Ruang Lab --</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id_lab }}">{{ $lab->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="assetDropdown" class="block text-sm font-semibold text-slate-700">Pilih Asset (Opsional)</label>
                        <select id="assetDropdown" name="id_asset" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            <option value="" selected disabled>Sedang memuat data asset...</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" id="tgl_mulai" name="tgl_mulai" class="datepicker block w-full pl-11 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all cursor-pointer placeholder:text-slate-400 font-medium" placeholder="Pilih tanggal mulai" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" name="tgl_selesai" class="datepicker block w-full pl-11 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all cursor-pointer placeholder:text-slate-400 font-medium" placeholder="Pilih tanggal selesai" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Jam Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input type="text" id="jam_mulai" name="jam_mulai" class="timepicker block w-full pl-11 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all cursor-pointer placeholder:text-slate-400 font-medium" placeholder="Pilih jam mulai" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Jam Selesai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input type="text" name="jam_selesai" class="timepicker block w-full pl-11 pr-10 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all cursor-pointer placeholder:text-slate-400 font-medium" placeholder="Pilih jam selesai" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Keperluan / Keterangan <span class="text-red-500">*</span></label>
                        <textarea name="keterangan" rows="2" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-y" placeholder="Contoh: Bootcamp PA Bimbingan MMZ" required></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Daftar Nama Peserta <span class="text-red-500">*</span></label>
                        <textarea name="daftar_nama" rows="4" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-y" placeholder="1. Nama Pertama&#10;2. Nama Kedua" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Ketua Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="ketua_kegiatan" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Nama Ketua" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Kontak Ketua (No. HP) <span class="text-red-500">*</span></label>
                            <input type="text" name="kontak_ketua" class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="w-full py-4 px-6 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assetDropdown = document.getElementById('assetDropdown');

            // Fetch Data Asset dari API
            fetch('/api/assets')
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat asset');
                    return response.json();
                })
                .then(data => {
                    assetDropdown.innerHTML = '<option value="">-- Tidak Meminjam Asset --</option>';
                    data.forEach(asset => {
                        const option = document.createElement('option');
                        option.value = asset.id;
                        option.textContent = `${asset.nama_asset} - ${asset.nama} (${asset.posisi_asset})`;
                        assetDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching assets:', error);
                    assetDropdown.innerHTML = '<option value="">-- Gagal memuat asset --</option>';
                });

            const tglMulai = document.getElementById('tgl_mulai');
            const jamMulai = document.getElementById('jam_mulai');
            const levelPreview = document.getElementById('levelPreview');

            function updateLevel() {
                if (!tglMulai.value || !jamMulai.value) return;

                const dateObj = new Date(tglMulai.value);
                const day = dateObj.getDay(); 
                const isWeekend = (day === 0 || day === 6);
                
                let level = '3'; 

                if (!isWeekend) {
                    const time = jamMulai.value;
                    if (time >= '07:00' && time <= '16:00') {
                        level = '1';
                    } else if (time >= '17:00' && time <= '22:00') {
                        level = '2';
                    }
                }
                
                levelPreview.value = level;
            }

            tglMulai.addEventListener('change', updateLevel);
            jamMulai.addEventListener('change', updateLevel);

            // Initialize Flatpickr for Date
            flatpickr(".datepicker", {
                locale: "id",
                dateFormat: "Y-m-d",
                minDate: "today",
                altInput: true,
                altFormat: "d F Y",
                disableMobile: "true"
            });

            // Initialize Flatpickr for Time
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                altInput: true,
                altFormat: "H:i",
                disableMobile: "true"
            });
        });
    </script>

    <style>
        .flatpickr-calendar {
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            border-radius: 1rem !important;
            padding: 10px !important;
            font-family: 'Inter', sans-serif;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #0B2B36 !important;
            border-color: #0B2B36 !important;
        }
    </style>
</body>
</html>