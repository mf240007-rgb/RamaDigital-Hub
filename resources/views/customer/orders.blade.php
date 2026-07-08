@extends('layouts.app')

@section('content')

<style>
/* ── Order Card ─────────────────────────────────────── */
.order-card {
    border-radius: 18px !important;
    overflow: hidden;
    transition: box-shadow .2s ease, transform .2s ease;
}
.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.10) !important;
}

/* Header accent strip kiri berdasarkan status */
.order-card[data-status="lunas"]               { border-left: 5px solid #10b981 !important; }
.order-card[data-status="menunggu_konfirmasi"]  { border-left: 5px solid #f59e0b !important; }
.order-card[data-status="ditolak"]             { border-left: 5px solid #ef4444 !important; }

/* Header gradient sesuai status */
.order-header-lunas              { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); }
.order-header-menunggu_konfirmasi { background: linear-gradient(135deg, #fffbeb, #fef9c3); }
.order-header-ditolak            { background: linear-gradient(135deg, #fff5f5, #fee2e2); }

/* Badge chip kecil */
.info-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
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

@media (max-width: 575.98px) {
    .order-total { font-size: 1.1rem; }
    .info-chip   { font-size: 0.72rem; padding: 3px 9px; }
}
</style>

<div class="container py-4" style="max-width: 780px;">

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
                $ps       = $order->payment_status ?? 'ditolak';
                $payStyle = $order->paymentBadge();

                // Warna accent sesuai status
                $accentColor = match($ps) {
                    'lunas'               => '#10b981',
                    'menunggu_konfirmasi' => '#f59e0b',
                    default               => '#ef4444',
                };
                $headerClass = 'order-header-' . $ps;
            @endphp

            <div class="card border-0 shadow-sm order-card" data-status="{{ $ps }}">

                {{-- ── HEADER CARD ─────────────────────────────── --}}
                <div class="px-4 pt-4 pb-3 {{ $headerClass }}">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                        {{-- Kiri: nomor & tanggal --}}
                        <div>
                            <div class="fw-bold mb-1"
                                 style="font-family:monospace;font-size:1.05rem;color:var(--warna-gelap);letter-spacing:.5px;">
                                {{ $order->order_number ?? 'RDH-'.$order->id }}
                            </div>
                            <div class="text-muted" style="font-size:.8rem;">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $order->created_at->format('d M Y') }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-clock me-1"></i>
                                {{ $order->created_at->format('H:i') }} WIB
                            </div>
                        </div>

                        {{-- Kanan: badge status bayar --}}
                        <span class="badge rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1"
                              style="background:{{ $payStyle['bg'] }};color:{{ $payStyle['text'] }};font-size:.82rem;">
                            <i class="bi {{ $payStyle['icon'] }}"></i>
                            {{ $payStyle['label'] }}
                        </span>
                    </div>

                    {{-- Info chips: tipe · status pesanan · total --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="info-chip"
                              style="background:rgba(255,255,255,.7);color:var(--warna-gelap);border:1px solid #e2e8f0;">
                            <i class="bi {{ $order->item_type === 'jasa' ? 'bi-printer' : 'bi-bag' }}"></i>
                            {{ $order->item_type === 'jasa' ? 'Jasa Cetak' : 'Produk ATK' }}
                        </span>
                        <span class="info-chip"
                              style="background:rgba(255,255,255,.7);color:var(--warna-gelap);border:1px solid #e2e8f0;">
                            <i class="bi bi-activity"></i>
                            {{ $order->status }}
                        </span>
                        <span class="info-chip"
                              style="background:{{ $accentColor }}18;color:{{ $accentColor }};border:1px solid {{ $accentColor }}40;">
                            <i class="bi bi-cash-coin"></i>
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <hr class="order-divider">

                {{-- ── BODY CARD ────────────────────────────────── --}}
                <div class="px-4 py-3">

                    {{-- Detail pesanan --}}
                    <div class="mb-3">
                        <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">
                            Detail Pesanan
                        </div>
                        <div class="fw-semibold" style="color:var(--warna-gelap);font-size:.95rem;">
                            {{ $order->detail_pesanan }}
                        </div>
                        @if($order->item_type === 'jasa' && $order->estimasi_harga > 0)
                            <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                                <span class="info-chip"
                                      style="background:#fff8f0;color:#c05621;border:1px solid #fed7aa;">
                                    <i class="bi bi-calculator"></i>
                                    Estimasi: Rp {{ number_format($order->estimasi_harga, 0, ',', '.') }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Harga final dikonfirmasi admin via WhatsApp.
                                </small>
                            </div>
                        @endif
                    </div>

                    {{-- Catatan verifikasi (jika ada) --}}
                    @if($order->catatan_pembayaran)
                        @php
                            $catatanBg    = $ps === 'ditolak' ? '#fff5f5' : ($ps === 'menunggu_persetujuan_batal' ? '#fce7f3' : '#f0f9ff');
                            $catatanBorder= $ps === 'ditolak' ? '#fecaca' : ($ps === 'menunggu_persetujuan_batal' ? '#fbcfe8' : '#bae6fd');
                            $catatanIcon  = $ps === 'ditolak' ? 'bi-exclamation-triangle-fill text-danger' : ($ps === 'menunggu_persetujuan_batal' ? 'bi-clock-history text-danger' : 'bi-chat-left-text-fill text-info');
                            $catatanText  = $ps === 'ditolak' ? '#991b1b' : ($ps === 'menunggu_persetujuan_batal' ? '#9d174d' : '#0369a1');
                        @endphp
                        <div class="mb-3 p-3 rounded-3 d-flex align-items-start gap-2"
                             style="background:{{ $catatanBg }};border:1px solid {{ $catatanBorder }};">
                            <i class="bi {{ $catatanIcon }} flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                            <div style="font-size:.88rem;color:{{ $catatanText }};">
                                @if($ps === 'ditolak') <strong>Alasan Penolakan:</strong><br> @endif
                                {{ $order->catatan_pembayaran }}
                            </div>
                        </div>
                    @endif

                    {{-- Alasan permintaan batal pelanggan (jika ada) --}}
                    @if($order->cancellation_reason && $ps === 'menunggu_persetujuan_batal')
                        <div class="mb-3 p-3 rounded-3 d-flex align-items-start gap-2"
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

                <hr class="order-divider">

                {{-- ── FOOTER CARD: Bukti & Aksi ───────────────── --}}
                <div class="px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
                     style="background:#fafbff;border-radius:0 0 18px 18px;">

                    {{-- Kiri: thumbnail bukti atau placeholder --}}
                    <div class="d-flex align-items-center gap-3">
                        @if($order->bukti_bayar)
                            <a href="{{ route('customer.orders.bukti', $order->id) }}" target="_blank">
                                <img src="{{ route('customer.orders.bukti', $order->id) }}"
                                     alt="Bukti pembayaran"
                                     class="bukti-thumb">
                            </a>
                            <div>
                                <div class="fw-semibold" style="font-size:.85rem;color:var(--warna-gelap);">
                                    Bukti Pembayaran
                                </div>
                                <a href="{{ route('customer.orders.bukti', $order->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-1"
                                   style="font-size:.78rem;">
                                    <i class="bi bi-eye me-1"></i>Lihat Bukti
                                </a>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                 style="width:80px;height:80px;background:#f1f5f9;border:2px dashed #cbd5e1;">
                                <i class="bi bi-image text-muted" style="font-size:1.5rem;opacity:.4;"></i>
                            </div>
                            <div class="text-muted" style="font-size:.83rem;">
                                Belum ada bukti pembayaran
                            </div>
                        @endif
                    </div>

                    {{-- Kanan: upload form (hanya untuk status ditolak) --}}
                    @if($ps === 'ditolak')
                        <form action="{{ route('customer.orders.upload-bukti', $order->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="upload-zone"
                              style="min-width: 240px; flex: 1; max-width: 360px;">
                            @csrf
                            <label class="form-label fw-semibold mb-2" style="font-size:.85rem;">
                                <i class="bi bi-upload me-1 text-primary"></i>Upload Ulang Bukti Pembayaran
                            </label>
                            <input type="file"
                                   name="bukti_bayar"
                                   class="form-control form-control-sm mb-2 @error('bukti_bayar') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/jpg"
                                   required>
                            @error('bukti_bayar')
                                <div class="invalid-feedback d-block" style="font-size:.8rem;">{{ $message }}</div>
                            @enderror
                            <button type="submit"
                                    class="btn btn-primary btn-sm rounded-pill px-4 w-100 fw-semibold">
                                <i class="bi bi-send me-1"></i>Kirim Bukti
                            </button>
                        </form>

                    @elseif($ps === 'menunggu_konfirmasi')
                        <div class="d-flex flex-column gap-2" style="min-width:200px;">
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                 style="background:#fffbeb;border:1px solid #fde68a;font-size:.82rem;color:#92400e;">
                                <i class="bi bi-hourglass-split flex-shrink-0"></i>
                                Menunggu verifikasi admin
                            </div>
                            {{-- Tombol ajukan pembatalan --}}
                            <button type="button"
                                    class="btn btn-sm rounded-pill px-3 fw-semibold"
                                    style="background:#fce7f3;color:#9d174d;border:1px solid #fbcfe8;font-size:.8rem;"
                                    onclick="bukaModalBatalPelanggan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                <i class="bi bi-x-circle me-1"></i>Ajukan Pembatalan
                            </button>
                        </div>

                    @elseif($ps === 'menunggu_persetujuan_batal')
                        <div class="d-flex flex-column gap-2" style="min-width:200px;">
                            <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                                 style="background:#fce7f3;border:1px solid #fbcfe8;font-size:.82rem;color:#9d174d;">
                                <i class="bi bi-clock-history flex-shrink-0"></i>
                                Permintaan batal menunggu persetujuan admin
                            </div>
                            @php
                                $adminWa = '6285273300045'; // nomor WA admin
                                $waMsg = urlencode('Halo Admin, saya ingin menindaklanjuti permintaan pembatalan pesanan ' . ($order->order_number ?? '#'.$order->id) . '. Mohon informasi proses refundnya. Terima kasih.');
                            @endphp
                            <a href="https://api.whatsapp.com/send?phone={{ $adminWa }}&text={{ $waMsg }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-success rounded-pill px-3"
                               style="font-size:.8rem;">
                                <i class="bi bi-whatsapp me-1"></i>Hubungi Admin untuk Refund
                            </a>
                        </div>

                    @elseif($ps === 'lunas')
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                             style="background:#ecfdf5;border:1px solid #a7f3d0;font-size:.82rem;color:#065f46;">
                            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                            Pembayaran telah dikonfirmasi
                        </div>
                    @endif

                </div>

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

@endsection
