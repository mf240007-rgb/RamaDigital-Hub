@extends('layouts.admin')

@section('content')

    {{-- Top Bar --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold mb-1" style="color: var(--warna-gelap);">Dashboard Admin</h1>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar3 text-muted"></i>
                <span class="text-muted" id="realtime-date" style="font-size: 1rem;"></span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white; min-width: 160px;">
            <i class="bi bi-clock text-warning"></i>
            <span id="realtime-clock" class="fw-bold fs-5 font-monospace">--:--:--</span>
        </div>
    </div>

    {{-- Welcome Alert --}}
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm"
         role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
        <i class="bi bi-check-circle-fill flex-shrink-0 me-3 fs-3 text-success"></i>
        <div>
            <h5 class="alert-heading mb-1">Selamat Datang Admin!</h5>
            <p class="mb-0">Kamu berhasil masuk ke Panel Admin <strong>RamaDigital Hub</strong>.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    {{-- Notif pembayaran menunggu --}}
    @if($pendingPayment > 0)
    <div class="alert d-flex align-items-center gap-3 mb-4 shadow-sm"
         style="border-radius: 12px; border: none; border-left: 5px solid #f59e0b; background: #fffbeb;">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-4 flex-shrink-0"></i>
        <div class="flex-grow-1">
            <strong>{{ $pendingPayment }} pembayaran</strong> menunggu konfirmasi dari pelanggan.
        </div>
        <a href="{{ route('admin.print-orders.index') }}" class="btn btn-sm btn-warning rounded-pill px-3 flex-shrink-0">
            Tinjau
        </a>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.produk.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 h-100"
                     style="border-radius:16px; background:linear-gradient(135deg,#1a73e8,#4a9eff); color:white;">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;flex-shrink:0;">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div>
                            <div class="small opacity-75">Total Produk ATK</div>
                            <div class="fw-bold fs-3 lh-1 mt-1">{{ $totalProduk }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.print-orders.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 h-100"
                     style="border-radius:16px; background:linear-gradient(135deg,#f59e0b,#fbbf24); color:white;">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;flex-shrink:0;">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div>
                            <div class="small opacity-75">Pesanan Aktif</div>
                            <div class="fw-bold fs-3 lh-1 mt-1">{{ $pesananMasuk }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card border-0 h-100"
                 style="border-radius:16px; background:linear-gradient(135deg,#10b981,#34d399); color:white;">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;flex-shrink:0;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pesanan Selesai</div>
                        <div class="fw-bold fs-3 lh-1 mt-1">{{ $pesananSelesai }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
                <div class="card stat-card border-0 h-100"
                     style="border-radius:16px; background:linear-gradient(135deg,#ef4444,#f87171); color:white;">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;flex-shrink:0;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <div class="small opacity-75">Pendapatan Bulan Ini</div>
                            <div class="fw-bold fs-4 lh-1 mt-1">
                                Rp {{ number_format($pendapatanBulan / 1000000, 1) }} Jt
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Tabel Pesanan Cetak Terbaru --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
                     style="border-radius:16px 16px 0 0; border-bottom:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                        <i class="bi bi-printer me-2 text-primary"></i>Pesanan Cetak Terbaru
                    </h6>
                    <a href="{{ route('admin.print-orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#f8faff;">
                                <tr style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;">
                                    <th class="ps-4 py-3 fw-semibold">No. Pesanan</th>
                                    <th class="fw-semibold">Pelanggan</th>
                                    <th class="fw-semibold">Status</th>
                                    <th class="fw-semibold pe-4">Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                @php
                                    $bayarStyle = match($order->payment_status ?? 'belum_bayar') {
                                        'lunas'               => ['bg'=>'#d1fae5','text'=>'#065f46','label'=>'Lunas'],
                                        'menunggu_konfirmasi' => ['bg'=>'#fff3cd','text'=>'#856404','label'=>'Menunggu'],
                                        default               => ['bg'=>'#fee2e2','text'=>'#991b1b','label'=>'Belum'],
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold" style="color:var(--warna-utama);font-size:0.82rem;font-family:monospace;">
                                            {{ $order->order_number ?? 'RDH-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.88rem;">{{ $order->user->full_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge rounded-pill px-2" style="font-size:0.75rem;
                                            background:{{ match($order->status){ 'selesai'=>'#d1fae5','diproses'=>'#fff3cd','dibatalkan'=>'#fee2e2',default=>'#dbeafe'} }};
                                            color:{{ match($order->status){ 'selesai'=>'#065f46','diproses'=>'#856404','dibatalkan'=>'#991b1b',default=>'#1e40af'} }};">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge rounded-pill px-2"
                                              style="font-size:0.75rem;background:{{ $bayarStyle['bg'] }};color:{{ $bayarStyle['text'] }};">
                                            {{ $bayarStyle['label'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox d-block mb-1 opacity-25 fs-3"></i>
                                        Belum ada pesanan cetak.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Pesanan ATK Terbaru --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
                     style="border-radius:16px 16px 0 0; border-bottom:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                        <i class="bi bi-bag me-2 text-warning"></i>Pesanan ATK Terbaru
                    </h6>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                        Laporan
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background:#f8faff;">
                                <tr style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;">
                                    <th class="ps-4 py-3 fw-semibold">Pelanggan</th>
                                    <th class="fw-semibold">Total</th>
                                    <th class="fw-semibold pe-4">Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAtk as $order)
                                @php
                                    $bs = match($order->payment_status ?? 'belum_bayar') {
                                        'lunas'               => ['bg'=>'#d1fae5','text'=>'#065f46','label'=>'Lunas'],
                                        'menunggu_konfirmasi' => ['bg'=>'#fff3cd','text'=>'#856404','label'=>'Menunggu'],
                                        default               => ['bg'=>'#fee2e2','text'=>'#991b1b','label'=>'Belum'],
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4" style="font-size:0.88rem;">
                                        {{ $order->user->full_name ?? '-' }}
                                    </td>
                                    <td style="font-size:0.85rem;" class="fw-semibold">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge rounded-pill px-2"
                                              style="font-size:0.75rem;background:{{ $bs['bg'] }};color:{{ $bs['text'] }};">
                                            {{ $bs['label'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-bag d-block mb-1 opacity-25 fs-3"></i>
                                        Belum ada pesanan ATK.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}

    <script>
        const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        function updateClock() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2,'0');
            const mnt = String(now.getMinutes()).padStart(2,'0');
            const dtk = String(now.getSeconds()).padStart(2,'0');
            document.getElementById('realtime-clock').textContent = `${jam}:${mnt}:${dtk}`;
            document.getElementById('realtime-date').textContent  =
                `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

@endsection
