@extends('layouts.app')

@section('content')

<style>
/* ── Order Card ─────────────────────────────────────── */
.order-card {
    border-radius: 18px !important;
    overflow: hidden;
    transition: box-shadow .2s ease, transform .2s ease;
    background: #fff;
}
.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.10) !important;
}

/* Header accent strip kiri berdasarkan status */
.order-card[data-status="lunas"]               { border-left: 5px solid #10b981 !important; }
.order-card[data-status="menunggu_konfirmasi"]  { border-left: 5px solid #f59e0b !important; }
.order-card[data-status="ditolak"]             { border-left: 5px solid #ef4444 !important; }
.order-card[data-status="selesai"]             { border-left: 5px solid #10b981 !important; }
.order-card[data-status="diproses"]            { border-left: 5px solid #3b82f6 !important; }
.order-card[data-status="Menunggu Antrean"]    { border-left: 5px solid #f59e0b !important; }

/* Header gradient sesuai status */
.order-header-lunas              { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); }
.order-header-menunggu_konfirmasi { background: linear-gradient(135deg, #fffbeb, #fef9c3); }
.order-header-ditolak            { background: linear-gradient(135deg, #fff5f5, #fee2e2); }
.order-header-selesai            { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); }
.order-header-diproses           { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
.order-header-menunggu           { background: linear-gradient(135deg, #fffbeb, #fef9c3); }

/* Badge chip kecil */
.info-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    white-space: nowrap;
}

/* Divider putus-putus antar section */
.order-divider {
    border: none;
    border-top: 1.5px dashed #e2e8f0;
    margin: 0;
}

/* Total harga besar */
.order-total {
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--warna-aksen);
    letter-spacing: -0.5px;
}

/* Bukti thumbnail */
.bukti-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: border-color .2s;
}
.bukti-thumb:hover { border-color: var(--warna-utama); }

/* Upload form */
.upload-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    padding: 16px;
    background: #f8fafc;
    transition: border-color .2s;
}
.upload-zone:focus-within { border-color: var(--warna-utama); }

.detail-toggle {
    border-color: #cbd5e1;
    color: #475569;
}
.detail-toggle:hover,
.detail-toggle:focus {
    background: #f8fafc;
    color: var(--warna-gelap);
    border-color: #94a3b8;
}

.detail-link {
    color: #475569;
    font-size: .84rem;
    font-weight: 700;
    text-decoration: none;
}
.detail-link:hover,
.detail-link:focus {
    color: var(--warna-gelap);
    text-decoration: underline;
}

.detail-panel {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
}

.summary-text {
    color: var(--warna-gelap);
    font-size: .95rem;
    line-height: 1.45;
}

.summary-list {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
}

.summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
}

.summary-item + .summary-item {
    border-top: 1px solid #e2e8f0;
}

.summary-label {
    color: #0f172a;
    font-weight: 700;
    font-size: .92rem;
    min-width: 0;
}

.summary-value {
    flex-shrink: 0;
    padding: 4px 10px;
    border-radius: 999px;
    background: #f8fafc;
    color: #334155;
    border: 1px solid #e2e8f0;
    font-size: .8rem;
    font-weight: 700;
}

.card-actions {
    border-top: 1px solid #e2e8f0;
    background: linear-gradient(180deg, #fcfdff, #f8fafc);
}

.status-note {
    color: #64748b;
    font-size: .85rem;
    line-height: 1.35;
}

.pesanan-shell {
    padding-top: 6rem;
}

@media (max-width: 575.98px) {
    .pesanan-shell {
        padding-top: 5rem;
    }
}

@media (max-width: 575.98px) {
    .order-total { font-size: 1.1rem; }
    .info-chip   { font-size: 0.72rem; padding: 3px 9px; }
    .summary-item {
        align-items: flex-start;
        flex-direction: column;
    }
    .summary-value {
        align-self: flex-start;
    }
}
</style>

<div class="container pesanan-shell pb-4" style="max-width: 780px;">

    {{-- ── Page Header ──────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-4 page-header-row flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">
                <i class="bi bi-receipt me-2 text-primary"></i>Pesanan Saya
            </h2>
            <small class="text-muted">Pantau status pembayaran dan unggah bukti kapan saja.</small>
        </div>
        <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
            <i class="bi bi-bag-plus me-1"></i>Tambah Belanja
        </a>
    </div>

    {{-- ── Empty State ──────────────────────────────────────── --}}
    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-body p-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:80px;height:80px;background:#f0f4ff;">
                    <i class="bi bi-inbox" style="font-size:2.2rem;color:#94a3b8;"></i>
                </div>
                <h5 class="fw-bold mb-1" style="color:var(--warna-gelap);">Belum ada pesanan</h5>
                <p class="text-muted mb-4" style="font-size:.9rem;">
                    Setelah checkout, pesanan dan status pembayaran akan muncul di sini.
                </p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-bag me-1"></i>Mulai Belanja
                </a>
            </div>
        </div>

    @else
        <div class="d-flex flex-column gap-4">
        @foreach($orders as $order)
            @php
                $isJasa = $order->item_type === 'jasa';
                $ps     = $isJasa ? $order->status : ($order->payment_status ?? 'ditolak');

                // Untuk jasa cetak, buat style berdasarkan status order
                if ($isJasa) {
                    $payStyle = match($order->status) {
                        'selesai'          => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill','label'=>'Selesai'],
                        'diproses'         => ['bg'=>'#dbeafe','text'=>'#1e40af','icon'=>'bi-gear-fill',        'label'=>'Diproses'],
                        'dibatalkan'       => ['bg'=>'#fee2e2','text'=>'#991b1b','icon'=>'bi-x-circle-fill',    'label'=>'Dibatalkan'],
                        default            => ['bg'=>'#fff3cd','text'=>'#856404','icon'=>'bi-hourglass-split',  'label'=>'Menunggu Antrean'],
                    };
                } else {
                    $payStyle = $order->paymentBadge();
                }

                // Warna accent sesuai status
                $accentColor = match($ps) {
                    'lunas', 'selesai'    => '#10b981',
                    'menunggu_konfirmasi',
                    'Menunggu Antrean'    => '#f59e0b',
                    'diproses'            => '#3b82f6',
                    default               => '#ef4444',
                };
                $headerClass = $isJasa
                    ? 'order-header-' . ($order->status === 'Menunggu Antrean' ? 'menunggu' : $order->status)
                    : 'order-header-' . $ps;

                // Tampilkan nota jika: ATK=lunas, Jasa=selesai
                $tampilNota = (!$isJasa && $ps === 'lunas') || ($isJasa && $order->status === 'selesai');
            @endphp

            <div class="card border-0 shadow-sm order-card" data-status="{{ $ps }}">

                {{-- ── HEADER CARD ─────────────────────────────── --}}
                <div class="px-4 pt-4 pb-3 {{ $headerClass }} position-relative">
                    <div class="d-flex justify-content-between align-items-start gap-3">

                        {{-- Kiri: nomor & tanggal --}}
                        <div class="flex-grow-1">
                            <div class="fw-bold mb-1"
                                 style="font-family:monospace;font-size:1.05rem;color:var(--warna-gelap);letter-spacing:.5px;">
                                {{ $order->order_number ?? 'RDH-'.$order->id }}
                            </div>
                            <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.8rem;">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y') }}</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $order->created_at->format('H:i') }} WIB</span>
                            </div>
                        </div>

                        {{-- Kanan: badge status bayar --}}
                        <span class="badge rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1 flex-shrink-0"
                              style="background:{{ $payStyle['bg'] }};color:{{ $payStyle['text'] }};font-size:.82rem;">
                            <i class="bi {{ $payStyle['icon'] }}"></i>
                            {{ $payStyle['label'] }}
                        </span>
                    </div>

                    {{-- Info chips: tipe · total --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="info-chip"
                              style="background:#f8fafc;color:#0f172a;border:1px solid #e2e8f0;">
                            <i class="bi {{ $order->item_type === 'jasa' ? 'bi-printer' : 'bi-bag' }}"></i>
                            {{ $order->item_type === 'jasa' ? 'Jasa Cetak' : 'Produk ATK' }}
                        </span>
                        <span class="info-chip"
                              style="background:#ffffff;color:#0f172a;border:1px solid #e2e8f0;">
                            <i class="bi bi-cash-coin"></i>
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <hr class="order-divider">

                {{-- ── BODY CARD ────────────────────────────────── --}}
                <div class="px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <div class="text-uppercase text-muted fw-semibold" style="font-size:.75rem; letter-spacing:.06em;">Ringkasan Pesanan</div>
                            <div class="text-muted" style="font-size:.86rem;">Item dan jumlah dibuat seragam untuk tiap transaksi.</div>
                        </div>

                        <button class="btn btn-link p-0 detail-link"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#detailOrder{{ $order->id }}"
                                aria-expanded="false"
                                aria-controls="detailOrder{{ $order->id }}">
                            Detail
                        </button>
                    </div>

                    @php $summaryRows = $order->summaryRows(); @endphp
                    <div class="summary-list">
                        @foreach($summaryRows as $row)
                            <div class="summary-item">
                                <div class="summary-label text-truncate">{{ $row['label'] }}</div>
                                <div class="summary-value">{{ $row['value'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="collapse mt-3" id="detailOrder{{ $order->id }}">
                        <div class="detail-panel p-3">
                            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">
                                Detail Tambahan
                            </div>
                            <div class="fw-semibold" style="color:var(--warna-gelap);font-size:.95rem;line-height:1.5;">
                                {{ $order->detail_pesanan }}
                            </div>

                            @if($order->item_type === 'jasa')
                                @php $dokumenDisplayNames = $order->getDokumenDisplayNames(); @endphp
                                <div class="mt-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-files text-primary"></i>
                                        <div class="fw-semibold" style="font-size:.9rem;color:var(--warna-gelap);">Dokumen yang Dicetak</div>
                                    </div>
                                    @if(!empty($dokumenDisplayNames))
                                        <ul class="mb-0 ps-3" style="font-size:.88rem;color:#334155;line-height:1.6;">
                                            @foreach($dokumenDisplayNames as $namaFile)
                                                <li>{{ $namaFile }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted" style="font-size:.88rem;">Belum ada dokumen yang diunggah.</div>
                                    @endif
                                </div>
                            @endif

                            @if($order->item_type === 'jasa' && $order->catatan)
                                <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2"
                                     style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <i class="bi bi-chat-left-text-fill text-info flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                                    <div style="font-size:.88rem;color:#0369a1;">
                                        <strong>Catatan:</strong><br>
                                        {{ $order->catatan }}
                                    </div>
                                </div>
                            @endif

                            @if($order->catatan_pembayaran)
                                @php
                                    $catatanBg    = $ps === 'ditolak' ? '#fff5f5' : ($ps === 'menunggu_persetujuan_batal' ? '#fce7f3' : '#f0f9ff');
                                    $catatanBorder= $ps === 'ditolak' ? '#fecaca' : ($ps === 'menunggu_persetujuan_batal' ? '#fbcfe8' : '#bae6fd');
                                    $catatanIcon  = $ps === 'ditolak' ? 'bi-exclamation-triangle-fill text-danger' : ($ps === 'menunggu_persetujuan_batal' ? 'bi-clock-history text-danger' : 'bi-chat-left-text-fill text-info');
                                    $catatanText  = $ps === 'ditolak' ? '#991b1b' : ($ps === 'menunggu_persetujuan_batal' ? '#9d174d' : '#0369a1');
                                @endphp
                                <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2"
                                     style="background:{{ $catatanBg }};border:1px solid {{ $catatanBorder }};">
                                    <i class="bi {{ $catatanIcon }} flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                                    <div style="font-size:.88rem;color:{{ $catatanText }};">
                                        @if($ps === 'ditolak') <strong>Alasan Penolakan:</strong><br> @endif
                                        {{ $order->catatan_pembayaran }}
                                    </div>
                                </div>
                            @endif

                            @if($order->cancellation_reason && $ps === 'menunggu_persetujuan_batal')
                                <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2"
                                     style="background:#fce7f3;border:1px solid #fbcfe8;">
                                    <i class="bi bi-clock-history text-danger flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                                    <div style="font-size:.88rem;color:#9d174d;">
                                        <strong>Alasan Pembatalan yang Diajukan:</strong><br>
                                        {{ $order->cancellation_reason }}
                                        <div class="mt-1 text-muted" style="font-size:.78rem;">
                                            Diajukan {{ $order->cancellation_requested_at?->format('d M Y H:i') }} WIB
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                 @if(!$isJasa || $tampilNota)
                    <hr class="order-divider">

                    {{-- ── FOOTER CARD: Aksi utama ───────────────── --}}
                    <div class="px-4 py-3 card-actions">
                        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <div class="status-note">
                                @if($ps === 'ditolak')
                                    Bukti pembayaran ditolak. Silakan upload ulang.
                                @elseif($ps === 'menunggu_konfirmasi')
                                    Menunggu verifikasi admin.
                                @elseif($ps === 'menunggu_persetujuan_batal')
                                    Permintaan pembatalan sedang diproses admin.
                                @elseif($ps === 'lunas')
                                    Pembayaran telah dikonfirmasi.
                                @elseif($isJasa && $order->status === 'selesai')
                                    Pesanan selesai dikerjakan.
                                @else
                                    Pesanan sedang diproses.
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                @if($ps === 'ditolak')
                                    <form action="{{ route('customer.orders.upload-bukti', $order->id) }}"
                                          method="POST"
                                          enctype="multipart/form-data"
                                          class="d-inline-flex align-items-center gap-2 flex-wrap">
                                        @csrf
                                        <input type="file"
                                               name="bukti_bayar"
                                               class="form-control form-control-sm @error('bukti_bayar') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg"
                                               style="max-width:220px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                            <i class="bi bi-send me-1"></i>Kirim Bukti
                                        </button>
                                    </form>
                                @elseif($ps === 'menunggu_konfirmasi')
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold"
                                            onclick="bukaModalBatalPelanggan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                        <i class="bi bi-x-circle me-1"></i>Ajukan Pembatalan
                                    </button>
                                @elseif($ps === 'menunggu_persetujuan_batal')
                                    @php
                                        $adminWa = '6285273300045';
                                        $waMsg = urlencode('Halo Admin, saya ingin menindaklanjuti permintaan pembatalan pesanan ' . ($order->order_number ?? '#'.$order->id) . '. Mohon informasi proses refundnya. Terima kasih.');
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $adminWa }}&text={{ $waMsg }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">
                                        <i class="bi bi-whatsapp me-1"></i>Hubungi Admin
                                    </a>
                                @endif

                                @if($tampilNota)
                                    <a href="{{ route('customer.orders.nota', $order->id) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                        <i class="bi bi-receipt me-1"></i>Lihat Nota
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>{{-- /order-card --}}
        @endforeach
        </div>
    @endif

</div>

{{-- Modal Ajukan Pembatalan --}}
<div class="modal fade" id="modalBatalPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0" style="border-radius:18px;overflow:hidden;">
            <div class="modal-header border-0 px-4 pt-4 pb-2" style="background:#fce7f3;">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#9d174d;">
                        <i class="bi bi-x-circle me-2"></i>Ajukan Pembatalan
                    </h5>
                    <p class="text-muted mb-0" id="labelNoBatalPelanggan" style="font-size:.85rem;"></p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBatalPelanggan" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="alert alert-warning py-2 px-3 mb-3" style="border-radius:10px;font-size:.83rem;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Permintaan pembatalan akan dikirim ke admin. Refund akan diproses manual oleh admin via WhatsApp setelah disetujui.
                    </div>
                    <label class="form-label fw-semibold mb-1" style="font-size:.88rem;">
                        Alasan Pembatalan <span class="text-danger">*</span>
                    </label>
                    <textarea name="cancellation_reason"
                              class="form-control"
                              rows="3"
                              required
                              placeholder="Misal: Saya ingin mengubah pesanan / salah pilih produk"></textarea>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                            data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn rounded-pill flex-fill fw-semibold text-white"
                            style="background:#9d174d;">
                        <i class="bi bi-send me-1"></i>Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalBatalPelanggan(orderId, orderNumber) {
    document.getElementById('formBatalPelanggan').action = '/pesanan-saya/' + orderId + '/ajukan-batal';
    document.getElementById('labelNoBatalPelanggan').textContent = 'Pesanan: ' + orderNumber;
    document.querySelector('#formBatalPelanggan textarea').value = '';
    new bootstrap.Modal(document.getElementById('modalBatalPelanggan')).show();
}
</script>
