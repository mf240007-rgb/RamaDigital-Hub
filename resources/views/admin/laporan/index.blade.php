@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Laporan Pesanan</h2>
            <small class="text-muted">Rekap pesanan ATK dan Jasa Cetak</small>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-printer me-1"></i>Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 12px;">
        <div class="card-body px-4 py-3">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size: 0.85rem;">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach($bulanList as $val => $label)
                            <option value="{{ $val }}" {{ $bulan === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size: 0.85rem;">Tipe Pesanan</label>
                    <select name="tipe" class="form-select">
                        <option value="semua" {{ $tipe === 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="atk"   {{ $tipe === 'atk'   ? 'selected' : '' }}>ATK</option>
                        <option value="cetak" {{ $tipe === 'cetak' ? 'selected' : '' }}>Jasa Cetak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg,#1a73e8,#4a9eff); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(255,255,255,0.2);font-size:1.4rem;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Total Pesanan</div>
                        <div class="fw-bold fs-4 lh-1 mt-1">{{ $totalPesanan }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg,#10b981,#34d399); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(255,255,255,0.2);font-size:1.4rem;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pendapatan Lunas</div>
                        <div class="fw-bold fs-5 lh-1 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg,#f59e0b,#fbbf24); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(255,255,255,0.2);font-size:1.4rem;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">ATK / Cetak</div>
                        <div class="fw-bold fs-4 lh-1 mt-1">{{ $jumlahAtk }} / {{ $jumlahCetak }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: linear-gradient(135deg,#8b5cf6,#a78bfa); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px;height:48px;background:rgba(255,255,255,0.2);font-size:1.4rem;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Selesai / Proses</div>
                        <div class="fw-bold fs-4 lh-1 mt-1">{{ $pesananSelesai }} / {{ $pesananProses }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Pembayaran --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px;">
                <div class="fw-bold fs-3 text-success">{{ $lunas }}</div>
                <small class="text-muted">Pembayaran Lunas</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px;">
                <div class="fw-bold fs-3 text-warning">{{ $menungguKonfirmasi }}</div>
                <small class="text-muted">Menunggu Konfirmasi</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px;">
                <div class="fw-bold fs-3 text-danger">{{ $belumBayar }}</div>
                <small class="text-muted">Belum Bayar</small>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius:16px 16px 0 0; border-bottom:1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                <i class="bi bi-table me-2 text-primary"></i>Detail Pesanan
            </h6>
            <span class="text-muted" style="font-size: 0.85rem;">{{ $orders->count() }} pesanan ditemukan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                    <thead style="background: #f8faff;">
                        <tr style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d;">
                            <th class="ps-4 py-3 fw-semibold">No</th>
                            <th class="fw-semibold">No. Pesanan</th>
                            <th class="fw-semibold">Pelanggan</th>
                            <th class="fw-semibold">Tipe</th>
                            <th class="fw-semibold">Detail</th>
                            <th class="fw-semibold">Total</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold">Pembayaran</th>
                            <th class="fw-semibold pe-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                        @php
                            $statusStyle = match($order->status) {
                                'Menunggu Antrean' => ['bg'=>'#dbeafe','text'=>'#1e40af'],
                                'diproses'         => ['bg'=>'#fff3cd','text'=>'#856404'],
                                'selesai'          => ['bg'=>'#d1fae5','text'=>'#065f46'],
                                'dibatalkan'       => ['bg'=>'#fee2e2','text'=>'#991b1b'],
                                default            => ['bg'=>'#f3f4f6','text'=>'#374151'],
                            };
                            $bayarStyle = match($order->payment_status) {
                                'lunas'                 => ['bg'=>'#d1fae5','text'=>'#065f46'],
                                'menunggu_konfirmasi'   => ['bg'=>'#fff3cd','text'=>'#856404'],
                                default                 => ['bg'=>'#fee2e2','text'=>'#991b1b'],
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                            <td>
                                <span class="fw-semibold" style="color: var(--warna-utama); font-family: monospace; font-size: 0.82rem;">
                                    {{ $order->order_number ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $order->user->full_name ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill px-2"
                                      style="background: {{ $order->item_type==='produk' ? '#d1fae5' : '#dbeafe' }};
                                             color: {{ $order->item_type==='produk' ? '#065f46' : '#1e40af' }};">
                                    {{ $order->item_type === 'produk' ? 'ATK' : 'Cetak' }}
                                </span>
                            </td>
                            <td style="max-width: 180px;">
                                <div class="text-truncate" title="{{ $order->detail_pesanan }}">
                                    {{ $order->detail_pesanan }}
                                </div>
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge rounded-pill px-2"
                                      style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}; font-size: 0.78rem;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2"
                                      style="background: {{ $bayarStyle['bg'] }}; color: {{ $bayarStyle['text'] }}; font-size: 0.78rem;">
                                    {{ $order->paymentLabel() }}
                                </span>
                            </td>
                            <td class="pe-4 text-muted">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada pesanan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($orders->count() > 0)
                    <tfoot style="background: #f8faff;">
                        <tr>
                            <td colspan="5" class="ps-4 py-3 fw-bold text-end pe-2">Total Pendapatan Lunas:</td>
                            <td colspan="4" class="fw-bold py-3" style="color: #065f46;">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

<style>
@media print {
    .sidebar, .d-print-none { display: none !important; }
    .col-md-9, .col-lg-10 { width: 100% !important; max-width: 100% !important; }
    .main-content { padding: 0 !important; }
}
</style>
@endsection
