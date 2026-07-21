<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman Laboratorium - {{ $peminjaman->id }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            font-size: 14px;
            background-color: white;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            box-sizing: border-box;
        }
        /* Kop Surat Section */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .kop-surat .logo {
            width: 80px;
            height: 80px;
            background-color: #cbd5e1; /* Placeholder logo */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            color: white;
            font-size: 20px;
        }
        .kop-surat .text-center {
            flex: 1;
            text-align: center;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #475569;
        }

        /* Title */
        .doc-title {
            text-align: center;
            margin-bottom: 2rem;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 18px;
            text-decoration: underline;
        }
        .doc-title p {
            margin: 4px 0 0;
            color: #64748b;
        }

        /* Table Detail */
        table.detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        table.detail-table th, table.detail-table td {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            text-align: left;
            vertical-align: top;
        }
        table.detail-table th {
            background-color: #f8fafc;
            width: 35%;
            font-weight: 600;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 4rem;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            width: 45%;
        }
        .signature-box .title {
            font-weight: 600;
            margin-bottom: 5rem;
        }
        .signature-box .name {
            font-weight: 700;
            text-decoration: underline;
        }

        @media print {
            body {
                background-color: white;
            }
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <!-- Jika Anda memiliki logo PCR, ubah tag <div> logo ini menjadi tag <img> -->
            <div class="logo">PCR</div>
            <div class="text-center">
                <h1>POLITEKNIK CALTEX RIAU</h1>
                <p>Jl. Umban Sari (Patin) No. 1 Rumbai, Pekanbaru-Riau 28265<br>Telp: (0761) 53939, Email: pcr@pcr.ac.id</p>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            <h2>BUKTI PERSETUJUAN PEMINJAMAN LABORATORIUM</h2>
            <p>Nomor Tiket: #{{ str_pad($peminjaman->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Tabel Detail Peminjaman -->
        <table class="detail-table">
            <tbody>
                <tr>
                    <th>Pengaju / Mahasiswa</th>
                    <td>{{ $mahasiswa ? $mahasiswa->username : 'Tidak Diketahui' }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $mahasiswa ? $mahasiswa->nim : '-' }}</td>
                </tr>
                <tr>
                    <th>Level Persetujuan</th>
                    <td>Level {{ $peminjaman->level }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_mulai)->subDays(1)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Ruang Laboratorium</th>
                    <td>{{ collect($peminjaman->labs)->pluck('nama')->implode(', ') ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Kebutuhan Alat</th>
                    <td style="white-space: pre-wrap;">{{ $peminjaman->kebutuhan_alat ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Peminjaman</th>
                    <td>
                        {{ \Carbon\Carbon::parse($peminjaman->tgl_mulai)->format('d M Y') }} 
                        s.d. 
                        {{ \Carbon\Carbon::parse($peminjaman->tgl_selesai)->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <th>Waktu (Jam)</th>
                    <td>
                        {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} 
                        s.d. 
                        {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }} WIB
                    </td>
                </tr>
                <tr>
                    <th>Keperluan / Kegiatan</th>
                    <td>{{ $peminjaman->keterangan }}</td>
                </tr>
                <tr>
                    <th>Daftar Peserta Tambahan</th>
                    <td>{{ collect($peminjaman->peserta)->pluck('username')->implode(', ') ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Pembimbing</th>
                    <td>{{ $peminjaman->pembimbing ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Status Dokumen</th>
                    <td style="font-weight: 700; color: #10b981;">
                        {{ $peminjaman->status == 'approved' ? 'DISETUJUI SEPENUHNYA' : strtoupper(str_replace('_', ' ', $peminjaman->status)) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            <p><strong>Pernyataan:</strong></p>
            <p style="text-align: justify; color: #334155;">Peminjam menyatakan bertanggung jawab penuh atas segala kerusakan atau kehilangan peralatan selama masa peminjaman. Dokumen ini dicetak dari Sistem Peminjaman Lab PCR dan sah digunakan sebagai bukti persetujuan (tidak memerlukan tanda tangan fisik).</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="title">Pemohon (Mahasiswa),</div>
                <div class="name">{{ $mahasiswa ? $mahasiswa->username : '______________________' }}</div>
                <div>NIM: {{ $mahasiswa ? $mahasiswa->nim : '_________' }}</div>
            </div>
            
            <div class="signature-box">
                <div class="title">Telah Disetujui Oleh,</div>
                <div class="name">Sistem Peminjaman PCR</div>
                <div>Digital Approval (Level {{ $peminjaman->level }})</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 3rem; font-size: 11px; color: #94a3b8;">
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}
        </div>
    </div>

</body>
</html>
