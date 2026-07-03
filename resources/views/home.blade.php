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

        @media (max-width: 991.98px) {
            .hero-section {
                padding: 64px 0 48px 0;
            }

            .hero-section h1 {
                font-size: 2.25rem;
            }

            .hero-section .btn {
                min-width: 160px;
            }

            .section-toolbar {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .75rem;
            }

            .section-toolbar .btn {
                width: 100%;
                max-width: 280px;
            }
        }

        @media (max-width: 767.98px) {
            .hero-section {
                padding: 56px 0 40px 0;
            }

            .hero-section h1 {
                font-size: 1.9rem;
                line-height: 1.15;
            }

            .hero-section .lead {
                font-size: 1rem;
            }

            .hero-section .btn {
                width: 100%;
                max-width: 290px;
                margin-right: 0 !important;
            }

            .hero-section .btn + .btn {
                margin-top: .75rem;
            }

            .section-title {
                font-size: 1.35rem;
                padding-left: 12px;
            }

            .form-cetak-wrapper,
            .status-wrapper {
                padding: 20px;
                border-radius: 14px;
            }

            #katalog .row.g-4.px-2 {
                row-gap: 1rem !important;
            }

            #katalog .col-sm-6,
            #katalog .col-md-4,
            #katalog .col-lg-3 {
                padding-left: .5rem;
                padding-right: .5rem;
            }

            #katalog .carousel-control-prev,
            #katalog .carousel-control-next {
                display: none;
            }

            .status-wrapper .input-group,
            .form-cetak-wrapper .d-flex.gap-4 {
                flex-direction: column;
                gap: .75rem !important;
            }

            .status-wrapper .input-group > .input-group-text,
            .status-wrapper .input-group > .form-control,
            .status-wrapper .input-group > .btn {
                width: 100%;
                border-radius: 12px !important;
            }

            .status-wrapper .input-group > .input-group-text {
                border-right: 1px solid #dee2e6;
                justify-content: center;
            }

            .status-wrapper .input-group > .form-control {
                border-left: 1px solid #dee2e6 !important;
                padding-left: .75rem !important;
            }

            footer {
                text-align: center;
            }
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
                        <a class="nav-link" href="{{ route('customer.orders') }}">
                            <i class="bi bi-receipt me-1"></i> Pesanan Saya
                        </a>
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

    {{-- ================================================
         MODAL POP-UP: Nomor Pesanan Berhasil Dibuat
         ================================================ --}}
    @if(session('new_order_number'))
    <div class="modal fade" id="modalNomorPesanan" tabindex="-1" aria-labelledby="modalNomorPesananLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">

                {{-- Header dengan gradient --}}
                <div class="modal-header border-0 pb-0 pt-4 px-4"
                     style="background: linear-gradient(135deg, #1a73e8, #4a9eff);">
                    <div class="w-100 text-center pb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width: 64px; height: 64px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-check-circle-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="modal-title text-white fw-bold mb-1" id="modalNomorPesananLabel">
                            Pesanan Berhasil Dikirim!
                        </h5>
                        <p class="text-white mb-0" style="opacity: 0.85; font-size: 0.88rem;">
                            Simpan nomor pesanan berikut untuk mengecek status cetak kamu
                        </p>
                    </div>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 pt-4 pb-3 text-center">

                    <p class="text-muted mb-2" style="font-size: 0.85rem;">Nomor Pesanan Kamu</p>

                    {{-- Kotak nomor pesanan --}}
                    <div class="d-flex align-items-center justify-content-center gap-2 mx-auto mb-3"
                         style="background: #f0f4ff; border: 2px dashed #1a73e8; border-radius: 12px; padding: 14px 20px; max-width: 320px;">
                        <span id="nomorPesananText"
                              style="font-family: monospace; font-size: 1.4rem; font-weight: 700; color: #1a73e8; letter-spacing: 1px;">
                            {{ session('new_order_number') }}
                        </span>
                        <button type="button"
                                id="btnSalinNomor"
                                class="btn btn-sm btn-outline-primary rounded-pill ms-1"
                                title="Salin nomor pesanan"
                                style="font-size: 0.75rem; padding: 3px 10px;"
                                onclick="salinNomorPesanan()">
                            <i class="bi bi-clipboard me-1"></i>Salin
                        </button>
                    </div>

                    <p class="text-muted mb-0" style="font-size: 0.82rem;">
                        <i class="bi bi-telephone me-1"></i>
                        Butuh bantuan? Hubungi kami di <strong>0852-7330-0045</strong>
                    </p>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex gap-2">
                    <button type="button"
                            class="btn btn-outline-secondary rounded-pill flex-fill"
                            data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button"
                            class="btn btn-primary rounded-pill flex-fill fw-semibold"
                            onclick="tutupModalLaluCekStatus('{{ session('new_order_number') }}')">
                        <i class="bi bi-search me-1"></i>Cek Status Sekarang
                    </button>
                </div>

            </div>
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

            <div class="d-flex justify-content-between align-items-center mb-4 section-toolbar">
                <h2 class="section-title mb-0" style="border-left: 5px solid var(--warna-utama); padding-left: 15px;">
                    <i class="bi bi-grid-fill me-2" style="color: var(--warna-utama);"></i>
                    Katalog Produk ATK
                </h2>
                <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm btn-sm">
                    Lihat Semua Produk <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div id="productCarousel" class="carousel slide">
                <div class="carousel-inner">
                    @php $chunks = $products->chunk(4); @endphp
                    @forelse($chunks as $index => $productChunk)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row row-cols-2 row-cols-lg-4 g-3 g-md-4 px-2">
                                @foreach($productChunk as $product)
                                    <div class="col">
                                        <div class="card product-card h-100 bg-white">
                                            
                                            @if($product->gambar)
                                                <img src="{{ asset('images/produk/' . $product->gambar) }}" class="card-img-top" alt="{{ $product->name_produk }}" style="height: 160px; object-fit: contain; background: #f8f9fa; padding: 10px;">
                                            @else
                                                <div class="card-img-top-placeholder bg-secondary-subtle" style="height: 160px; display: flex; align-items: center; justify-content: center; font-size: 3.5rem;">📦</div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <h6 class="card-title fw-bold text-truncate" title="{{ $product->name_produk }}">{{ $product->name_produk }}</h6>
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
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="display-1 text-muted mb-3">📭</div>
                            <h4 class="text-muted">Belum Ada Produk ATK Tersedia</h4>
                            <p class="text-muted small">Silakan tambahkan data produk baru melalui Dashboard Admin.</p>
                        </div>
                    @endforelse
                </div>

                @if($products->count() > 4)
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev" style="width: 5%; left: -50px;">
                        <span class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 40px; height: 40px; opacity: 0.8;">
                            <i class="bi bi-chevron-left text-white fs-5"></i>
                        </span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next" style="width: 5%; right: -50px;">
                        <span class="bg-dark rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 40px; height: 40px; opacity: 0.8;">
                            <i class="bi bi-chevron-right text-white fs-5"></i>
                        </span>
                    </button>
                @endif
            </div>

        </section>{{-- Akhir Katalog Slider --}}

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
                                Isi formulir di bawah ini, tim kami akan segera menghubungi kamu via WhatsApp untuk konfirmasi harga dan estimasi.
                            </p>

                            @if($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    <strong>Mohon periksa isian formulir:</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('cetak.submit') }}" method="POST" enctype="multipart/form-data">
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
                        @else
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-3"></i>
                                <div>
                                    <strong>Anda harus login terlebih dahulu!</strong><br>
                                    <small>Silakan <a href="{{ route('user.login') }}" class="alert-link">login</a> atau <a href="{{ route('user.register') }}" class="alert-link">daftar</a> untuk menggunakan layanan jasa cetak.</small>
                                </div>
                            </div>
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
                            Masukkan <strong>Nomor Pesanan</strong> yang kamu terima setelah memesan untuk melihat status terakhir pesananmu.
                        </p>

                        {{-- Form Cek Status --}}
                        <form action="{{ route('cetak.cek-status') }}" method="POST" id="formCekStatus">
                            @csrf
                            <div class="input-group input-group-lg status-search-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-upc-scan text-primary"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 ps-0"
                                       name="order_number"
                                       id="inputNomorPesanan"
                                       placeholder="Contoh: RDH-20260626-0001"
                                       value="{{ session('cek_query', old('order_number')) }}"
                                       autocomplete="off">
                                <button class="btn btn-primary fw-bold px-4" type="submit">
                                    <i class="bi bi-search me-1"></i> Cek Sekarang
                                </button>
                            </div>
                        </form>

                        {{-- Hasil: Error tidak ditemukan --}}
                        @if(session('cek_error'))
                            <div class="mt-4">
                                <div class="alert alert-warning d-flex align-items-start gap-3 mb-0"
                                     style="border-radius: 12px; border-left: 4px solid #f59e0b;">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold mb-1">Pesanan Tidak Ditemukan</div>
                                        <div style="font-size: 0.9rem;">{!! session('cek_error') !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Hasil: Pesanan ditemukan --}}
                        @if(session('cek_result'))
                            @php
                                $order = session('cek_result');
                                $statusConfig = [
                                    'Menunggu Antrean' => ['color' => '#f59e0b', 'bg' => '#fffbeb', 'border' => '#f59e0b', 'icon' => 'bi-hourglass-split', 'label' => 'Menunggu Antrean'],
                                    'diproses'         => ['color' => '#3b82f6', 'bg' => '#eff6ff', 'border' => '#3b82f6', 'icon' => 'bi-gear-fill',         'label' => 'Sedang Diproses'],
                                    'selesai'          => ['color' => '#10b981', 'bg' => '#f0fdf4', 'border' => '#10b981', 'icon' => 'bi-check-circle-fill', 'label' => 'Selesai'],
                                    'dibatalkan'       => ['color' => '#ef4444', 'bg' => '#fff5f5', 'border' => '#ef4444', 'icon' => 'bi-x-circle-fill',     'label' => 'Dibatalkan'],
                                ];
                                $cfg = $statusConfig[$order['status']] ?? $statusConfig['Menunggu Antrean'];
                                $createdAt = \Carbon\Carbon::parse($order['created_at'])->locale('id');
                                $updatedAt = \Carbon\Carbon::parse($order['updated_at'])->locale('id');
                                $isBatalkan = $order['status'] === 'dibatalkan';
                                $isMenunggu = $order['status'] === 'Menunggu Antrean';
                            @endphp

                            {{-- Notif sukses batalkan --}}
                            @if(session('success_cancel'))
                                <div class="mt-3 alert alert-success border-0 py-2 px-3"
                                     style="border-radius: 10px; font-size: 0.88rem;">
                                    <i class="bi bi-check-circle-fill me-2"></i>{!! session('success_cancel') !!}
                                </div>
                            @endif
                            {{-- Error batalkan --}}
                            @if(session('cek_error_cancel'))
                                <div class="mt-3 alert alert-danger border-0 py-2 px-3"
                                     style="border-radius: 10px; font-size: 0.88rem;">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>{!! session('cek_error_cancel') !!}
                                </div>
                            @endif

                            <div class="mt-4">
                                <div class="card border-0 shadow-sm" style="border-radius: 14px; border-left: 5px solid {{ $cfg['border'] }} !important; background: {{ $cfg['bg'] }};">
                                    <div class="card-body px-4 py-3">

                                        {{-- Header: Nomor & Status --}}
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <small class="text-muted" style="font-size: 0.78rem;">NOMOR PESANAN</small>
                                                <div class="fw-bold fs-6" style="color: var(--warna-gelap); letter-spacing: 0.5px; font-family: monospace;">
                                                    {{ $order['order_number'] }}
                                                </div>
                                            </div>
                                            <span class="badge px-3 py-2 rounded-pill fw-semibold"
                                                  style="background-color: {{ $cfg['color'] }}; font-size: 0.85rem;">
                                                <i class="bi {{ $cfg['icon'] }} me-1"></i>
                                                {{ $cfg['label'] }}
                                            </span>
                                        </div>

                                        <hr class="my-2" style="border-color: rgba(0,0,0,0.08);">

                                        {{-- Detail Pesanan --}}
                                        <div class="row g-2 mt-1" style="font-size: 0.88rem;">
                                            <div class="col-6">
                                                <div class="text-muted">Detail Cetak</div>
                                                <div class="fw-semibold">{{ $order['detail_pesanan'] }}</div>
                                            </div>
                                            @if($order['catatan'])
                                            <div class="col-6">
                                                <div class="text-muted">Catatan</div>
                                                <div class="fw-semibold">{{ $order['catatan'] }}</div>
                                            </div>
                                            @endif
                                            <div class="col-6">
                                                <div class="text-muted">Tanggal Pesan</div>
                                                <div class="fw-semibold">{{ $createdAt->translatedFormat('d F Y, H:i') }} WIB</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted">Update Terakhir</div>
                                                <div class="fw-semibold">{{ $updatedAt->translatedFormat('d F Y, H:i') }} WIB</div>
                                            </div>
                                        </div>

                                        {{-- Info Pembatalan --}}
                                        @if($isBatalkan && $order['alasan_pembatalan'])
                                            <div class="mt-3 p-3 rounded" style="background: #fee2e2; font-size: 0.85rem;">
                                                <div class="fw-semibold text-danger mb-1">
                                                    <i class="bi bi-x-circle-fill me-1"></i>
                                                    Dibatalkan oleh {{ $order['dibatalkan_oleh'] === 'admin' ? 'Admin' : 'Kamu' }}
                                                </div>
                                                <div class="text-danger" style="opacity: 0.85;">
                                                    Alasan: {{ $order['alasan_pembatalan'] }}
                                                </div>
                                            </div>
                                        @elseif(!$isBatalkan)
                                            {{-- Progress tracker (hanya tampil kalau tidak dibatalkan) --}}
                                            <div class="mt-3 pt-2" style="border-top: 1px solid rgba(0,0,0,0.07);">
                                                <div class="d-flex justify-content-between align-items-center position-relative">
                                                    <div class="position-absolute" style="top: 14px; left: 10%; right: 10%; height: 3px; background: #e2e8f0; z-index: 0;"></div>
                                                    @php
                                                        $steps      = ['Menunggu Antrean', 'diproses', 'selesai'];
                                                        $currentIdx = array_search($order['status'], $steps);
                                                        $stepIcons  = ['bi-hourglass-split', 'bi-gear-fill', 'bi-check-circle-fill'];
                                                        $stepLabels = ['Menunggu', 'Diproses', 'Selesai'];
                                                    @endphp
                                                    @foreach($steps as $i => $step)
                                                        @php
                                                            $isDone    = $i <= $currentIdx;
                                                            $isCurrent = $i === $currentIdx;
                                                        @endphp
                                                        <div class="d-flex flex-column align-items-center position-relative" style="z-index: 1; flex: 1;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-1"
                                                                 style="width: 30px; height: 30px;
                                                                        background: {{ $isDone ? $cfg['color'] : '#e2e8f0' }};
                                                                        box-shadow: {{ $isCurrent ? '0 0 0 4px ' . $cfg['color'] . '33' : 'none' }};">
                                                                <i class="bi {{ $stepIcons[$i] }}"
                                                                   style="font-size: 0.85rem; color: {{ $isDone ? 'white' : '#94a3b8' }};"></i>
                                                            </div>
                                                            <small style="font-size: 0.72rem; font-weight: {{ $isCurrent ? '700' : '400' }}; color: {{ $isDone ? $cfg['color'] : '#94a3b8' }};">
                                                                {{ $stepLabels[$i] }}
                                                            </small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            @if($order['status'] === 'selesai')
                                                <div class="mt-3 p-2 text-center rounded"
                                                     style="background: #dcfce7; font-size: 0.85rem; color: #15803d;">
                                                    <i class="bi bi-bag-check-fill me-1"></i>
                                                    Pesanan kamu sudah selesai! Silakan ambil di toko.
                                                </div>
                                            @elseif($order['status'] === 'diproses')
                                                <div class="mt-3 p-2 text-center rounded"
                                                     style="background: #dbeafe; font-size: 0.85rem; color: #1d4ed8;">
                                                    <i class="bi bi-clock-history me-1"></i>
                                                    Pesanan sedang kami kerjakan. Mohon ditunggu ya!
                                                </div>
                                            @else
                                                <div class="mt-3 p-2 text-center rounded"
                                                     style="background: #fef9c3; font-size: 0.85rem; color: #92400e;">
                                                    <i class="bi bi-people-fill me-1"></i>
                                                    Pesanan masuk antrian. Tim kami akan segera memprosesnya.
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Tombol batalkan (hanya jika Menunggu Antrean) --}}
                                        @if($isMenunggu)
                                            <div class="mt-3 pt-2 text-end" style="border-top: 1px solid rgba(0,0,0,0.07);">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                        style="font-size: 0.82rem;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalBatalPelanggan">
                                                    <i class="bi bi-x-lg me-1"></i>Batalkan Pesanan Ini
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </section>

    </div>{{-- Akhir .container --}}

    {{-- ================================================
         MODAL: Batalkan Pesanan (Pelanggan)
         ================================================ --}}
    @if(session('cek_result') && (session('cek_result')['status'] ?? '') === 'Menunggu Antrean')
    <div class="modal fade" id="modalBatalPelanggan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #fff5f5;">
                    <div>
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>Batalkan Pesanan
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                            Pesanan: <strong>{{ session('cek_result')['order_number'] }}</strong>
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('cetak.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_number" value="{{ session('cek_result')['order_number'] }}">
                    <div class="modal-body px-4 py-3">
                        <div class="alert alert-warning border-0 py-2 px-3 mb-3"
                             style="border-radius: 10px; background: #fef9c3; font-size: 0.83rem;">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Pembatalan <strong>tidak dapat dibatalkan</strong>. Pastikan kamu yakin sebelum melanjutkan.
                        </div>
                        <label class="form-label fw-semibold mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="alasan_pembatalan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Contoh: Berubah pikiran, dokumen belum siap, dll."
                                  maxlength="500"
                                  required></textarea>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-x-lg me-1"></i>Ya, Batalkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    <!-- ================================================
         JAVASCRIPT KUSTOM
         ================================================ -->
    <script>
        // ============================================================
        // SHORTCUT RAHASIA — Ctrl + Alt + A
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.altKey && e.key === 'a') {
                e.preventDefault();
                window.location.href = '/admin/login';
            }
        });

        // Tampilkan modal nomor pesanan otomatis setelah submit berhasil
        @if(session('new_order_number'))
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('modalNomorPesanan'));
                modal.show();
            });
        @endif

        // Scroll otomatis ke seksi cek-status jika ada hasil pencarian
        @if(session('cek_result') || session('cek_error'))
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('cek-status');
                if (el) {
                    setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'start' }), 300);
                }
            });
        @endif

        // Fungsi salin nomor pesanan ke clipboard
        function salinNomorPesanan() {
            const nomor = document.getElementById('nomorPesananText').textContent.trim();
            navigator.clipboard.writeText(nomor).then(function () {
                const btn = document.getElementById('btnSalinNomor');
                btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin!';
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');
                setTimeout(function () {
                    btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Salin';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            });
        }

        // Tutup modal lalu scroll ke cek-status dan isi nomor
        function tutupModalLaluCekStatus(nomor) {
            const modalEl = document.getElementById('modalNomorPesanan');
            const modal   = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Setelah modal selesai menutup (animasi ~300ms), scroll dan isi form
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);
                const input = document.getElementById('inputNomorPesanan');
                if (input) input.value = nomor;
                const seksi = document.getElementById('cek-status');
                if (seksi) seksi.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }
    </script>
</body>
</html>
