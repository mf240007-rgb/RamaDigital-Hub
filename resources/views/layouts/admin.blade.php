<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — RamaDigital Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { --warna-gelap: #1c2b4a; --warna-utama: #1a73e8; --warna-aksen: #ff6d00; }
        body { background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, var(--warna-gelap) 0%, #2c3e6b 100%); color: white; padding: 0; }
        .sidebar-brand { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 1.1rem; font-weight: 700; }
        .sidebar-brand span { color: var(--warna-aksen); }
        .sidebar-nav .nav-link { color: rgba(255,255,255,0.75); padding: 12px 20px; border-radius: 0; transition: all 0.2s; font-size: 0.9rem; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { background: rgba(255,255,255,0.1); color: white; padding-left: 28px; }
        .main-content { padding: 30px; }
        .stat-card { border: none; border-radius: 14px; box-shadow: 0 3px 15px rgba(0,0,0,0.07); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="sidebar-brand">
                    <i class="bi bi-printer-fill me-2" style="color: var(--warna-aksen);"></i>
                    Rama<span>Digital</span> Hub
                </div>
                <ul class="nav flex-column sidebar-nav mt-2">
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.produk.*')) active @endif" href="{{ route('admin.produk.index') }}"><i class="bi bi-box-seam me-2"></i> Kelola Produk ATK</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.print-orders.*')) active @endif" href="{{ route('admin.print-orders.index') }}"><i class="bi bi-printer me-2"></i> Pesanan Cetak
                        @php
                            $newCetak = \App\Models\Order::where('item_type','jasa')->where('status','Menunggu Antrean')->count();
                        @endphp
                        @if($newCetak > 0)
                            <span class="badge bg-danger rounded-pill ms-1" style="font-size:0.7rem;">{{ $newCetak }}</span>
                        @endif
                    </a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('admin.customers.*')) active @endif" href="{{ route('admin.customers.index') }}"><i class="bi bi-people me-2"></i> Data Pelanggan</a></li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admin.verifikasi-atk.*')) active @endif"
                           href="{{ route('admin.verifikasi-atk.index') }}"
                           style="position:relative;">
                            <i class="bi bi-patch-check me-2"></i> Verifikasi Bayar ATK
                            @php
                                $pendingVerif = \App\Models\Order::where('item_type','produk')
                                    ->where('payment_status','menunggu_konfirmasi')->count();
                            @endphp
                            @if($pendingVerif > 0)
                                <span class="badge bg-danger rounded-pill ms-1" style="font-size:0.7rem;">{{ $pendingVerif }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item mt-3 border-top border-secondary pt-3"><a class="nav-link @if(request()->routeIs('admin.settings')) active @endif" href="{{ route('admin.settings') }}"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="{{ route('admin.logout') }}"><i class="bi bi-box-arrow-left me-2"></i> Keluar (Logout)</a></li>
                </ul>
            </div>

            <div class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</body>
</html>
