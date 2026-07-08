<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - RamaDigital Hub</title>

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
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--warna-gelap);
            margin-bottom: 10px;
        }

        .register-header span {
            color: var(--warna-aksen);
        }

        .register-header p {
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

        .btn-register {
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

        .btn-register:hover {
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
            grid-template-columns: 1fr; /* Diubah menjadi 1fr agar input Nama Lengkap memanjang penuh dan proporsional */
            gap: 15px;
        }

        .input-group-text {
            border: 2px solid #e9ecef;
            background-color: white;
        }

        /* Perbaikan CSS agar border kiri input tetap rapi walau error */
        .input-group .form-control {
            border-left: none;
        }
        .input-group .form-control.is-invalid {
            border-left: 2px solid #dc3545;
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 30px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <a href="/" class="back-link">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>

        <div class="register-header">
            <h1>
                <i class="bi bi-printer-fill" style="color: var(--warna-aksen);"></i>
                Rama<span>Digital</span> Hub
            </h1>
            <p>Daftar akun untuk memesan jasa cetak dokumen</p>
        </div>

        {{-- Error Messages Global --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Gagal Daftar!</strong> Mohon periksa kembali data Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Register Form --}}
        <form action="{{ route('user.register.submit') }}" method="POST">
            @csrf

            {{-- Username --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name" class="form-label">
                        <i class="bi bi-person-check me-2"></i>Username
                    </label>
                    <input type="text"
                           class="form-control @error('full_name') is-invalid @enderror"
                           id="full_name"
                           name="full_name"
                           value="{{ old('full_name') }}"
                           placeholder="Username"
                           required>
                    @error('full_name')
                        <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- WhatsApp Number --}}
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
                       maxlength="15"
                       pattern="[0-9]{10,15}"
                       placeholder="Contoh: 08123456789"
                       required>
                @error('whatsapp')
                    <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
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
                           placeholder="Minimal 8 karakter"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1"
                            style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0;">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                @else
                    <small class="text-muted d-block mt-1">Minimal 8 karakter, gunakan kombinasi huruf, angka, dan simbol</small>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
                </label>
                <div class="input-group">
                    <input type="password"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Ulangi password"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" tabindex="-1"
                            style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0;">
                        <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-register">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </button>
        </form>

        {{-- Login Link --}}
        <div class="auth-link">
            Sudah punya akun?
            <a href="{{ route('user.login') }}">Masuk di sini</a>
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
        function toggleVisibility(btnId, inputId, iconId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            });
        }

        toggleVisibility('togglePassword',        'password',              'togglePasswordIcon');
        toggleVisibility('togglePasswordConfirm', 'password_confirmation', 'togglePasswordConfirmIcon');
    </script>

</body>
</html>