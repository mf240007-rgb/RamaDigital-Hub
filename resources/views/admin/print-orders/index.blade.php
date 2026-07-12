@extends('layouts.admin')

@section('content')

    <style>
        .print-orders-table {
            min-width: 980px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .print-orders-table th,
        .print-orders-table td {
            padding: 0.95rem 0.8rem;
            vertical-align: middle;
            white-space: normal;
            line-height: 1.45;
        }

        .print-orders-table thead th {
            white-space: nowrap;
            padding-top: 1rem;
            padding-bottom: 1rem;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.76rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        .print-orders-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s ease;
        }

        .print-orders-table tbody tr:nth-child(even) {
            background-color: #fcfdff;
        }

        .print-orders-table tbody tr:hover {
            background-color: #f8fbff;
        }

        .print-orders-table th:first-child,
        .print-orders-table td:first-child {
            padding-left: 1.15rem;
        }

        .print-orders-table th:last-child,
        .print-orders-table td:last-child {
            padding-right: 1.15rem;
        }

        .print-orders-table .detail-cell {
            min-width: 260px;
        }

        .print-orders-table .detail-stack {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .print-orders-table .detail-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.6rem;
            border-radius: 0.375rem;
            font-size: 0.78rem;
            font-weight: 600;
            background: #f8fafc;
            color: #475569;
            border: none;
            width: fit-content;
        }

        .print-orders-table .detail-pill-accent {
            background: #fff7ed;
            color: #9a2c00;
        }

        .print-orders-table .customer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
            color: #fff;
            flex-shrink: 0;
            letter-spacing: 0.02em;
        }

        .print-orders-table .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.34rem 0.7rem;
            font-size: 0.76rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .print-orders-table .status-info {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .print-orders-table .status-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .print-orders-table .status-success {
            background: #dcfce7;
            color: #15803d;
        }

        .print-orders-table .status-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .print-orders-table .status-muted {
            background: #f1f5f9;
            color: #475569;
        }

        .print-orders-table .doc-btn {
            min-width: 112px;
            justify-content: center;
            padding: 0.42rem 0.7rem;
            font-size: 0.78rem;
            border-radius: 999px;
            white-space: nowrap;
        }

        .print-orders-table .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid rgba(226, 232, 240, 0.95) !important;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16) !important;
            z-index: 1055;
        }

        .print-orders-table .dropdown {
            position: relative;
        }

        .table-responsive {
            overflow: visible;
        }

        .print-orders-table .dropdown-item {
            padding: 0.7rem 0.9rem;
            border-radius: 0.5rem;
            margin: 0.15rem 0.35rem;
            transition: background-color 0.18s ease, color 0.18s ease;
        }

        .print-orders-table .dropdown-item:hover,
        .print-orders-table .dropdown-item:focus {
            background-color: #f3f4f6;
            color: #111827;
        }

        .print-orders-table .meta-text {
            font-size: 0.8rem;
            color: #64748b;
        }

        .print-orders-table .muted-note {
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.3;
        }

        .print-orders-table .cell-tight {
            white-space: nowrap;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Pesanan Cetak</h2>
            <small class="text-muted">Daftar semua pesanan jasa cetak dokumen dari pelanggan</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
                 style="background: var(--warna-gelap); color: white; min-height: 44px;">
                <i class="bi bi-printer-fill text-warning"></i>
                <span class="fw-semibold">{{ $counts['menunggu'] }} Menunggu</span>
            </div>
            {{-- Tombol Hapus Filter (buka modal) --}}
            <button type="button"
                    class="btn btn-outline-danger rounded-3 px-3 py-2 d-flex align-items-center gap-2 align-self-stretch"
                    data-bs-toggle="modal" data-bs-target="#modalHapusFilter"
                    title="Hapus pesanan berdasarkan filter">
                <i class="bi bi-funnel-fill"></i>
                <span class="fw-semibold" style="font-size: 0.9rem;">Hapus via Filter</span>
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
            <i class="bi bi-check-circle-fill fs-5 text-success flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #dc3545;">
            <i class="bi bi-exclamation-circle-fill fs-5 text-danger flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tab Filter + Search (sama pola dengan Verifikasi ATK) --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('admin.print-orders.index', ['filter' => 'Menunggu Antrean']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'Menunggu Antrean' ? 'btn-warning fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-hourglass-split me-1"></i>Menunggu
            @if($counts['menunggu'] > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $counts['menunggu'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.print-orders.index', ['filter' => 'diproses']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'diproses' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-gear-fill me-1"></i>Diproses
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['diproses'] }}</span>
        </a>
        <a href="{{ route('admin.print-orders.index', ['filter' => 'menunggu_persetujuan_batal']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'menunggu_persetujuan_batal' ? 'btn-pink fw-bold' : 'btn-outline-secondary' }}"
           style="{{ $filter === 'menunggu_persetujuan_batal' ? 'background:#9d174d;color:white;border-color:#9d174d;' : '' }}">
            <i class="bi bi-clock-history me-1"></i>Permintaan Batal
            @if($counts['minta_batal'] > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $counts['minta_batal'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.print-orders.index', ['filter' => 'selesai']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'selesai' ? 'btn-success fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-check-circle me-1"></i>Selesai
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['selesai'] }}</span>
        </a>
        <a href="{{ route('admin.print-orders.index', ['filter' => 'ditolak']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'ditolak' ? 'btn-danger fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-x-circle me-1"></i>Ditolak
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['ditolak'] }}</span>
        </a>
        <a href="{{ route('admin.print-orders.index', ['filter' => 'semua']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'semua' ? 'btn-dark fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-list-ul me-1"></i>Semua
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['semua'] }}</span>
        </a>

        <form method="GET" action="{{ route('admin.print-orders.index') }}"
              class="ms-auto d-flex gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
                        <label for="searchPrintOrders" class="visually-hidden">Cari pelanggan atau detail pesanan</label>
                        <input type="text" id="searchPrintOrders" name="search" class="form-control rounded-pill"
                   style="width:240px;font-size:0.88rem;"
                   placeholder="Cari pelanggan atau detail..."
                   value="{{ $keyword }}">
            <button type="submit" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- Toolbar Hapus Massal (muncul saat ada yang dicentang) --}}
    <div id="toolbarHapusMassal"
         class="d-none mb-3 px-4 py-2 rounded-3 d-flex align-items-center gap-3"
         style="background: #fff5f5; border: 1px solid #fecaca;">
        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
        <span class="fw-semibold text-danger" id="labelJumlahDipilih">0 pesanan dipilih</span>
        <button type="button"
                class="btn btn-danger btn-sm rounded-pill px-3 ms-auto"
                onclick="konfirmasiHapusMassal()">
            <i class="bi bi-trash3-fill me-1"></i>Hapus yang Dipilih
        </button>
        <button type="button"
                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                onclick="batalPilihan()">
            Batal
        </button>
    </div>

    <form id="formHapusMassal" action="{{ route('admin.print-orders.bulk-delete') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="bulk_mode" value="selected">
        <div id="bulkOrderIdsContainer"></div>
    </form>

    {{-- Tabel Pesanan --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                <i class="bi bi-printer me-2 text-primary"></i>Daftar Pesanan Jasa Cetak
                <span class="text-muted fw-normal">
                    @if($filter === 'Menunggu Antrean') — Menunggu Konfirmasi
                    @elseif($filter === 'diproses') — Sedang Diproses
                    @elseif($filter === 'menunggu_persetujuan_batal') — Permintaan Batal
                    @elseif($filter === 'selesai') — Pesanan Selesai
                    @elseif($filter === 'ditolak') — Bukti Pembayaran Ditolak
                    @else — Semua Status
                    @endif
                </span>
            </h6>
            <span class="badge rounded-pill px-3 py-2"
                  style="background: linear-gradient(135deg, #1a73e8, #4a9eff); font-size: 0.75rem;">
                <i class="bi bi-broadcast me-1"></i>Live Data
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive px-2 px-lg-3 py-2">
                <table class="table table-hover align-middle mb-0 print-orders-table">
                    <thead style="background: #f8faff;">
                        <tr style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d;">
                            <th class="ps-3 py-3 align-middle" style="width: 36px;">
                                <input type="checkbox" class="form-check-input" id="checkAll"
                                       title="Pilih semua" onchange="toggleCheckAll(this)">
                            </th>
                            <th class="fw-semibold py-3 align-middle" style="width: 40px;">No</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 150px;">No. Pesanan</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 170px; min-width: 150px;">Pelanggan</th>
                            <th class="fw-semibold py-3 align-middle">Detail Pesanan</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 110px; min-width: 100px;">Tgl. Masuk</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 120px; min-width: 110px;">Dokumen</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 140px; min-width: 125px;">DP / Bukti</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 150px; min-width: 130px;">Ubah Status</th>
                            <th class="fw-semibold py-3 align-middle text-center pe-3" style="width: 70px; min-width: 60px;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        @php
                            $nama    = $order->user->full_name ?? $order->user->name ?? 'Pelanggan';
                            $inisial = collect(explode(' ', $nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $colors  = ['#1a73e8','#10b981','#8b5cf6','#f59e0b','#ef4444','#06b6d4','#ec4899'];
                            $bgColor = $colors[$index % count($colors)];
                            $isAwaitingRemainingPayment = $order->status === 'selesai'
                                && $order->payment_status === 'dp_diterima'
                                && $order->getRemainingBalance() > 0;
                            $isWaitingRemainingVerification = $order->status === 'selesai'
                                && $order->payment_status === 'sisa_dibayar';

                            $statusStyle = match($order->status) {
                                'Menunggu Antrean' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'bi-clock', 'label' => 'Menunggu Konfirmasi'],
                                'diproses'         => ['bg' => '#fff3cd', 'text' => '#856404', 'icon' => 'bi-gear-fill'],
                                'selesai'          => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'bi-check-circle-fill'],
                                'dibatalkan'       => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'bi-x-circle-fill'],
                                default            => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'bi-dash'],
                            };
                            $dpBadge = match($order->payment_status ?? '') {
                                'lunas' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Lunas'],
                                'dp_diterima' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'DP Diterima'],
                                'sisa_dibayar' => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Sisa Menunggu'],
                                'menunggu_konfirmasi' => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Menunggu'],
                                'ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Ditolak'],
                                default => ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => 'Belum'],
                            };
                        @endphp
                        <tr class="order-row" data-id="{{ $order->id }}">
                            {{-- Checkbox --}}
                            <td class="ps-3">
                                <input type="checkbox"
                                       class="form-check-input order-checkbox"
                                       data-order-id="{{ $order->id }}"
                                       onchange="updateToolbar()">
                            </td>

                            {{-- No --}}
                            <td class="text-muted text-center" style="font-size: 0.88rem;">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
                            </td>

                            {{-- No. Pesanan --}}
                            <td>
                                @if($order->order_number)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold"
                                          style="background:#f0f4ff;color:#1a73e8;font-size:0.75rem;letter-spacing:0.3px;font-family:monospace;white-space:nowrap;">
                                        {{ $order->order_number }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Pelanggan --}}
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="customer-avatar" style="background:{{ $bgColor }};">
                                        {{ $inisial }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate" style="font-size:0.86rem;color:var(--warna-gelap);max-width:140px;">{{ $nama }}</div>
                                        <div class="meta-text mt-1">{{ $order->user->whatsapp ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Detail --}}
                            <td class="detail-cell" style="font-size:0.85rem;">
                                @php
                                    // ── Ringkasan satu baris ──────────────────────────────
                                    $labelKertasPendek = match($order->jenis_kertas ?? '') {
                                        'hvs_a4'      => 'HVS A4',
                                        'hvs_f4'      => 'HVS F4',
                                        'foto_glossy' => 'Foto Glossy',
                                        'foto_matte'  => 'Foto Matte',
                                        default       => $order->jenis_kertas ?? '—',
                                    };
                                    $labelMode = match($order->mode_cetak ?? '') {
                                        'hitam_putih' => 'H&P',
                                        'full_color'  => 'Full Color',
                                        default       => null,
                                    };
                                    // ── Konten popover (HTML di-escape) ──────────────────
                                    $popLines = [];
                                    if ($order->jenis_kertas)    $popLines[] = '<b>Kertas:</b> ' . $labelKertasPendek;
                                    if ($order->jumlah_cetak)    $popLines[] = '<b>Cetak:</b> ' . $order->jumlah_cetak . '×';
                                    if ($order->mode_cetak)      $popLines[] = '<b>Mode:</b> ' . ($order->mode_cetak === 'hitam_putih' ? 'Hitam & Putih' : 'Full Color');
                                    if ($order->intensitas_warna) $popLines[] = '<b>Warna:</b> ' . ($order->intensitas_warna === 'sedikit_warna' ? 'Sedikit' : 'Banyak');
                                    if ($order->estimasi_harga > 0) $popLines[] = '<b>Estimasi:</b> Rp ' . number_format($order->estimasi_harga, 0, ',', '.');
                                    if ($order->catatan)         $popLines[] = '<b>Catatan:</b> ' . e($order->catatan);
                                    $popContent = implode('<br>', $popLines) ?: 'Tidak ada detail tambahan.';
                                @endphp

                                {{-- Ringkasan utama --}}
                                <div class="detail-stack">
                                    <div class="d-flex align-items-center gap-1 fw-semibold text-dark">
                                        <span>{{ $labelKertasPendek }}{{ $labelMode ? ' · ' . $labelMode : '' }}</span>
                                        @if(!empty($popLines))
                                            <button type="button"
                                                    class="btn btn-link p-0 border-0 text-muted detail-popover"
                                                    data-bs-toggle="popover"
                                                    data-bs-trigger="click"
                                                    data-bs-placement="right"
                                                    data-bs-html="true"
                                                    data-bs-title="Detail Pesanan"
                                                    data-bs-content="{{ $popContent }}"
                                                    style="font-size:0.9rem;line-height:1;"
                                                    title="Lihat detail">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Estimasi harga — satu-satunya info tambahan yang selalu tampil --}}
                                    @if($order->estimasi_harga > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="detail-pill">
                                                <i class="bi bi-cash-stack"></i>
                                                Estimasi: Rp {{ number_format($order->estimasi_harga, 0, ',', '.') }}
                                            </span>
                                            <span class="detail-pill detail-pill-accent">
                                                <i class="bi bi-credit-card-2-front"></i>
                                                DP: Rp {{ number_format($order->getDpAmount(), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="text-muted cell-tight text-center" style="font-size:0.82rem;">
                                <div class="fw-semibold text-dark">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="muted-note mt-1">{{ $order->created_at->format('H:i') }} WIB</div>
                            </td>

                            {{-- Dokumen --}}
                            <td class="text-center align-middle">
                                @php $files = $order->getDokumenFiles(); @endphp
                                @if(empty($files))
                                    <span class="text-muted" style="font-size:0.82rem;">—</span>
                                @elseif(count($files) === 1)
                                    <a href="{{ route('admin.print-orders.download', ['id' => $order->id, 'fileIndex' => 0]) }}"
                                       class="btn btn-primary btn-sm rounded-pill doc-btn"
                                       title="Download dokumen">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm rounded-pill doc-btn dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="bi bi-download me-1"></i>{{ count($files) }} Dokumen
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-2" style="min-width:240px;font-size:0.82rem;border-radius:12px;">
                                            <li class="px-2 pb-2 pt-1">
                                                <div class="small fw-semibold text-muted">
                                                    {{ count($files) }} file tersedia
                                                </div>
                                            </li>
                                            @foreach($files as $idx => $fileName)
                                                @php
                                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                    $typeLabel = match($extension) {
                                                        'pdf' => 'PDF',
                                                        'doc', 'docx' => 'Word',
                                                        'xls', 'xlsx' => 'Excel',
                                                        'jpg', 'jpeg', 'png' => 'Gambar',
                                                        default => strtoupper($extension ?: 'File')
                                                    };
                                                    $displayName = preg_replace('/^\d+_\d+_[a-f0-9]+_/i', '', $fileName);
                                                @endphp
                                                <li>
                                                    <a class="dropdown-item rounded-2 py-2 px-2 d-flex align-items-center justify-content-between gap-2"
                                                       href="{{ route('admin.print-orders.download', ['id' => $order->id, 'fileIndex' => $idx]) }}">
                                                        <div class="text-start">
                                                            <div class="fw-semibold text-truncate" style="max-width:145px;" title="{{ $displayName }}">{{ $displayName }}</div>
                                                        </div>
                                                        <span class="badge rounded-pill bg-light text-dark fw-normal flex-shrink-0">{{ $typeLabel }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>

                            {{-- DP / Bukti --}}
                            <td class="text-center align-middle" style="min-width: 150px;">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold" style="background:{{ $dpBadge['bg'] }};color:{{ $dpBadge['text'] }};font-size:0.72rem;">
                                        {{ $dpBadge['label'] }}
                                    </span>
                                    @if($order->bukti_bayar)
                                        <a href="{{ route('admin.print-orders.download-bukti', $order->id) }}"
                                           target="_blank"
                                           class="btn btn-outline-primary btn-sm rounded-pill px-2"
                                           style="font-size:0.72rem;white-space:nowrap;">
                                            <i class="bi bi-eye me-1"></i>Lihat Bukti
                                        </a>
                                    @else
                                        <span class="muted-note">Belum ada bukti</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Ubah Status --}}
                            <td class="text-center align-middle" style="min-width: 160px;">
                                @if($order->status === 'selesai' && $order->payment_status === 'lunas')
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="status-pill status-success">
                                            <i class="bi bi-check-circle-fill"></i>Selesai
                                        </span>
                                        <span class="muted-note">Tidak ada aksi lagi</span>
                                    </div>
                                @elseif($order->status === 'dibatalkan')
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="status-pill status-danger">
                                            <i class="bi bi-x-circle-fill"></i>Dibatalkan
                                        </span>
                                        <span class="muted-note">oleh {{ ucfirst($order->dibatalkan_oleh ?? '—') }}</span>
                                    </div>
                                @else
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <span class="status-pill {{ ($order->status === 'diproses' || $isAwaitingRemainingPayment || $isWaitingRemainingVerification) ? 'status-warning' : 'status-info' }}">
                                            <i class="bi {{ ($order->status === 'diproses' || $isAwaitingRemainingPayment || $isWaitingRemainingVerification) ? 'bi-gear-fill' : 'bi-clock-fill' }}"></i>
                                            @if($isWaitingRemainingVerification)
                                                Verifikasi Sisa
                                            @elseif($isAwaitingRemainingPayment)
                                                Menunggu Sisa
                                            @else
                                                {{ $order->status === 'diproses' ? 'Diproses' : 'Menunggu' }}
                                            @endif
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-2 py-1 d-inline-flex align-items-center gap-1"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    style="font-size:0.75rem;">
                                                <i class="bi bi-three-dots"></i>
                                                <span class="d-none d-md-inline">Aksi</span>
                                            </button>
                                            <ul class="dropdown-menu shadow-lg border-0 rounded-3 py-2 z-50" style="min-width:220px; background-color:#ffffff !important;">
                                                <li class="px-3 pb-2 small fw-semibold text-muted">Aksi pesanan</li>
                                                {{-- Aksi khusus permintaan batal dari pelanggan --}}
                                                @if($order->payment_status === 'menunggu_persetujuan_batal')
                                                    @if($order->cancellation_reason)
                                                        <li>
                                                            <div class="px-3 py-2" style="font-size:0.78rem;background:#fce7f3;border-radius:6px;margin:0 6px 4px;">
                                                                <i class="bi bi-chat-left-text me-1 text-danger"></i>
                                                                <strong>Alasan:</strong> {{ Str::limit($order->cancellation_reason, 60) }}
                                                            </div>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <button type="button" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-success"
                                                                onclick="bukaModalSetujuiBatalCetak({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                                            <i class="bi bi-check2-circle text-success"></i>Setujui Pembatalan
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2"
                                                                onclick="bukaModalTolakBatalCetak({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                                            <i class="bi bi-arrow-counterclockwise text-warning"></i>Tolak Permintaan Batal
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                @endif
                                                @if($order->bukti_bayar && !in_array($order->payment_status, ['lunas', 'dp_diterima', 'sisa_dibayar'], true))
                                                    <li>
                                                        <form action="{{ route('admin.print-orders.konfirmasi-bayar', $order->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <input type="hidden" name="payment_mode" value="dp">
                                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2">
                                                                <i class="bi bi-check2-circle text-success"></i>Konfirmasi DP
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($order->payment_status === 'sisa_dibayar' && $order->bukti_bayar)
                                                    <li>
                                                        <form action="{{ route('admin.print-orders.konfirmasi-bayar', $order->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            <input type="hidden" name="payment_mode" value="sisa">
                                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2">
                                                                <i class="bi bi-wallet2 text-primary"></i>Konfirmasi Sisa
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($order->bukti_bayar && !in_array($order->payment_status, ['lunas', 'dp_diterima'], true))
                                                    <li>
                                                        <button type="button" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2" onclick="tolakPembayaran({{ $order->id }})">
                                                            <i class="bi bi-x-circle text-danger"></i>Tolak Bukti
                                                        </button>
                                                    </li>
                                                @endif
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <form action="{{ route('admin.print-orders.status', $order->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <input type="hidden" name="status" value="Menunggu Antrean">
                                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2">
                                                            <i class="bi bi-clock-history"></i>Set Menunggu
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.print-orders.status', $order->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <input type="hidden" name="status" value="diproses">
                                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2">
                                                            <i class="bi bi-gear"></i>Set Diproses
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item d-flex align-items-center gap-2 px-3 py-2"
                                                            onclick="bukaModalSelesaikan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}', '{{ $order->total_harga > 0 ? $order->total_harga : ($order->estimasi_harga > 0 ? $order->estimasi_harga : '') }}')">
                                                        <i class="bi bi-check2-circle text-success"></i>Set Selesai
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-warning" onclick="bukaModalBatalAdmin({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                                        <i class="bi bi-x-lg"></i>Batalkan Pesanan
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Hapus Satu --}}
                            <td class="text-center pe-3">
                                @if($order->status === 'selesai' && $order->payment_status === 'lunas')
                                    <span class="muted-note">—</span>
                                @else
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                            style="width:34px;height:34px;"
                                            title="Hapus pesanan ini"
                                            onclick="hapusSatuPesanan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                        <i class="bi bi-trash3" style="font-size:0.8rem;"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="bi bi-printer fs-1 d-block mb-2 opacity-25"></i>
                                @if($keyword || $filter !== 'Menunggu Antrean')
                                    Tidak ada pesanan yang cocok dengan filter.
                                    <br><a href="{{ route('admin.print-orders.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Reset Filter</a>
                                @else
                                    Belum ada pesanan jasa cetak masuk.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="card-footer bg-white px-4 py-3" style="border-radius: 0 0 16px 16px; border-top: 1px solid #f0f0f0;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- Form hapus satu pesanan (di luar formHapusMassal agar tidak nested) --}}
    <form id="formHapusSatu" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- =============================================
         MODAL: Tolak Bukti Pembayaran
         ============================================= --}}
    <div class="modal fade" id="modalTolakPembayaran" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #fef2f2;">
                    <div>
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>Tolak Bukti Pembayaran
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Masukkan alasan penolakan bukti pembayaran agar pelanggan memahami ketidakvalidan bukti tersebut.</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTolakPembayaran" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <label class="form-label fw-semibold mb-1">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_pembatalan"
                                  class="form-control"
                                  rows="4"
                                  maxlength="500"
                                  placeholder="Contoh: bukti pembayaran tidak jelas, nominal tidak sesuai, atau bukti bukan milik pesanan ini."
                                  required></textarea>
                        <div class="form-text">Alasan ini akan dicatat pada catatan pembayaran pesanan.</div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Tolak Bukti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =============================================
         MODAL: Batalkan Pesanan (Admin)
         ============================================= --}}
    <div class="modal fade" id="modalBatalAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #fffbeb;">
                    <div>
                        <h5 class="fw-bold mb-1 text-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Pesanan
                        </h5>
                        <p class="text-muted mb-0" id="labelNomorPesananBatal" style="font-size: 0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formBatalAdmin" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <label class="form-label fw-semibold mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="alasan_pembatalan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Contoh: File dokumen tidak dapat diproses, stok kertas habis, dll."
                                  maxlength="500"
                                  required></textarea>
                        <div class="form-text">Alasan ini akan terlihat oleh pelanggan saat mengecek status pesanan.</div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-warning rounded-pill flex-fill fw-semibold text-dark">
                            <i class="bi bi-x-lg me-1"></i>Konfirmasi Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =============================================
         MODAL: Hapus via Filter
         ============================================= --}}
    <div class="modal fade" id="modalHapusFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">

                <div class="modal-header border-0 px-4 pt-4 pb-2"
                     style="background: #fff5f5;">
                    <div>
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-funnel-fill me-2"></i>Hapus Pesanan via Filter
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                            Hapus banyak pesanan sekaligus berdasarkan status dan umur pesanan.
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.print-orders.bulk-delete') }}" method="POST"
                      onsubmit="return konfirmasiHapusFilter(this)">
                    @csrf
                    <input type="hidden" name="bulk_mode" value="filter">

                    <div class="modal-body px-4 py-3">

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Status Pesanan</label>
                            <select name="filter_status" class="form-select" required>
                                <option value="selesai" selected>Selesai</option>
                                <option value="Menunggu Antrean">Menunggu Antrean</option>
                                <option value="diproses">Diproses</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="semua">Semua Status</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Lebih dari</label>
                            <select name="filter_older" class="form-select" required>
                                <option value="30" selected>30 hari yang lalu</option>
                                <option value="7">7 hari yang lalu</option>
                                <option value="14">14 hari yang lalu</option>
                                <option value="60">60 hari yang lalu</option>
                                <option value="90">90 hari yang lalu</option>
                            </select>
                        </div>

                        <div class="alert alert-warning border-0 py-2 px-3 mb-0"
                             style="border-radius: 10px; background: #fef9c3; font-size: 0.83rem;">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Pesanan yang dihapus <strong>tidak dapat dikembalikan</strong>. File dokumen juga akan ikut terhapus.
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-trash3-fill me-1"></i>Hapus Sekarang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- Modal: Harga Final saat pekerjaan selesai dikerjakan --}}
    <div class="modal fade" id="modalHargaFinal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #ecfdf5;">
                    <div>
                        <h5 class="fw-bold mb-1 text-success">
                            <i class="bi bi-check2-circle me-2"></i>Selesai Dikerjakan
                        </h5>
                        <p class="text-muted mb-0" id="labelNomorPesananFinal" style="font-size: 0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formHargaFinal" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="selesai">
                    <div class="modal-body px-4 py-3">
                        <label class="form-label fw-semibold mb-1">Harga Final <span class="text-danger">*</span></label>
                        <input type="number"
                               name="harga_final"
                               id="inputHargaFinal"
                               class="form-control"
                               min="1"
                               required>
                        <div class="form-text">Masukkan harga akhir jasa cetak. Pelanggan akan diminta melunasi sisa pembayaran sebelum nota tersedia.</div>

                        <label class="form-label fw-semibold mt-3 mb-1">Catatan Admin (Opsional)</label>
                        <textarea name="catatan_admin"
                                  id="inputCatatanAdmin"
                                  class="form-control"
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Contoh: ada revisi warna, ukuran khusus, atau tambahan finishing."></textarea>
                        <div class="form-text">Catatan ini akan muncul di nota jika diisi.</div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i>Simpan & Tagih Sisa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function handleStatusChange(selectEl) {
            const selectedValue = selectEl.value;
            const previousValue = selectEl.dataset.previousValue || selectEl.value;

            if (selectedValue !== 'selesai') {
                selectEl.dataset.previousValue = selectedValue;
                selectEl.closest('form').submit();
                return;
            }

            selectEl.value = previousValue;
            selectEl.dataset.previousValue = previousValue;

            bukaModalSelesaikan(selectEl.dataset.orderId, selectEl.dataset.orderNumber, selectEl.dataset.defaultPrice || '');
        }

        function bukaModalSelesaikan(orderId, orderNumber, defaultPrice) {
            const modal = document.getElementById('modalHargaFinal');
            const form = document.getElementById('formHargaFinal');
            const label = document.getElementById('labelNomorPesananFinal');
            const input = document.getElementById('inputHargaFinal');

            form.action = '/{{ config('admin.path') }}/pesanan-cetak/' + orderId + '/status';
            label.textContent = 'Pesanan: ' + orderNumber;
            input.value = defaultPrice;
            input.focus();

            new bootstrap.Modal(modal).show();
        }

        // ── Checkbox: Pilih Semua ──────────────────────────────
        function toggleCheckAll(master) {
            document.querySelectorAll('.order-checkbox').forEach(cb => {
                cb.checked = master.checked;
            });
            updateToolbar();
        }

        // ── Update toolbar saat ada perubahan centang ──────────
        function updateToolbar() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            const toolbar = document.getElementById('toolbarHapusMassal');
            const label   = document.getElementById('labelJumlahDipilih');
            const container = document.getElementById('bulkOrderIdsContainer');

            if (container) {
                container.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'order_ids[]';
                    input.value = cb.getAttribute('data-order-id');
                    container.appendChild(input);
                });
            }

            if (checked.length > 0) {
                toolbar.classList.remove('d-none');
                toolbar.classList.add('d-flex');
                label.textContent = checked.length + ' pesanan dipilih';
            } else {
                toolbar.classList.add('d-none');
                toolbar.classList.remove('d-flex');
            }

            // Sync state checkAll
            const all  = document.querySelectorAll('.order-checkbox');
            const master = document.getElementById('checkAll');
            if (master) {
                master.indeterminate = checked.length > 0 && checked.length < all.length;
                master.checked = checked.length === all.length && all.length > 0;
            }
        }

        // ── Konfirmasi hapus massal (checkbox) ────────────────
        function konfirmasiHapusMassal() {
            const jumlah = document.querySelectorAll('.order-checkbox:checked').length;
            if (jumlah === 0) {
                alert('Pilih minimal satu pesanan.');
                return;
            }
            if (confirm(jumlah + ' pesanan akan dihapus permanen beserta file dokumennya. Lanjutkan?')) {
                document.getElementById('formHapusMassal').submit();
            }
        }

        function tolakPembayaran(orderId) {
            const modal = document.getElementById('modalTolakPembayaran');
            const form = document.getElementById('formTolakPembayaran');
            const textarea = form.querySelector('textarea[name="alasan_pembatalan"]');

            form.action = '/{{ config('admin.path') }}/pesanan-cetak/' + orderId + '/tolak-bayar';
            textarea.value = '';
            textarea.focus();

            new bootstrap.Modal(modal).show();
        }

        // ── Batal pilihan ─────────────────────────────────────
        function batalPilihan() {
            document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = false);
            const master = document.getElementById('checkAll');
            if (master) { master.checked = false; master.indeterminate = false; }
            updateToolbar();
        }

        // ── Konfirmasi hapus filter ───────────────────────────
        function konfirmasiHapusFilter(form) {
            const status = form.querySelector('[name="filter_status"]').options[form.querySelector('[name="filter_status"]').selectedIndex].text;
            const older  = form.querySelector('[name="filter_older"]').options[form.querySelector('[name="filter_older"]').selectedIndex].text;
            return confirm('Hapus semua pesanan "' + status + '" yang lebih tua dari ' + older + '?\n\nTindakan ini tidak dapat dibatalkan.');
        }

        // ── Hapus Satu Pesanan (via form terpisah, hindari nested form) ──
        function hapusSatuPesanan(orderId, orderNumber) {
            if (!confirm('Hapus pesanan ' + orderNumber + '? Tindakan ini tidak dapat dibatalkan.')) return;
            const form = document.getElementById('formHapusSatu');
            form.action = '/{{ config('admin.path') }}/pesanan-cetak/' + orderId;
            form.submit();
        }

        // ── Modal Batalkan (Admin) ────────────────────────────
        function bukaModalBatalAdmin(orderId, orderNumber) {
            const modal = document.getElementById('modalBatalAdmin');
            const form  = document.getElementById('formBatalAdmin');
            const label = document.getElementById('labelNomorPesananBatal');
            form.action = '/{{ config('admin.path') }}/pesanan-cetak/' + orderId + '/cancel';
            label.textContent = 'Pesanan: ' + orderNumber;
            modal.querySelector('textarea[name="alasan_pembatalan"]').value = '';
            new bootstrap.Modal(modal).show();
        }

        // ── Modal Setujui Batal (dari pelanggan) ──────────────
        function bukaModalSetujuiBatalCetak(orderId, orderNumber) {
            document.getElementById('formSetujuiBatalCetak').action =
                '/{{ config('admin.path') }}/pesanan-cetak/' + orderId + '/setujui-batal';
            document.getElementById('labelSetujuiBatalCetak').textContent = 'Pesanan: ' + orderNumber;
            new bootstrap.Modal(document.getElementById('modalSetujuiBatalCetak')).show();
        }

        // ── Modal Tolak Permintaan Batal (dari pelanggan) ─────
        function bukaModalTolakBatalCetak(orderId, orderNumber) {
            document.getElementById('formTolakBatalCetak').action =
                '/{{ config('admin.path') }}/pesanan-cetak/' + orderId + '/tolak-batal';
            document.getElementById('labelTolakBatalCetak').textContent = 'Pesanan: ' + orderNumber;
            new bootstrap.Modal(document.getElementById('modalTolakBatalCetak')).show();
        }
    </script>

    {{-- Modal Setujui Batal Cetak --}}
    <div class="modal fade" id="modalSetujuiBatalCetak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background:#fce7f3;">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#9d174d;"><i class="bi bi-check-circle-fill me-2"></i>Setujui Pembatalan</h5>
                        <p class="text-muted mb-0" id="labelSetujuiBatalCetak" style="font-size:0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formSetujuiBatalCetak" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <div class="alert alert-warning py-2 px-3 mb-3" style="border-radius:10px;font-size:0.85rem;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Pesanan akan dibatalkan. Pastikan refund DP sudah dilakukan secara manual via transfer sebelum menyetujui.
                        </div>
                        <label class="form-label fw-semibold mb-1" style="font-size:0.88rem;">Catatan (Opsional)</label>
                        <input type="text" name="catatan" class="form-control form-control-sm"
                               placeholder="Misal: Refund DP sudah ditransfer ke pelanggan">
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn rounded-pill flex-fill fw-semibold text-white" style="background:#9d174d;">
                            <i class="bi bi-check2 me-1"></i>Setujui & Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Tolak Permintaan Batal Cetak --}}
    <div class="modal fade" id="modalTolakBatalCetak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background:#fffbeb;">
                    <div>
                        <h5 class="fw-bold mb-1 text-warning"><i class="bi bi-arrow-counterclockwise me-2"></i>Tolak Permintaan Batal</h5>
                        <p class="text-muted mb-0" id="labelTolakBatalCetak" style="font-size:0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTolakBatalCetak" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <p class="text-muted" style="font-size:0.85rem;">Status pembayaran pesanan akan dikembalikan ke <strong>Menunggu Konfirmasi</strong>.</p>
                        <label class="form-label fw-semibold mb-1" style="font-size:0.88rem;">Alasan (Opsional)</label>
                        <input type="text" name="catatan" class="form-control form-control-sm"
                               placeholder="Misal: Pembayaran sudah dikonfirmasi, tidak bisa dibatalkan">
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill flex-fill fw-semibold text-dark">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Tolak Permintaan Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ── Init tooltips & popovers ──────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            // Tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el, { placement: 'top' });
            });

            // Popovers (detail pesanan)
            document.querySelectorAll('.detail-popover').forEach(function (el) {
                new bootstrap.Popover(el, {
                    sanitize: false,   // izinkan HTML di content
                });
            });

            document.querySelectorAll('.status-form select').forEach(function (el) {
                el.dataset.previousValue = el.value;
            });

            // Tutup semua popover saat klik di luar
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.detail-popover') && !e.target.closest('.popover')) {
                    document.querySelectorAll('.detail-popover').forEach(function (el) {
                        const pop = bootstrap.Popover.getInstance(el);
                        if (pop) pop.hide();
                    });
                }
            });
        });
    </script>

@endsection
