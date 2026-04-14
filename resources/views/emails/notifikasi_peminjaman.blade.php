<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Peminjaman Lab</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #0d6efd; text-align: center;">Ada Pengajuan Peminjaman Baru!</h2>
        
        <p>Halo, Bapak/Ibu.</p>
        <p>Terdapat mahasiswa yang mengajukan permohonan peminjaman laboratorium yang <strong>membutuhkan persetujuan Anda</strong>. Berikut adalah rinciannya:</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Nama Mahasiswa</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">: {{ $mahasiswa->username }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ruang Lab</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">: Lab {{ $peminjaman->id_lab }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tanggal</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">: {{ $peminjaman->tgl_mulai }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Waktu</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">: {{ \Carbon\Carbon::parse($peminjaman->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->jam_selesai)->format('H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tujuan / Keterangan</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">: {{ $peminjaman->keterangan }}</td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/dashboard') }}" style="background-color: #0d6efd; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Buka Halaman Persetujuan
            </a>
        </div>

        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            Email ini dibuat otomatis oleh Sistem Peminjaman Lab PCR. Harap tidak membalas email ini.
        </p>
    </div>

</body>
</html>