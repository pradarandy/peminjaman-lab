<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Persetujuan Peminjaman</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #1e293b;
        }
        .card {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
            width: 90%;
            border: 1px solid #e2e8f0;
        }
        .icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px auto;
        }
        .icon-success {
            background-color: #d1fae5;
            color: #10b981;
        }
        .icon-danger {
            background-color: #ffe4e6;
            color: #f43f5e;
        }
        .icon-warning {
            background-color: #fef3c7;
            color: #f59e0b;
        }
        h2 {
            margin: 0 0 12px 0;
            font-size: 24px;
            font-weight: 700;
        }
        p {
            margin: 0 0 24px 0;
            color: #64748b;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background-color: #f1f5f9;
            color: #475569;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            font-size: 15px;
        }
        .btn:hover {
            background-color: #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="card">
        @if($status == 'expired')
            <div class="icon icon-warning">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h2>Maaf, link kadaluarsa!</h2>
            <p>Status peminjaman ini sudah tidak bisa diubah karena telah diproses sebelumnya.</p>
        @elseif($status == 'approved')
            <div class="icon icon-success">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2>Berhasil Disetujui!</h2>
            <p>Terima kasih, permohonan peminjaman lab ini telah resmi Anda setujui.</p>
        @elseif($status == 'rejected')
            <div class="icon icon-danger">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h2>Berhasil Ditolak!</h2>
            <p>Terima kasih, permohonan peminjaman lab ini telah Anda tolak.</p>
        @endif

        <p style="font-size: 13px; color: #94a3b8; margin-bottom: 20px;">Halaman ini akan tertutup otomatis dalam <span id="countdown">4</span> detik.</p>
        
        <button onclick="window.close()" class="btn">Tutup Halaman Ini</button>
    </div>

    <script>
        // Hitung mundur sebelum close otomatis
        let seconds = 3;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.close(); // Mencoba menutup tab
            }
        }, 1000);
    </script>
</body>
</html>
