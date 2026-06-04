<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi — RamaDigital Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --warna-utama: #1a73e8;
            --warna-aksen: #ff6d00;
            --warna-gelap: #1c2b4a;
        }

        body {
            background: linear-gradient(135deg, var(--warna-gelap) 0%, var(--warna-utama) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .page-card {
            width: 100%;
            max-width: 520px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.16);
            overflow: hidden;
        }

        .page-card-header {
            padding: 35px 35px 20px;
            background: linear-gradient(135deg, var(--warna-gelap), #2c3e6b);
            color: white;
            text-align: center;
        }

        .page-card-header h1 {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-card-body {
            padding: 35px;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #dee2e6;
            padding: 14px 16px;
            font-size: 0.95rem;
        }

        .btn-login {
            width: 100%;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--warna-utama);
            text-decoration: none;
        }

        .alert {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="page-card">
        <div class="page-card-header">
            <h1>Ubah Kata Sandi</h1>
            <p class="mb-0">Gunakan password default <strong>password123</strong> untuk melakukan perubahan.</p>
        </div>

        <div class="page-card-body">
            <a href="{{ route('user.login') }}" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan username Anda" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="old_password" class="form-label fw-semibold">Password Lama</label>
                    <input type="password" id="old_password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="password123" required>
                    @error('old_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Masukkan password baru" required>
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn btn-warning btn-login text-dark">
                    <i class="bi bi-key-fill me-1"></i>Ubah Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>
