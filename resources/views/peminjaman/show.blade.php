<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Form - Peminjaman Lab</title>
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
                    <a href="/dashboard" class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-bold text-lg tracking-tight text-primary">Sistem Peminjaman Lab</span>
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/dashboard" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    @if($peminjaman->status == 'approved')
                    <a href="/peminjaman/print/{{ $peminjaman->id }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Bukti
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Status Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dokumen Peminjaman</h1>
            </div>
            
            <div>
                @if($peminjaman->status == 'pending_pembimbing')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Menunggu Pembimbing
                    </span>
                @elseif($peminjaman->status == 'pending_laboran')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Menunggu Laboran
                    </span>
                @elseif($peminjaman->status == 'pending_kajur')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Menunggu Kajur
                    </span>
                @elseif($peminjaman->status == 'pending_wadir')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Menunggu Wadir
                    </span>
                @elseif($peminjaman->status == 'approved')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Disetujui
                    </span>
                @elseif($peminjaman->status == 'rejected')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                        {{ $peminjaman->status }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Approval Stepper -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8 overflow-x-auto">
            <h3 class="text-sm font-bold text-slate-900 mb-6 tracking-tight">Jejak Persetujuan</h3>
            
            @php
                $statusList = ['pending_pembimbing', 'pending_laboran', 'pending_kajur', 'pending_wadir', 'approved'];
                $currentIndex = array_search($peminjaman->status, $statusList);
                if ($peminjaman->status == 'rejected') {
                    $currentIndex = -1; // Special case
                }

                $steps = [
                    ['name' => 'Pembimbing', 'status_key' => 'pending_pembimbing', 'index' => 0],
                    ['name' => 'Laboran', 'status_key' => 'pending_laboran', 'index' => 1]
                ];
                if ($peminjaman->level == '2' || $peminjaman->level == '3') {
                    $steps[] = ['name' => 'Kajur', 'status_key' => 'pending_kajur', 'index' => 2];
                }
                if ($peminjaman->level == '3') {
                    $steps[] = ['name' => 'Wadir', 'status_key' => 'pending_wadir', 'index' => 3];
                }
            @endphp

            <div class="flex items-center w-full min-w-max">
                @foreach($steps as $key => $step)
                    @php
                        // Determine step visual state
                        $isCompleted = ($currentIndex > $step['index']) || ($peminjaman->status == 'approved');
                        $isActive = ($currentIndex === $step['index']);
                        $isRejected = ($peminjaman->status == 'rejected' && $currentIndex === -1);
                        
                        $circleBg = 'bg-slate-100 text-slate-400';
                        $circleBorder = 'border-slate-200';
                        $textColor = 'text-slate-500';
                        $icon = '<span class="font-semibold text-sm">'.($key+1).'</span>';
                        $statusText = 'Belum Diproses';

                        if ($isCompleted) {
                            $circleBg = 'bg-emerald-50 text-emerald-500';
                            $circleBorder = 'border-emerald-200';
                            $textColor = 'text-emerald-700';
                            $icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
                            $statusText = 'Disetujui';
                        } elseif ($isActive) {
                            $circleBg = 'bg-amber-50 text-amber-500';
                            $circleBorder = 'border-amber-200';
                            $textColor = 'text-amber-700 font-bold';
                            $statusText = 'Menunggu';
                            // If rejected, the step that was supposed to be next is marked as rejected instead? 
                            // Actually, if rejected, we just stop. Let's make it simple.
                        } elseif ($isRejected) {
                            // If the document is rejected, maybe we just show a red badge somewhere else.
                            // The current active step at the time of rejection is hard to track without an approval table log.
                        }
                    @endphp
                    
                    <div class="flex flex-col items-center relative z-10 w-32">
                        <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white shadow-sm transition-all {{ $circleBg }} {{ $circleBorder }}">
                            {!! $icon !!}
                        </div>
                        <div class="mt-3 text-center">
                            <div class="text-xs font-bold uppercase tracking-wider {{ $textColor }}">{{ $step['name'] }}</div>
                            <div class="text-[10px] font-medium text-slate-400 mt-1">{{ $statusText }}</div>
                        </div>
                    </div>

                    @if($key !== count($steps) - 1)
                        <div class="flex-1 h-0.5 bg-slate-200 mx-2 {{ $isCompleted ? 'bg-emerald-300' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="p-0">
                <table class="min-w-full divide-y divide-slate-200">
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="w-1/3 px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Pengaju / Mahasiswa</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $mahasiswa ? $mahasiswa->username : 'Tidak Diketahui' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Level Persetujuan</th>
                            <td class="px-6 py-4 text-sm text-slate-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Level {{ $peminjaman->level }}
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Tanggal Peminjaman / Pengajuan</th>
                            <td class="px-6 py-4 text-sm text-slate-900">{{ \Carbon\Carbon::parse($peminjaman->tgl_mulai)->subDays(1)->format('Y-m-d') }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Ruang Lab</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-semibold">{{ $peminjaman->labs->pluck('nama')->implode(', ') ?: '-' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50 align-top">Kebutuhan Alat</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-semibold whitespace-pre-wrap">{{ $peminjaman->kebutuhan_alat ?: '-' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Tanggal Mulai</th>
                            <td class="px-6 py-4 text-sm text-primary font-bold">{{ $peminjaman->tgl_mulai }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Tanggal Akhir</th>
                            <td class="px-6 py-4 text-sm text-primary font-bold">{{ $peminjaman->tgl_selesai }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Jam Mulai</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-semibold">{{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Jam Akhir</th>
                            <td class="px-6 py-4 text-sm text-rose-600 font-semibold">{{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50 align-top">Keperluan</th>
                            <td class="px-6 py-4 text-sm text-slate-900">{{ $peminjaman->keterangan }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50 align-top">Daftar Nama Peserta</th>
                            <td class="px-6 py-4 text-sm text-slate-900 whitespace-pre-wrap">{{ $peminjaman->peserta->pluck('username')->implode(', ') ?: '-' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Pembimbing</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $peminjaman->pembimbing ?? '-' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Ketua Kegiatan</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $peminjaman->ketua_kegiatan ?? '-' }}</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-500 bg-slate-50/50">Kontak Ketua</th>
                            <td class="px-6 py-4 text-sm text-slate-900 font-medium">{{ $peminjaman->kontak_ketua ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approval Actions -->
        @php
            $canApprove = false;
            if (auth()->check()) {
                $userRole = auth()->user()->role;
                if ($peminjaman->status == 'pending_laboran' && $userRole == 'laboran') $canApprove = true;
                elseif ($peminjaman->status == 'pending_kajur' && $userRole == 'kajur') $canApprove = true;
                elseif ($peminjaman->status == 'pending_wadir' && $userRole == 'wadir') $canApprove = true;
            }
        @endphp
        @if($canApprove)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center mb-10">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Tindakan Persetujuan</h3>
            <p class="text-slate-500 text-sm mb-8 max-w-md mx-auto">Pastikan Anda telah membaca rincian peminjaman dengan seksama sebelum memberikan keputusan persetujuan atau penolakan.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <form action="/peminjaman/{{ $peminjaman->id }}/approval-web" method="POST" class="w-full sm:w-auto flex-1 max-w-xs mx-auto sm:mx-0">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" onclick="return confirm('Yakin ingin MENOLAK peminjaman ini?')" class="w-full py-3 px-6 bg-white border-2 border-rose-100 hover:border-rose-200 text-rose-600 hover:bg-rose-50 font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak Permohonan
                    </button>
                </form>

                <form action="/peminjaman/{{ $peminjaman->id }}/approval-web" method="POST" class="w-full sm:w-auto flex-1 max-w-xs mx-auto sm:mx-0">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" onclick="return confirm('Yakin ingin MENYETUJUI peminjaman ini?')" class="w-full py-3 px-6 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Setujui Permohonan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </main>

</body>
</html>
