<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RamaDigital Hub — Toko ATK & Jasa Cetak</title>

    {{-- Bootstrap CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    {{-- Bootstrap Icons (opsional, untuk ikon-ikon kecil) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ============================================
           CSS KUSTOM RamaDigital Hub
           ============================================ */

        /* Warna tema utama */
        :root {
            --warna-utama: #1a73e8;
            --warna-aksen: #ff6d00;
            --warna-gelap: #1c2b4a;
        }

        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- Navbar --- */
        .navbar-brand-custom {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navbar-brand-custom span {
            color: var(--warna-aksen); /* Warna oranye untuk "Digital" */
        }

        /* --- Hero Section --- */
        .hero-section {
            background: linear-gradient(135deg, var(--warna-gelap) 0%, var(--warna-utama) 100%);
            color: white;
            padding: 80px 0 60px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.8rem;
            font-weight: 800;
        }

        .hero-section .badge-custom {
            background-color: var(--warna-aksen);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* --- Kartu Produk --- */
        .product-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .product-card .card-img-top-placeholder {
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        .product-card .harga {
            color: var(--warna-aksen);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .stock-badge {
            font-size: 0.75rem;
        }

        /* --- Section Titles --- */
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--warna-gelap);
            border-left: 5px solid var(--warna-utama);
            padding-left: 15px;
            margin-bottom: 25px;
        }

        /* --- Form Cetak --- */
        .form-cetak-wrapper {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }

        /* --- Cek Status --- */
        .status-wrapper {
            background: linear-gradient(135deg, #e8f4fd, #d1ecf1);
            border-radius: 16px;
            padding: 35px;
            border: 1px solid #bee5eb;
        }

        /* --- Footer --- */
        footer {
            background-color: var(--warna-gelap);
            color: #adb5bd;
        }

        footer a {
            color: #adb5bd;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
        }

        /* --- Kustomisasi Tombol Login Navbar --- */
.btn-login-custom {
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
    transition: all 0.2s ease-in-out;
}

.btn-login-custom:hover {
    background-color: #ffc107 !important; /* Warna kuning btn-warning Bootstrap */
    border-color: #ffc107 !important;
    color: #1c2b4a !important; /* Teks berubah menjadi gelap agar kontras dan terbaca */
}
    </style>
</head>
<body>

    <!-- ================================================
         NAVBAR / HEADER
         ================================================ -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--warna-gelap);">
        <div class="container">

            {{-- Logo / Brand --}}
            <a class="navbar-brand navbar-brand-custom" href="/">
                <i class="bi bi-printer-fill me-2" style="color: var(--warna-aksen);"></i>
                Rama<span>Digital</span> Hub
            </a>

            {{-- Tombol Hamburger untuk Mobile --}}
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu"
                    aria-controls="navbarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Menu Navigasi --}}
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#katalog">Katalog ATK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jasa-cetak">Jasa Cetak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cek-status">Cek Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.view') }}">
                            <i class="bi bi-cart3 me-1"></i> Keranjang
                            @if(!empty($cartCount) && $cartCount > 0)
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Auth: tampilkan dropdown jika login, tombol login jika belum --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white px-3" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdownUser">
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('user.logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('user-logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                    <form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link btn btn-login-custom px-3" href="{{ route('user.login') }}" style="border-radius: 8px;">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login User
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>

        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- ================================================
         HERO SECTION
         ================================================ -->
    <section class="hero-section">
        <div class="container">
            <span class="badge-custom mb-3 d-inline-block">Toko ATK & Jasa Cetak Terpercaya</span>
            <h1>Selamat Datang di<br>RamaDigital Hub</h1>
            <p class="lead mt-3 mb-4" style="opacity: 0.85; max-width: 550px; margin: auto;">
                Temukan berbagai kebutuhan alat tulis kantor dan layanan cetak dokumen berkualitas di satu tempat.
            </p>
            <a href="#katalog" class="btn btn-warning btn-lg fw-bold me-2 px-4">
                Lihat Katalog
            </a>
            <a href="#jasa-cetak" class="btn btn-outline-light btn-lg me-2 px-4">
                Jasa Cetak
            </a>

        </div>
    </section>

    <div class="container py-5">

        <!-- ================================================
             SEKSI KATALOG PRODUK ATK (STATIS)
             ================================================ -->
       <section id="katalog" class="mb-5">

            <h2 class="section-title">
                <i class="bi bi-grid-fill me-2" style="color: var(--warna-utama);"></i>
                Katalog Produk ATK
            </h2>

            {{-- Menggunakan bootstrap grid row bawaan template kamu --}}
            <div class="row g-4">

                @forelse($products as $product)
                    {{-- Responsif grid: 1 kolom di HP, 2 di tablet, 3 di laptop, 4 di layar besar --}}
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            
                            @if($product->gambar)
                                <img src="{{ asset('images/produk/' . $product->gambar) }}" class="card-img-top" alt="{{ $product->name_produk }}" style="height: 160px; object-fit: cover;">
                            @else
                                <div class="card-img-top-placeholder bg-secondary-subtle">📦</div>
                            @endif
                            
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $product->name_produk }}</h6>
                                <p class="harga mb-1">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                
                                @if($product->stok > 10)
                                    <span class="badge bg-success stock-badge">Stok: {{ $product->stok }} pcs</span>
                                @elseif($product->stok > 0)
                                    <span class="badge bg-warning text-dark stock-badge">Stok Menipis: {{ $product->stok }} pcs</span>
                                @else
                                    <span class="badge bg-danger stock-badge">Stok Habis</span>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="btn btn-primary btn-sm w-100" {{ $product->stok == 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Ditampilkan jika di database admin belum ada produk sama sekali --}}
                    <div class="col-12 text-center py-5">
                        <div class="display-1 text-muted mb-3">📭</div>
                        <h4 class="text-muted">Belum Ada Produk ATK Tersedia</h4>
                        <p class="text-muted small">Silakan tambahkan data produk baru terlebih dahulu melalui Dashboard Admin.</p>
                    </div>
                @endforelse

            </div>{{-- Akhir row --}}

        </section>{{-- Akhir Katalog --}}

        <hr class="my-5">

        <!-- ================================================
             SEKSI FORMULIR JASA CETAK DOKUMEN (STATIS)
             ================================================ -->
        <section id="jasa-cetak" class="mb-5">

            <h2 class="section-title">
                <i class="bi bi-printer-fill me-2" style="color: var(--warna-utama);"></i>
                Formulir Jasa Cetak Dokumen
            </h2>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-cetak-wrapper">

                        @auth
                            <p class="text-muted mb-4">
                                <i class="bi bi-info-circle me-1"></i>
                                Isi formulir di bawah ini, tim kami akan segera menghubungi kamu via WhatsApp.
                            </p>
                        @else
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3"></i>
                                <div>
                                    <strong>Anda harus login terlebih dahulu!</strong><br>
                                    <small>Silakan <a href="{{ route('user.login') }}" class="alert-link">login</a> atau <a href="{{ route('user.register') }}" class="alert-link">daftar</a> untuk menggunakan layanan jasa cetak.</small>
                                </div>
                            </div>
                        @endauth

                        @auth
                            {{--
                                CATATAN PENTING:
                                Form ini STATIS — tombol "Pesan Sekarang" belum terhubung ke backend.
                                Untuk proyek lanjutan, action="" bisa diisi dengan route yang memproses data.
                            --}}
                            <form action="#" method="POST">
                                @csrf

                                {{-- Baris 2: Jenis Kertas & Jumlah Lembar --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="jenisKertas" class="form-label fw-semibold">
                                        <i class="bi bi-file-earmark me-1"></i> Jenis Kertas
                                    </label>
                                    {{-- DROPDOWN PILIHAN KERTAS --}}
                                    <select class="form-select" id="jenisKertas" name="jenis_kertas" required>
                                        <option value="" disabled selected>-- Pilih Jenis Kertas --</option>
                                        <optgroup label="Kertas Biasa">
                                            <option value="hvs_a4">HVS A4</option>
                                            <option value="hvs_f4">HVS F4/Folio</option>
                                        </optgroup>
                                        <optgroup label="Kertas Foto">
                                            <option value="foto_glossy">Foto Glossy (Foto)</option>
                                            <option value="foto_matte">Foto Matte (Stiker)</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlahLembar" class="form-label fw-semibold">
                                        <i class="bi bi-stack me-1"></i> Jumlah Lembar / Eksemplar
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="jumlahLembar"
                                           name="jumlah"
                                           min="1"
                                           placeholder="Contoh: 10"
                                           required>
                                </div>
                            </div>

                            {{-- Baris 3: Pilihan Warna Cetak --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-palette me-1"></i> Mode Cetak
                                </label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="mode_cetak" id="cetakBW" value="hitam_putih" checked>
                                        <label class="form-check-label" for="cetakBW">
                                            Hitam & Putih
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="mode_cetak" id="cetakWarna" value="full_color">
                                        <label class="form-check-label" for="cetakWarna">
                                            Full Color
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Baris 4: Upload File --}}
                            <div class="mb-3">
                                <label for="fileUpload" class="form-label fw-semibold">
                                    <i class="bi bi-cloud-upload me-1"></i> Upload File Dokumen
                                </label>
                                {{-- TOMBOL UPLOAD FILE TIRUAN --}}
                                <input class="form-control" type="file" id="fileUpload" name="file_dokumen"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">
                                    Format yang diterima: PDF, Word (.doc/.docx), atau Gambar (JPG/PNG). Maks. 10 MB.
                                </div>
                            </div>

                            {{-- Baris 5: Catatan Tambahan --}}
                            <div class="mb-4">
                                <label for="catatanTambahan" class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text me-1"></i> Catatan Tambahan (Opsional)
                                </label>
                                <textarea class="form-control" id="catatanTambahan"
                                          name="catatan" rows="3"
                                          placeholder="Contoh: Bolak-balik, jilid spiral, dst."></textarea>
                            </div>

                                {{-- TOMBOL SUBMIT --}}
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg fw-bold" style="background-color: var(--warna-utama); color: white;">
                                        <i class="bi bi-send-fill me-2"></i>
                                        Pesan Jasa Cetak Sekarang
                                    </button>
                                </div>

                            </form>
                        @endauth

                    </div>
                </div>
            </div>

        </section>{{-- Akhir Jasa Cetak --}}

        <hr class="my-5">

        <!-- ================================================
             SEKSI CEK STATUS PESANAN
             ================================================ -->
        <section id="cek-status" class="mb-5">

            <h2 class="section-title">
                <i class="bi bi-search me-2" style="color: var(--warna-utama);"></i>
                Cek Status Pesanan
            </h2>

            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="status-wrapper">

                        <p class="mb-4 text-muted">
                            Masukkan <strong>Nomor Pesanan</strong> yang kamu terima via WhatsApp untuk melihat status terakhir pesananmu.
                        </p>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-upc-scan"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   id="inputNomorPesanan"
                                   placeholder="Masukkan No. Pesanan (contoh: RDH-20240815-001)">
                            <button class="btn btn-primary fw-bold" type="button" id="btnCekStatus">
                                <i class="bi bi-search me-1"></i> Cek Sekarang
                            </button>
                        </div>

                       

                        {{-- Contoh tampilan hasil status (statis) --}}
                        <div class="mt-4" id="hasilStatus" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill flex-shrink-0 me-3 fs-4"></i>
                                <div>
                                    <strong>Status Pesanan #RDH-20240815-001:</strong><br>
                                    🔄 Sedang diproses — Estimasi selesai: Hari ini pukul 16.00 WIB.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>{{-- Akhir .container --}}

    <!-- ================================================
         FOOTER
         ================================================ -->
    <footer class="py-4 mt-3">
        <div class="container text-center">
            <p class="mb-1">
                <strong style="color: white;">RamaDigital Hub</strong>
            </p>
            <p class="mb-0" style="font-size: 0.85rem;">
                📍 Jl. Mayor Iskandar No.771, Baturaja Timur &nbsp;|&nbsp;
                📱 WA: 0852-7330-0045 &nbsp;|&nbsp;
                © 2026 RamaDigital Hub
            </p>
        </div>
    </footer>

    <!-- ================================================
         Bootstrap JS via CDN (wajib ada di bagian bawah)
         ================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jyor3NBAIsDfCxnXHcpkEPRiNqaEB"
            crossorigin="anonymous"></script>

    <!-- ================================================
         JAVASCRIPT KUSTOM
         ================================================ -->
    <script>
        // ============================================================
        // BAGIAN 1: Simulasi Tombol "Cek Status Pesanan" (Statis)
        // ============================================================
        document.getElementById('btnCekStatus').addEventListener('click', function() {
            var inputVal = document.getElementById('inputNomorPesanan').value.trim();
            var hasilDiv = document.getElementById('hasilStatus');

            if (inputVal !== '') {
                hasilDiv.style.display = 'block'; // Tampilkan hasil
            } else {
                hasilDiv.style.display = 'none';
                alert('⚠️ Masukkan nomor pesanan terlebih dahulu!');
            }
        });


        // ============================================================
        // BAGIAN 2: SHORTCUT RAHASIA — Ctrl + Alt + A
        // ============================================================
        // Fungsi ini mendeteksi kombinasi tombol keyboard secara diam-diam.
        // Tidak ada tombol atau link yang terlihat oleh pengunjung biasa.
        //
        // Cara kerja:
        // 1. 'keydown' = event yang terpicu saat tombol ditekan
        // 2. e.ctrlKey = true jika tombol Ctrl sedang ditekan
        // 3. e.altKey  = true jika tombol Alt sedang ditekan
        // 4. e.key === 'a' = mengecek apakah tombol 'A' yang ditekan
        //    (gunakan huruf kecil karena browser mengembalikan huruf kecil)
        // 5. e.preventDefault() = mencegah aksi default browser
        //    (misalnya Alt bisa membuka menu browser di beberapa sistem)
        // ============================================================

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.key === 'a') {

                // Mencegah aksi default browser agar kombinasi bekerja mulus
                e.preventDefault();

                // Arahkan ke halaman login admin (tidak terlihat di UI)
                // Menggunakan URL langsung ke route '/admin/login'
                window.location.href = '/admin/login';
            }
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</body>
</html>
