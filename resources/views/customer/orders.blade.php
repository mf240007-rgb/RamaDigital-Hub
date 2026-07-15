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
.order-card[data-status="sisa_menunggu_konfirmasi"] { border-left: 5px solid #f59e0b !important; }
.order-card[data-status="ditolak"]             { border-left: 5px solid #ef4444 !important; }
.order-card[data-status="dibatalkan"]          { border-left: 5px solid #ef4444 !important; }
.order-card[data-status="selesai"]             { border-left: 5px solid #10b981 !important; }
.order-card[data-status="diproses"]            { border-left: 5px solid #3b82f6 !important; }
.order-card[data-status="Menunggu Antrean"]    { border-left: 5px solid #f59e0b !important; }
.order-card[data-status="menunggu_persetujuan_batal"] { border-left: 5px solid #db2777 !important; }
.order-card[data-status="menunggu_pelunasan_sisa"] { border-left: 5px solid #f59e0b !important; }

/* Header gradient sesuai status */
.order-header-lunas              { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); }
.order-header-menunggu_konfirmasi { background: linear-gradient(135deg, #fffbeb, #fef9c3); }
.order-header-sisa_menunggu_konfirmasi { background: linear-gradient(135deg, #fffbeb, #fef9c3); }
.order-header-ditolak            { background: linear-gradient(135deg, #fff5f5, #fee2e2); }
.order-header-dibatalkan         { background: linear-gradient(135deg, #fff5f5, #fee2e2); }
.order-header-selesai            { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); }
.order-header-diproses           { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
.order-header-menunggu           { background: linear-gradient(135deg, #fffbeb, #fef9c3); }
.order-header-menunggu_persetujuan_batal { background: linear-gradient(135deg, #fff7fb, #fce7f3); }
.order-header-menunggu_pelunasan_sisa { background: linear-gradient(135deg, #fffbeb, #fef9c3); }

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
        @php
            $jasaOrders    = $orders->where('item_type', 'jasa')->values();
            $atkOrders     = $orders->where('item_type', 'produk')->values();
            $filterAktif   = $tipe ?? 'semua';
            // Hitung badge dari semua pesanan (tidak terpengaruh filter aktif)
            $allJasaCount  = isset($allOrders) ? $allOrders->where('item_type','jasa')->count() : $jasaOrders->count();
            $allAtkCount   = isset($allOrders) ? $allOrders->where('item_type','produk')->count() : $atkOrders->count();
            $allTotalCount = isset($allOrders) ? $allOrders->count() : $orders->count();
        @endphp

        {{-- ── Tab Filter ──────────────────────────────────── --}}
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('customer.orders') }}"
               class="btn rounded-pill px-4 fw-semibold {{ $filterAktif === 'semua' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua
                <span class="badge rounded-pill ms-1 {{ $filterAktif === 'semua' ? 'bg-white text-primary' : 'bg-secondary' }}">
                    {{ $allTotalCount }}
                </span>
            </a>
            <a href="{{ route('customer.orders', ['tipe' => 'cetak']) }}"
               class="btn rounded-pill px-4 fw-semibold {{ $filterAktif === 'cetak' ? 'btn-primary' : 'btn-outline-secondary' }}">
                <i class="bi bi-printer me-1"></i>Jasa Cetak
                <span class="badge rounded-pill ms-1 {{ $filterAktif === 'cetak' ? 'bg-white text-primary' : 'bg-secondary' }}">
                    {{ $allJasaCount }}
                </span>
            </a>
            <a href="{{ route('customer.orders', ['tipe' => 'atk']) }}"
               class="btn rounded-pill px-4 fw-semibold {{ $filterAktif === 'atk' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                <i class="bi bi-bag me-1"></i>Produk ATK
                <span class="badge rounded-pill ms-1 {{ $filterAktif === 'atk' ? 'bg-white text-warning' : 'bg-secondary' }}">
                    {{ $allAtkCount }}
                </span>
            </a>
        </div>

        {{-- ── Pesanan Jasa Cetak ──────────────────────────── --}}
        @if($jasaOrders->isNotEmpty() && in_array($filterAktif, ['semua', 'cetak']))
            @if($filterAktif === 'semua')
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-printer-fill text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0" style="color:var(--warna-gelap);">Pesanan Jasa Cetak</h5>
                    <span class="badge rounded-pill bg-primary ms-1">{{ $jasaOrders->count() }}</span>
                </div>
            @endif
            <div class="d-flex flex-column gap-4 {{ $filterAktif === 'semua' ? 'mb-5' : '' }}">
            @foreach($jasaOrders as $order)
                @php $isJasa = true; @endphp
                @include('customer._order_card')
            @endforeach
            </div>
        @endif

        {{-- ── Pesanan Produk ATK ──────────────────────────── --}}
        @if($atkOrders->isNotEmpty() && in_array($filterAktif, ['semua', 'atk']))
            @if($filterAktif === 'semua')
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-bag-fill text-warning fs-5"></i>
                    <h5 class="fw-bold mb-0" style="color:var(--warna-gelap);">Pesanan Produk ATK</h5>
                    <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $atkOrders->count() }}</span>
                </div>
            @endif
            <div class="d-flex flex-column gap-4">
            @foreach($atkOrders as $order)
                @php $isJasa = false; @endphp
                @include('customer._order_card')
            @endforeach
            </div>
        @endif

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

function setModalQris(btn) {
    const dp    = btn.getAttribute('data-dp');
    const order = btn.getAttribute('data-order');
    document.getElementById('qrisDpAmount').textContent  = 'Rp ' + dp;
    document.getElementById('qrisOrderNum').textContent  = order;
}
</script>

{{-- ── Modal QRIS & Cara Bayar DP ─────────────────────────── --}}
<div class="modal fade" id="modalQrisDP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header border-0 text-white text-center justify-content-center pb-0"
                 style="background:linear-gradient(135deg,#1a73e8,#4a9eff);padding:20px 24px 16px;">
                <div class="w-100">
                    <i class="bi bi-qr-code fs-1 d-block mb-1"></i>
                    <h5 class="fw-bold mb-0">Bayar DP via QRIS</h5>
                    <small style="opacity:.85;">Pesanan: <span id="qrisOrderNum" class="fw-bold"></span></small>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3 text-center">

                {{-- Nominal DP --}}
                <div class="alert border-0 py-2 mb-3"
                     style="background:#f0f9ff;border-radius:10px;">
                    <div class="text-muted" style="font-size:.8rem;">Nominal DP yang harus dibayar:</div>
                    <div class="fw-bold fs-5" id="qrisDpAmount"
                         style="color:var(--warna-aksen,#ff6d00);"></div>
                    <div class="text-muted mt-1" style="font-size:.75rem;">50% dari total estimasi pesanan</div>
                </div>

                {{-- QRIS Image --}}
                @php
                    $qrisModal = null;
                    foreach(['jpg','jpeg','png'] as $ext) {
                        if(file_exists(public_path('images/qris.'.$ext))) {
                            $qrisModal = asset('images/qris.'.$ext).'?t='.filemtime(public_path('images/qris.'.$ext));
                            break;
                        }
                    }
                @endphp
                @if($qrisModal)
                    <img src="{{ $qrisModal }}" alt="QRIS"
                         class="img-fluid rounded-3 shadow-sm mb-2"
                         style="max-width:220px;border:1px solid #e2e8f0;">
                    <div class="mb-3">
                        <a href="{{ $qrisModal }}" download="QRIS-RamaDigital-Hub"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-download me-1"></i>Download QRIS
                        </a>
                    </div>
                @else
                    <div class="rounded-3 mx-auto mb-3 d-flex flex-column align-items-center justify-content-center"
                         style="width:180px;height:180px;background:#f8faff;border:2px dashed #bee5eb;">
                        <i class="bi bi-qr-code" style="font-size:3.5rem;color:#1a73e8;opacity:0.35;"></i>
                        <small class="text-muted mt-2" style="font-size:.72rem;">QRIS belum tersedia</small>
                    </div>
                @endif

                {{-- Info Rekening --}}
                <div class="rounded-3 py-2 px-3 mb-3 text-start"
                     style="background:#fff8f0;border:1px solid #fed7aa;font-size:.82rem;">
                    <div class="fw-semibold text-warning mb-1">
                        <i class="bi bi-bank me-1"></i>Transfer Rekening BRI
                    </div>
                    <div class="fw-bold" style="font-size:1rem;letter-spacing:.5px;">3286-01-053842-53-3</div>
                    <div class="text-muted">a.n. Apriati</div>
                </div>

                {{-- Langkah --}}
                <div class="text-start mb-2" style="font-size:.8rem;color:#6c757d;">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <i class="bi bi-1-circle-fill text-primary flex-shrink-0 mt-1"></i>
                        <span>Buka aplikasi m-banking / dompet digital</span>
                    </div>
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <i class="bi bi-2-circle-fill text-primary flex-shrink-0 mt-1"></i>
                        <span>Scan QRIS atau transfer ke rekening BRI di atas</span>
                    </div>
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <i class="bi bi-3-circle-fill text-primary flex-shrink-0 mt-1"></i>
                        <span>Kembali ke halaman ini, lalu upload screenshot bukti</span>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-4-circle-fill text-primary flex-shrink-0 mt-1"></i>
                        <span>Klik <strong>Kirim Bukti DP</strong> untuk mengirim ke admin</span>
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-primary rounded-pill w-100 fw-semibold"
                        data-bs-dismiss="modal">
                    <i class="bi bi-check me-1"></i>Mengerti, Saya Akan Transfer
                </button>
            </div>

        </div>
    </div>
</div>
