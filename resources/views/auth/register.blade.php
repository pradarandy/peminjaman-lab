<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Sistem Peminjaman Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Daftar Akun Baru</h3>
                            <p class="text-muted">Silakan lengkapi data diri Anda</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger pb-0">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/register-web" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Nama Lengkap / Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Contoh: rrrandyyy" value="{{ old('username') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@mahasiswa.pcr.ac.id" value="{{ old('email') }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Buat password (minimal 6 karakter)" required>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-success btn-lg">Daftar Sekarang</button>
                            </div>

                            <div class="text-center">
                                <span class="text-muted">Sudah punya akun?</span> 
                                <a href="/login" class="text-decoration-none fw-bold">Masuk di sini</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>