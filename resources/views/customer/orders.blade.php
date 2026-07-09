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
        @php
            $jasaOrders = $orders->where('item_type', 'jasa')->values();
            $atkOrders  = $orders->where('item_type', 'produk')->values();
        @endphp

        {{-- ── Pesanan Jasa Cetak ──────────────────────────── --}}
        @if($jasaOrders->isNotEmpty())
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-printer-fill text-primary fs-5"></i>
                <h5 class="fw-bold mb-0" style="color:var(--warna-gelap);">Pesanan Jasa Cetak</h5>
                <span class="badge rounded-pill bg-primary ms-1">{{ $jasaOrders->count() }}</span>
            </div>
            <div class="d-flex flex-column gap-4 mb-5">
            @foreach($jasaOrders as $order)
                @php $isJasa = true; @endphp
                @include('customer._order_card')
            @endforeach
            </div>
        @endif

        {{-- ── Pesanan Produk ATK ──────────────────────────── --}}
        @if($atkOrders->isNotEmpty())
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-bag-fill text-warning fs-5"></i>
                <h5 class="fw-bold mb-0" style="color:var(--warna-gelap);">Pesanan Produk ATK</h5>
                <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $atkOrders->count() }}</span>
            </div>
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
</script>
