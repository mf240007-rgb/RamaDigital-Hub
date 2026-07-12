<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RamaDigital Hub</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/brand/rd-logo.svg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { --warna-utama: #1a73e8; --warna-aksen: #ff6d00; --warna-gelap: #1c2b4a; }
        body { background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        .page-shell { padding-top: 70px; }
        .navbar-brand-custom { font-size: 1.35rem; font-weight: 700; letter-spacing: 0.3px; }
        .navbar-brand-custom span { color: var(--warna-aksen); }
        .brand-logo-icon { display: inline-block; height: 38px; width: auto; margin-right: 10px; vertical-align: middle; }
        .product-card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .product-card .harga { color: var(--warna-aksen); font-weight: 700; font-size: 1.1rem; }
        .stock-badge { font-size: 0.75rem; }
        .section-title { font-size: 1.8rem; font-weight: 700; color: var(--warna-gelap); border-left: 5px solid var(--warna-utama); padding-left: 15px; margin-bottom: 25px; }
        .form-cetak-wrapper { background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
        .status-wrapper { background: linear-gradient(135deg, #e8f4fd, #d1ecf1); border-radius: 16px; padding: 35px; border: 1px solid #bee5eb; }
        footer { background-color: var(--warna-gelap); color: #adb5bd; }
        footer a { color: #adb5bd; text-decoration: none; }
        footer a:hover { color: white; }
        .btn-login-custom { color: #ffffff !important; border-color: rgba(255, 255, 255, 0.4) !important; transition: all 0.2s ease-in-out; }
        .btn-login-custom:hover { background-color: #ffc107 !important; border-color: #ffc107 !important; color: #1c2b4a !important; }

        @media (max-width: 991.98px) {
            .page-shell { padding-top: 82px; }

            .navbar-collapse {
                margin-top: .75rem;
                padding: .75rem;
                background: rgba(28, 43, 74, 0.98);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 16px;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            }

            .navbar-nav {
                gap: .35rem;
            }

            .navbar-nav .nav-item {
                width: 100%;
            }

            .navbar-nav .nav-link {
                width: 100%;
                padding: .8rem 1rem;
                border-radius: 12px;
            }

            .navbar-nav .nav-link.btn-login-custom {
                text-align: center;
            }

            .navbar-nav .dropdown-menu {
                width: 100%;
                margin-top: .35rem;
            }

            .alert-wrapper .alert {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .75rem !important;
            }

            .alert-wrapper .btn-close {
                margin-left: 0 !important;
                align-self: flex-end;
            }
        }

        @media (max-width: 767.98px) {
            .section-title { font-size: 1.35rem; padding-left: 12px; margin-bottom: 18px; }
            .form-cetak-wrapper, .status-wrapper { padding: 20px; border-radius: 14px; }
            .product-card .harga { font-size: 1rem; }
            .product-card .card-img-top-placeholder,
            .product-card img { height: 140px !important; }
            .page-header-row {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .5rem;
            }
            .page-header-row .btn,
            .page-header-row a.btn {
                width: 100%;
                text-align: center;
            }
            .cart-item-row,
            .checkout-summary-item {
                flex-direction: column;
                align-items: flex-start !important;
            }
            .cart-item-row .text-end,
            .checkout-summary-item .text-end {
                width: 100%;
                text-align: left !important;
                margin-top: .5rem;
            }
            .cart-item-row .input-group {
                width: 100% !important;
                margin-top: .5rem;
            }
            .cart-item-row {
                padding: 1rem !important;
            }
            .cart-item-row > div:first-child {
                width: 56px;
                height: 56px;
            }
            .checkout-sticky {
                position: static !important;
                top: auto !important;
            }
            .checkout-sticky img.img-fluid { max-width: 180px !important; }
            .form-cetak-wrapper .d-flex.gap-4 { flex-direction: column; gap: .75rem !important; }
            .status-wrapper .input-group { flex-direction: column; }
            .status-wrapper .input-group .form-control,
            .status-wrapper .input-group .btn { border-radius: 10px !important; width: 100%; }
            .status-wrapper .input-group .input-group-text { display: none; }
            footer { text-align: center; }
            footer p { font-size: .92rem; }
        }

        @media (max-width: 575.98px) {
            .page-shell { padding-top: 74px; }
            .alert-wrapper .alert { padding: .9rem; }
            .container { padding-left: 12px !important; padding-right: 12px !important; }
            .card-body.px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
            .card-header.px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
            h2 { font-size: 1.4rem !important; }
            .fs-5 { font-size: 1rem !important; }
            .fs-3 { font-size: 1.3rem !important; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--warna-gelap); position: fixed; top: 0; width: 100%; z-index: 1030;">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/brand/rd-logo.svg') }}" alt="RD Logo" class="brand-logo-icon">
                Rama<span>Digital</span> Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                    aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog.index') ? 'active fw-bold text-info' : '' }}" href="{{ route('katalog.index') }}">Katalog ATK</a>
                    </li>
                    
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#jasa-cetak">Jasa Cetak</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cek-status">Cek Status</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('customer.orders') }}">
                            <i class="bi bi-receipt me-1"></i> Pesanan Saya
                            @php $paymentActions = $orderPaymentActionCount ?? 0; @endphp
                            @if($paymentActions > 0)
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">{{ $paymentActions }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.view') }}">
                            <i class="bi bi-cart3 me-1"></i> Keranjang
                            @php $count = $cartCount ?? 0; @endphp
                            @if($count > 0)
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">{{ $count }}</span>
                            @endif
                        </a>
                    </li>
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

    <div class="page-shell">
        @if(session('success'))
            <div class="container mt-3 alert-wrapper">
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm"
                     role="alert"
                     style="border-radius: 12px; border: none; border-left: 5px solid #198754; background: #f0fdf4;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                         style="width: 40px; height: 40px; background: #dcfce7;">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-success" style="font-size: 0.95rem;">Berhasil!</div>
                        <div class="text-dark" style="font-size: 0.9rem;">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mt-3 alert-wrapper">
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm"
                     role="alert"
                     style="border-radius: 12px; border: none; border-left: 5px solid #dc3545; background: #fff5f5;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                         style="width: 40px; height: 40px; background: #fee2e2;">
                        <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-danger" style="font-size: 0.95rem;">Terjadi Kesalahan</div>
                        <div class="text-dark" style="font-size: 0.9rem;">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="container mt-3 alert-wrapper">
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-3 shadow-sm"
                     role="alert"
                     style="border-radius: 12px; border: none; border-left: 5px solid #0ea5e9; background: #f0f9ff;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                         style="width: 40px; height: 40px; background: #e0f2fe;">
                        <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-info" style="font-size: 0.95rem;">Informasi</div>
                        <div class="text-dark" style="font-size: 0.9rem;">{{ session('info') }}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    {{-- Auto-dismiss alert dan hapus wrapper-nya agar tidak ada sisa ruang putih --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.alert.alert-dismissible').forEach(function (alertEl) {
                // Auto-dismiss setelah 4 detik
                setTimeout(function () {
                    bootstrap.Alert.getOrCreateInstance(alertEl).close();
                }, 4000);

                // Hapus container wrapper setelah animasi dismiss selesai
                alertEl.addEventListener('closed.bs.alert', function () {
                    const wrapper = alertEl.closest('.alert-wrapper');
                    if (wrapper) wrapper.remove();
                });
            });
        });
    </script>
</html>
