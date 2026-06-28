@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Laporan Pendapatan ATK</h2>
            <small class="text-muted">Rekap penjualan produk alat tulis kantor per bulan</small>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3 d-print-none">
            <i class="bi bi-printer me-1"></i>Cetak Laporan
        </button>
    </div>

    {{-- Filter Bulan --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 12px;">
        <div class="card-body px-4 py-3">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-1" style="font-size: 0.85rem;">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach($bulanList as $val => $label)
                            <option value="{{ $val }}" {{ $bulan === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
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

        {{-- Pendapatan --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:14px; background:linear-gradient(135deg,#10b981,#34d399); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="small opacity-80">Pendapatan Bulan Ini</div>
                        <div class="fw-bold mt-1" style="font-size:1.3rem;line-height:1.2;">
                            Rp {{ number_format($pendapatanAtk, 0, ',', '.') }}
                        </div>
                        <small class="opacity-75">dari {{ $pesananSelesaiAtk }} pesanan selesai</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pesanan --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:14px; background:linear-gradient(135deg,#1a73e8,#4a9eff); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <div class="small opacity-80">Total Pesanan ATK</div>
                        <div class="fw-bold fs-2 lh-1 mt-1">{{ $totalPesananAtk }}</div>
                        <small class="opacity-75">pesanan bulan ini</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:14px; background:linear-gradient(135deg,#8b5cf6,#a78bfa); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="small opacity-80">Selesai / Diproses</div>
                        <div class="fw-bold fs-2 lh-1 mt-1">{{ $pesananSelesaiAtk }} / {{ $pesananProsesAtk }}</div>
                        <small class="opacity-75">{{ $pesananBatalAtk }} dibatalkan</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-rata --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:14px; background:linear-gradient(135deg,#f59e0b,#fbbf24); color:white;">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:rgba(255,255,255,0.2);font-size:1.5rem;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <div class="small opacity-80">Rata-rata per Pesanan</div>
                        <div class="fw-bold mt-1" style="font-size:1.1rem;line-height:1.2;">
                            Rp {{ $pesananSelesaiAtk > 0 ? number_format($pendapatanAtk / $pesananSelesaiAtk, 0, ',', '.') : '0' }}
                        </div>
                        <small class="opacity-75">nilai rata-rata transaksi</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Grafik Tren + Produk Terlaris --}}
    <div class="row g-3 mb-4">

        {{-- Tren Pendapatan 12 Bulan --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-header bg-white px-4 py-3 border-0"
                     style="border-radius:14px 14px 0 0; border-bottom:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-0" style="color:var(--warna-gelap);">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Tren Pendapatan ATK (12 Bulan)
                    </h6>
                </div>
                <div class="card-body px-4 py-3">
                    @php
                        $maxIncome = $rekapBulanan->max('income') ?: 1;
                    @endphp
                    <div class="d-flex align-items-end gap-1" style="height: 150px;">
                        @foreach($rekapBulanan as $rb)
                            @php
                                $heightPct = ($rb['income'] / $maxIncome) * 100;
                                $isActive  = substr($rb['label'], -2) === now()->format('y')
                                             && strtolower(substr($rb['label'], 0, 3)) === strtolower(now()->isoFormat('MMM'));
                            @endphp
                            <div class="d-flex flex-column align-items-center flex-fill" title="Rp {{ number_format($rb['income'], 0, ',', '.') }}">
                                <div class="w-100 rounded-top"
                                     style="height: {{ max($heightPct, 3) }}%;
                                            background: {{ $rb['income'] > 0 ? ($isActive ? '#1a73e8' : '#93c5fd') : '#e2e8f0' }};
                                            min-height: 4px;">
                                </div>
                                <small class="text-muted mt-1" style="font-size:0.6rem;writing-mode:vertical-lr;transform:rotate(180deg);white-space:nowrap;">
                                    {{ $rb['label'] }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-2 text-end">
                        <small class="text-muted" style="font-size:0.75rem;">
                            <span class="d-inline-block rounded me-1" style="width:10px;height:10px;background:#1a73e8;"></span>Bulan ini
                            <span class="d-inline-block rounded ms-2 me-1" style="width:10px;height:10px;background:#93c5fd;"></span>Bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produk Terlaris --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
                <div class="card-header bg-white px-4 py-3 border-0"
                     style="border-radius:14px 14px 0 0; border-bottom:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-0" style="color:var(--warna-gelap);">
                        <i class="bi bi-trophy-fill me-2 text-warning"></i>Produk Terlaris Bulan Ini
                    </h6>
                </div>
                <div class="card-body px-4 py-3">
                    @if(count($produkTerlaris) > 0)
                        @php $maxItem = max($produkTerlaris) ?: 1; @endphp
                        @foreach($produkTerlaris as $nama => $jumlah)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-truncate" style="font-size:0.85rem;max-width:200px;" title="{{ $nama }}">{{ $nama }}</span>
                                    <span class="fw-semibold ms-2" style="font-size:0.85rem;white-space:nowrap;">{{ $jumlah }} pesanan</span>
                                </div>
                                <div class="progress" style="height:8px;border-radius:4px;">
                                    <div class="progress-bar"
                                         style="width:{{ ($jumlah / $maxItem) * 100 }}%;background:var(--warna-utama);">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                            Belum ada data produk terjual bulan ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Detail Pesanan ATK --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius:16px 16px 0 0; border-bottom:1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color:var(--warna-gelap);">
                <i class="bi bi-table me-2 text-primary"></i>Rincian Pesanan ATK
            </h6>
            <span class="text-muted" style="font-size:0.85rem;">{{ $atkOrders->count() }} pesanan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
                    <thead style="background:#f8faff;">
                        <tr style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;">
                            <th class="ps-4 py-3 fw-semibold" style="width:40px;">No</th>
                            <th class="fw-semibold py-3" style="width:160px;">No. Pesanan</th>
                            <th class="fw-semibold py-3" style="width:160px;">Pelanggan</th>
                            <th class="fw-semibold py-3">Produk Dibeli</th>
                            <th class="fw-semibold py-3 text-end" style="width:130px;">Total</th>
                            <th class="fw-semibold py-3 text-center" style="width:130px;">Pembayaran</th>
                            <th class="fw-semibold py-3 text-center" style="width:150px;">Verifikasi</th>
                            <th class="fw-semibold py-3 pe-4" style="width:120px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atkOrders as $i => $order)
                        @php
                            $statusStyle = match($order->status) {
                                'selesai'          => ['bg'=>'#d1fae5','text'=>'#065f46'],
                                'diproses'         => ['bg'=>'#fff3cd','text'=>'#856404'],
                                'dibatalkan'       => ['bg'=>'#fee2e2','text'=>'#991b1b'],
                                default            => ['bg'=>'#dbeafe','text'=>'#1e40af'],
                            };
                            $payStyle = match($order->payment_status ?? 'belum_bayar') {
                                'lunas'                 => ['bg'=>'#d1fae5','text'=>'#065f46','label'=>'Lunas'],
                                'menunggu_konfirmasi'   => ['bg'=>'#fff3cd','text'=>'#856404','label'=>'Menunggu'],
                                'ditolak'               => ['bg'=>'#fee2e2','text'=>'#991b1b','label'=>'Ditolak'],
                                default                 => ['bg'=>'#f3f4f6','text'=>'#374151','label'=>'Belum Bayar'],
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted" style="font-size:0.85rem;">{{ $i + 1 }}</td>
                            <td>
                                <span class="fw-semibold"
                                      style="color:var(--warna-utama);font-family:monospace;font-size:0.8rem;white-space:nowrap;">
                                    {{ $order->order_number ?? '—' }}
                                </span>
                            </td>
                            <td style="font-size:0.88rem;">{{ $order->user->full_name ?? '—' }}</td>
                            <td style="font-size:0.85rem;">
                                <div class="text-truncate" style="max-width:240px;" title="{{ $order->detail_pesanan }}">
                                    {{ $order->detail_pesanan }}
                                </div>
                            </td>
                            <td class="text-end fw-semibold" style="font-size:0.88rem;white-space:nowrap;
                                color:{{ $order->payment_status === 'lunas' ? '#065f46' : 'inherit' }};">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>

                            {{-- Status Pembayaran --}}
                            <td class="text-center">
                                <span class="badge rounded-pill px-2 py-1 d-block mb-1"
                                      style="background:{{ $payStyle['bg'] }};color:{{ $payStyle['text'] }};font-size:0.77rem;">
                                    {{ $payStyle['label'] }}
                                </span>
                                @if($order->bukti_bayar)
                                    <a href="{{ route('admin.laporan.bukti', $order->id) }}"
                                       target="_blank"
                                       class="btn btn-link btn-sm p-0"
                                       style="font-size:0.72rem;color:var(--warna-utama);">
                                        <i class="bi bi-image me-1"></i>Lihat Bukti
                                    </a>
                                @endif
                            </td>

                            {{-- Tombol Verifikasi --}}
                            <td class="text-center">
                                @if(($order->payment_status ?? 'belum_bayar') === 'menunggu_konfirmasi')
                                    <button type="button"
                                            class="btn btn-sm btn-success rounded-pill px-2"
                                            style="font-size:0.75rem;"
                                            onclick="bukaVerifikasi({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}', {{ $order->total_harga }})">
                                        <i class="bi bi-check2-circle me-1"></i>Verifikasi
                                    </button>
                                @elseif(($order->payment_status ?? '') === 'lunas')
                                    <small class="text-success" style="font-size:0.75rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi<br>
                                        @if($order->paid_at)
                                            <span class="text-muted">{{ $order->paid_at->format('d M H:i') }}</span>
                                        @endif
                                    </small>
                                @elseif(($order->payment_status ?? '') === 'ditolak')
                                    <small class="text-danger" style="font-size:0.75rem;">
                                        <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                                        @if($order->catatan_verifikasi)
                                            <br><span class="text-muted">{{ $order->catatan_verifikasi }}</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted" style="font-size:0.8rem;">—</span>
                                @endif
                            </td>

                            <td class="pe-4 text-muted" style="font-size:0.82rem;white-space:nowrap;">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small>{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-bag-x fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada pesanan ATK pada bulan ini.
                                <br><small>Pesanan ATK akan muncul setelah pelanggan melakukan checkout dari keranjang belanja.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($atkOrders->where('status','selesai')->count() > 0)
                    <tfoot style="background:#f8faff;">
                        <tr>
                            <td colspan="4" class="ps-4 py-3 fw-bold text-end pe-2" style="font-size:0.88rem;">
                                Total Pendapatan (Pesanan Selesai):
                            </td>
                            <td class="py-3 text-end fw-bold" style="color:#065f46;font-size:0.95rem;">
                                Rp {{ number_format($pendapatanAtk, 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="pe-4"></td>
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

    {{-- Modal Verifikasi Pembayaran --}}
    <div class="modal fade" id="modalVerifikasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #f0fdf4;">
                    <div>
                        <h5 class="fw-bold mb-1 text-success">
                            <i class="bi bi-shield-check me-2"></i>Verifikasi Pembayaran
                        </h5>
                        <p class="text-muted mb-0" id="labelVerifikasiOrder" style="font-size:0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formVerifikasi" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <div class="p-3 rounded-3 mb-3 text-center"
                             style="background:#f8faff;border:1px solid #e8eeff;">
                            <small class="text-muted d-block">Total yang harus dibayar</small>
                            <div class="fw-bold fs-4 text-success" id="labelNominalVerifikasi">Rp 0</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Aksi</label>
                            <div class="d-flex gap-2">
                                <div class="form-check flex-fill border rounded-3 p-3"
                                     style="cursor:pointer;" onclick="setAksi('lunas', this)">
                                    <input class="form-check-input" type="radio" name="aksi" value="lunas" id="aksiLunas" required>
                                    <label class="form-check-label fw-semibold text-success" for="aksiLunas" style="cursor:pointer;">
                                        <i class="bi bi-check-circle-fill me-1"></i>Konfirmasi Lunas
                                    </label>
                                </div>
                                <div class="form-check flex-fill border rounded-3 p-3"
                                     style="cursor:pointer;" onclick="setAksi('ditolak', this)">
                                    <input class="form-check-input" type="radio" name="aksi" value="ditolak" id="aksiTolak">
                                    <label class="form-check-label fw-semibold text-danger" for="aksiTolak" style="cursor:pointer;">
                                        <i class="bi bi-x-circle-fill me-1"></i>Tolak Bukti
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold mb-1">Catatan (Opsional)</label>
                            <input type="text" name="catatan_verifikasi" class="form-control"
                                   placeholder="Misal: Pembayaran sudah diterima / Bukti tidak jelas">
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-check2 me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function bukaVerifikasi(orderId, orderNumber, total) {
        document.getElementById('labelVerifikasiOrder').textContent = 'Pesanan: ' + orderNumber;
        document.getElementById('labelNominalVerifikasi').textContent =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('formVerifikasi').action =
            '/admin/laporan/verifikasi/' + orderId;
        // Reset radio
        document.getElementById('aksiLunas').checked = false;
        document.getElementById('aksiTolak').checked = false;
        new bootstrap.Modal(document.getElementById('modalVerifikasi')).show();
    }

    function setAksi(val, el) {
        document.querySelector('input[name="aksi"][value="' + val + '"]').checked = true;
    }
    </script>

@endsection
