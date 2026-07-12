<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RamaDigital Hub</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brand/rd-logo.svg') }}">

    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
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
        }

        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }

        .login-brand-logo { display: block; width: min(100%, 260px); height: auto; margin: 0 auto; }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--warna-gelap);
            margin-bottom: 10px;
        }

        .login-header span {
            color: var(--warna-aksen);
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--warna-gelap);
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--warna-utama);
            box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            background-color: var(--warna-utama);
            border: none;
            color: white;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #1557b0;
            color: white;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }

        .auth-link a {
            color: var(--warna-utama);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: var(--warna-utama);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #1557b0;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
                margin: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <a href="/" class="back-link">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>

        <div class="login-header">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <img src="{{ asset('images/brand/rd-logo.svg') }}" alt="RD Logo" style="height: 48px; width: auto; margin-right: 12px; vertical-align: middle;">
                <span style="font-size: 1.8rem; font-weight: 800; color: var(--warna-gelap); letter-spacing: 0.3px;">
                    Rama<span style="color: var(--warna-aksen);">Digital</span> Hub
                </span>
            </div>
            <p>Masuk untuk memesan jasa cetak dokumen</p>
        </div>

        {{-- Success / Info Messages --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Gagal Login!</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('user.login.submit') }}" method="POST">
            @csrf

            {{-- Username --}}
            <div class="form-group">
                <label for="full_name" class="form-label">
                    <i class="bi bi-person me-2"></i>Username
                </label>
                <input type="text"
                       class="form-control @error('full_name') is-invalid @enderror"
                       id="full_name"
                       name="full_name"
                       value="{{ old('full_name') }}"
                       placeholder="Username"
                       required>
                @error('full_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- WhatsApp Field --}}
            <div class="form-group">
                <label for="whatsapp" class="form-label">
                    <i class="bi bi-whatsapp me-2"></i>Nomor WhatsApp
                </label>
                <input type="tel"
                       class="form-control @error('whatsapp') is-invalid @enderror"
                       id="whatsapp"
                       name="whatsapp"
                       value="{{ old('whatsapp') }}"
                      inputmode="numeric"
                      maxlength="13"
                        pattern="[0-9]{10,13}"
                       placeholder="08xx-xxxx-xxxx"
                       required>
                @error('whatsapp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>Password
                </label>
                <div class="input-group">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1"
                            style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0;">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="{{ route('password.forgot') }}" class="text-decoration-none text-muted">
                <i class="bi bi-question-circle me-1"></i> Lupa Kata Sandi?
            </a>
        </div>

        {{-- Register Link --}}
        <div class="auth-link">
            Belum punya akun?
            <a href="{{ route('user.register') }}">Daftar di sini</a>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <script>
        const whatsappInput = document.getElementById('whatsapp');

        whatsappInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 13);
        });

        // Toggle show/hide password
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleIcon.classList.toggle('bi-eye', !isHidden);
            toggleIcon.classList.toggle('bi-eye-slash', isHidden);
        });
    </script>

</body>
</html>
