<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — RamaDigital Hub</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brand/rd-logo.svg') }}">

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

        .btn-warning {
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
        }

        .info-box {
            background: #fff4e5;
            border: 1px solid #ffe3b8;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-box strong {
            color: #b55e00;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--warna-utama);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="page-card">
        <div class="page-card-header">
            <h1>Lupa Kata Sandi?</h1>
            <p class="mb-0">Ikuti langkah di bawah untuk meminta reset password ke admin.</p>
        </div>

        <div class="page-card-body">
            <a href="{{ route('user.login') }}" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali ke Login</a>

            <div class="info-box">
                <p class="mb-2"><strong>Langkah 1:</strong> Masukkan nama lengkap dan username Anda.</p>
                <p class="mb-0">Kemudian klik tombol untuk menghubungi admin via WhatsApp. Pesan akan otomatis mencantumkan nama pelanggan dan username akun.</p>
            </div>

            <div class="mb-4">
                <label for="customer_name" class="form-label fw-semibold">Nama Pelanggan</label>
                <input type="text" id="customer_name" class="form-control" placeholder="Masukkan nama lengkap Anda">
            </div>

            <div class="mb-4">
                <label for="customer_username" class="form-label fw-semibold">Username</label>
                <input type="text" id="customer_username" class="form-control" placeholder="Masukkan username Anda">
            </div>

            <div class="mb-4">
                <button id="reset-button" class="btn btn-warning w-100 mb-3" type="button">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Admin via WhatsApp untuk Reset Akun
                </button>
                <small class="text-muted">Ganti nomor admin di file view jika diperlukan.</small>
            </div>

            <div class="info-box">
                <p class="mb-2"><strong>Langkah 2:</strong> Setelah admin mereset password ke default <code>password123</code>, gunakan tombol di bawah untuk mengubah password Anda secara mandiri.</p>
                <a href="{{ route('password.reset') }}" class="btn btn-outline-secondary w-100">Ubah Kata Sandi</a>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('reset-button');
        const nameInput = document.getElementById('customer_name');
        const usernameInput = document.getElementById('customer_username');

        const adminPhone = '6281234567890';
        const defaultMessage = 'Halo Admin, saya ingin meminta reset password akun saya.';

        function updateLink() {
            const nameValue = nameInput.value.trim();
            const usernameValue = usernameInput.value.trim();
            let message = defaultMessage;

            if (nameValue || usernameValue) {
                message = 'Halo Admin, mohon reset password akun saya.';
                if (nameValue) {
                    message += ' Nama pelanggan: ' + nameValue + '.';
                }
                if (usernameValue) {
                    message += ' Username: ' + usernameValue + '.';
                }
                message += ' Terima kasih.';
            }

            btn.onclick = function () {
                const url = 'https://api.whatsapp.com/send?phone=' + encodeURIComponent(adminPhone) + '&text=' + encodeURIComponent(message);
                window.open(url, '_blank');
            };
        }

        nameInput.addEventListener('input', updateLink);
        usernameInput.addEventListener('input', updateLink);
        updateLink();
    </script>
</body>
</html>
