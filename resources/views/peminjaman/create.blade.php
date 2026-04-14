<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman - Sistem Peminjaman Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-ligth">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/dashboard">Peminjaman Lab</a>
            <div class="d-flex">
                <a href="/dashboard" class="btn btn-outline-light btn-sm me-2">Kembali</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white pt-4 pb-2 border-0 text-center">
                        <h3 class="fw-bold">Form Pengajuan Peminjaman Lab</h3>
                        <p class="text-muted">Silahkan lengkapi data peminjaman di bawah ini</p>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $errors)
                                        <li>{{  $errors }} </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/peminjaman/store-web" method="POST">
                            @csrf <div class="mb-3">
                                <label class="form-label fw-bold"> Pilih Ruang Lab</label>
                                <select class="form-select" name="id_lab" required>
                                    <option value="1"> Lab 1 </option>
                                    <option value="2"> Lab 2 </option>
                                    <option value="3"> Lab 3 </option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="tgl_mulai" required>
                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="tgl_selesai" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jam Mulai</label>
                                    <input type="time" class="form-control" name="jam_mulai" required>
                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jam Selesai</label>
                                    <input type="time" class="form-control" name="jam_selesai" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Keterangan / Tujuan Peminjaman </label>
                                <textarea class="form-control" name="keterangan" rows="3" placeholder="Contoh: Mengerjakan Tugas Akhir" required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>