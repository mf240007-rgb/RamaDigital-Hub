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

        .input-group .form-control {
            border-radius: 12px 0 0 12px;
        }

        .input-group .btn-toggle-pw {
            border-radius: 0 12px 12px 0;
            border: 1px solid #dee2e6;
            border-left: none;
            background: white;
            color: #6c757d;
            padding: 0 14px;
        }

        .input-group .btn-toggle-pw:hover {
            background: #f8f9fa;
            color: #1a73e8;
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
                    <div class="input-group">
                        <input type="password" id="old_password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="password123" required>
                        <button class="btn-toggle-pw" type="button" onclick="togglePw('old_password', 'icon_old')" tabindex="-1">
                            <i class="bi bi-eye" id="icon_old"></i>
                        </button>
                    </div>
                    @error('old_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                    <div class="input-group">
                        <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Masukkan password baru" required>
                        <button class="btn-toggle-pw" type="button" onclick="togglePw('new_password', 'icon_new')" tabindex="-1">
                            <i class="bi bi-eye" id="icon_new"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        <button class="btn-toggle-pw" type="button" onclick="togglePw('new_password_confirmation', 'icon_confirm')" tabindex="-1">
                            <i class="bi bi-eye" id="icon_confirm"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning btn-login text-dark">
                    <i class="bi bi-key-fill me-1"></i>Ubah Password
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <script>
        function togglePw(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !isHidden);
            icon.classList.toggle('bi-eye-slash', isHidden);
        }
    </script>
</body>
</html>
