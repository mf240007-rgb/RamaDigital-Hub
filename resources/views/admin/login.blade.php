<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — RamaDigital Hub</title>

    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ============================================
           CSS untuk Halaman Login Admin
           ============================================ */

        :root {
            --warna-gelap: #1c2b4a;
            --warna-utama: #1a73e8;
        }

        /* Latar belakang penuh yang gelap & elegan */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--warna-gelap) 0%, #2c3e6b 50%, var(--warna-utama) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Card login yang berada di tengah layar */
        .login-card-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        /* Header card dengan warna gelap */
        .login-card-header {
            background: linear-gradient(135deg, var(--warna-gelap), #2c3e6b);
            color: white;
            padding: 35px 35px 25px 35px;
            text-align: center;
        }

        .login-card-header .icon-lock {
            font-size: 2.8rem;
            background: rgba(255,255,255,0.1);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .login-card-header h4 {
            font-weight: 700;
            margin-bottom: 3px;
        }

        .login-card-header p {
            font-size: 0.85rem;
            opacity: 0.7;
            margin-bottom: 0;
        }

        /* Body card */
        .login-card-body {
            padding: 35px;
            background: white;
        }

        /* Tombol login */
        .btn-login {
            background: linear-gradient(135deg, var(--warna-gelap), var(--warna-utama));
            color: white;
            border: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px;
            border-radius: 10px;
            transition: opacity 0.2s ease, transform 0.1s ease;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Input form kustom */
        .form-control-custom {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: var(--warna-utama);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
        }

        /* Link kembali ke beranda */
        .back-link {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card-wrapper">

        {{-- Link kembali ke beranda --}}
        <div class="text-center mb-3">
            <a href="/" class="back-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>

        {{-- CARD UTAMA LOGIN --}}
        <div class="card login-card">

            {{-- Header Card --}}
            <div class="login-card-header">
                <div class="icon-lock">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h4>Portal Admin</h4>
                <p>RamaDigital Hub — Akses Terbatas</p>
            </div>

            {{-- Body Card --}}
            <div class="login-card-body">

                {{-- ============================================
                     TAMPILKAN PESAN ERROR (jika login gagal)
                     ============================================
                     @if(session('error')) → Blade memeriksa apakah
                     ada data 'error' yang dikirim dari Controller.
                     Jika ada, tampilkan alert merah Bootstrap.
                ============================================ --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Tampilkan pesan sukses (misal setelah logout) --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill flex-shrink-0 me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- ============================================
                     FORM LOGIN
                     action: mengarah ke route 'admin.login.submit'
                     method: POST (mengirim data ke server)
                     @csrf: wajib di semua form POST Laravel (keamanan)
                ============================================ --}}
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    {{-- Input Username --}}
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold text-secondary">
                            <i class="bi bi-person-fill me-1"></i> Username
                        </label>
                        <input type="text"
                               class="form-control form-control-custom @error('username') is-invalid @enderror"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Masukkan username admin"
                               autofocus
                               required>
                        {{-- Tampilkan pesan error validasi Laravel --}}
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-secondary">
                            <i class="bi bi-lock-fill me-1"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control form-control-custom @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required
                                   style="border-radius: 10px 0 0 10px;">
                            {{-- Tombol Show/Hide Password --}}
                            <button class="btn btn-outline-secondary"
                                    type="button"
                                    id="btnTogglePassword"
                                    title="Tampilkan/Sembunyikan Password"
                                    style="border-radius: 0 10px 10px 0;">
                                <i class="bi bi-eye-fill" id="iconTogglePassword"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Masuk ke Dashboard
                        </button>
                    </div>

                </form>

                {{-- Divider --}}
                <hr class="my-4">

            </div>{{-- Akhir login-card-body --}}

        </div>{{-- Akhir .login-card --}}

        {{-- Teks kecil di bawah card --}}
        <p class="text-center mt-3 back-link" style="font-size: 0.78rem; opacity: 0.6;">
            <i class="bi bi-lock me-1"></i>
            Halaman ini khusus untuk administrator. Akses tidak sah dilarang.
        </p>

    </div>{{-- Akhir .login-card-wrapper --}}

    {{-- Bootstrap JS via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    {{-- JavaScript untuk toggle show/hide password --}}
    <script>
        document.getElementById('btnTogglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var icon = document.getElementById('iconTogglePassword');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';       // Tampilkan password
                icon.className = 'bi bi-eye-slash-fill'; // Ganti ikon
            } else {
                passwordInput.type = 'password';   // Sembunyikan lagi
                icon.className = 'bi bi-eye-fill';  // Kembalikan ikon
            }
        });
    </script>

</body>
</html>
