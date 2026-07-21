<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Peminjaman Lab</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f8fafc; padding: 20px; color: #1e293b;">

    <div style="max-width: 700px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); overflow: hidden;">
        
        <!-- Header -->
        <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background-color: #ffffff;">
            <div style="display: flex; align-items: center;">
                <div style="background-color: #eff6ff; color: #2563eb; padding: 8px; border-radius: 12px; display: inline-block; vertical-align: middle;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h1 style="margin: 0; margin-left: 12px; font-size: 24px; font-weight: 700; color: #0f172a; display: inline-block; vertical-align: middle;">Dokumen Peminjaman</h1>
            </div>
            
            <div style="float: right;">
                <span style="display: inline-block; padding: 6px 16px; border-radius: 9999px; font-size: 14px; font-weight: 600; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #f59e0b; margin-right: 6px;"></span> Menunggu Persetujuan
                </span>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div style="padding: 20px 30px;">
            <p style="margin-top: 0;">Halo, Bapak/Ibu.</p>
            <p>Terdapat pengajuan peminjaman laboratorium yang <strong>membutuhkan persetujuan Anda</strong>. Berikut adalah rinciannya:</p>
        </div>

        <!-- Detail Table -->
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <tbody>
                <tr>
                    <th style="width: 35%; padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9; border-top: 1px solid #f1f5f9;">Pengaju / Mahasiswa</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 500; color: #0f172a; border-bottom: 1px solid #f1f5f9; border-top: 1px solid #f1f5f9;">{{ $mahasiswa ? $mahasiswa->username : 'Tidak Diketahui' }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Level Persetujuan</th>
                    <td style="padding: 16px 24px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9;">
                        <span style="display: inline-block; padding: 2px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">
                            Level {{ $peminjaman->level }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Tanggal Peminjaman / Pengajuan</th>
                    <td style="padding: 16px 24px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ \Carbon\Carbon::parse($peminjaman->tgl_mulai)->subDays(1)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Ruang Lab</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9;">
                        {{ $peminjaman->labs->pluck('nama')->implode(', ') ?: '-' }}
                    </td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9; vertical-align: top;">Kebutuhan Alat</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; white-space: pre-wrap;">{{ $peminjaman->kebutuhan_alat ?: '-' }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Tanggal Mulai</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #0B2B36; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->tgl_mulai }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Tanggal Akhir</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #0B2B36; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->tgl_selesai }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Jam Mulai</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Jam Akhir</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #e11d48; border-bottom: 1px solid #f1f5f9;">{{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9; vertical-align: top;">Keperluan</th>
                    <td style="padding: 16px 24px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->keterangan }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9; vertical-align: top;">Daftar Nama Peserta</th>
                    <td style="padding: 16px 24px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9; white-space: pre-wrap;">{{ $peminjaman->peserta->pluck('username')->implode(', ') ?: '-' }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Pembimbing</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 500; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->pembimbing ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Ketua Kegiatan</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 500; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->ketua_kegiatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">Kontak Ketua</th>
                    <td style="padding: 16px 24px; font-size: 14px; font-weight: 500; color: #0f172a; border-bottom: 1px solid #f1f5f9;">{{ $peminjaman->kontak_ketua ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Approval Actions -->
        <div style="padding: 40px 30px; text-align: center; background-color: #ffffff;">
            <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 20px; font-weight: 700; color: #0f172a;">Tindakan Persetujuan</h3>
            <p style="margin-top: 0; margin-bottom: 24px; font-size: 14px; color: #64748b; max-width: 400px; margin-left: auto; margin-right: auto;">Pastikan Anda telah membaca rincian peminjaman dengan seksama sebelum memberikan keputusan persetujuan atau penolakan.</p>
            
            <div>
                <a href="{{ $rejectUrl }}" style="display: inline-block; width: 160px; padding: 12px 20px; background-color: #ffffff; border: 2px solid #ffe4e6; color: #e11d48; font-weight: 700; border-radius: 12px; text-decoration: none; text-align: center; margin-right: 12px; margin-bottom: 10px;">
                    Tolak
                </a>
                <a href="{{ $approveUrl }}" style="display: inline-block; width: 160px; padding: 14px 20px; background-color: #10b981; color: #ffffff; font-weight: 700; border-radius: 12px; text-decoration: none; text-align: center; margin-bottom: 10px; box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);">
                    Setujui Permohonan
                </a>
            </div>
        </div>

    </div>

    <p style="margin-top: 24px; font-size: 12px; color: #94a3b8; text-align: center;">
        Email ini dikirim otomatis oleh Sistem Peminjaman Lab PCR. Harap tidak membalas email ini.
    </p>

</body>
</html>