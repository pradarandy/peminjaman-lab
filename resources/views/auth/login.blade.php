<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Sistem Peminjaman Lab PCR</title>
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

            .btn-primary {
                background-color: #0d6efd;
                border: none;
            }

            .btn-primary:hover {
                background-color: #0b5ed7;
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
                                <h3 class="fw-bold">Sistem Peminjaman</h3>
                                <p class ="text-muted">Laboratorium Politeknik Caltex Riau</p>
                            </div>

                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Login Gagal!</strong> {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="/login-web" method="POST">
                            <form action="/login-web" method="POST">
                                @csrf <div class="mb-3">
                                    <label for="email" class="form-label"> Alamat Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email@mahasiswa.pcr.ac.id" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                                </div>

                                <div class="text-center">
                                    <span class="text_muted">Belum punya akun?</span>
                                    <a href="/register" class="text-decoration-none fw-bold">Daftar di sini</a>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>