@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4 page-header-row">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">
                <i class="bi bi-credit-card me-2 text-primary"></i>Checkout
            </h2>
            <small class="text-muted">Selesaikan pembayaran untuk memproses pesananmu</small>
        </div>
        <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius: 12px;">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <div>
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">

            {{-- ====== KIRI: Ringkasan + Upload ====== --}}
            <div class="col-lg-6">

                {{-- Ringkasan Produk --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-box-seam me-2 text-primary"></i>Ringkasan Pesanan
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center gap-3 px-4 py-3 checkout-summary-item {{ !$loop->last ? 'border-bottom' : '' }}"
                             style="border-color: #f0f0f0 !important;">
                            <div class="rounded-2 flex-shrink-0 overflow-hidden"
                                 style="width: 44px; height: 44px; background: #f0f4f8;">
                                @if($item['product']->gambar && file_exists(public_path('images/produk/' . $item['product']->gambar)))
                                    <img src="{{ asset('images/produk/' . $item['product']->gambar) }}"
                                         style="width:44px;height:44px;object-fit:cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size:1.1rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-semibold text-truncate" style="font-size: 0.88rem;">{{ $item['product']->name_produk }}</div>
                                <small class="text-muted">Rp {{ number_format($item['product']->harga, 0, ',', '.') }} × {{ $item['quantity'] }}</small>
                            </div>
                            <div class="fw-bold flex-shrink-0" style="color: var(--warna-aksen); font-size: 0.9rem;">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- Total — hanya satu di sini --}}
                    <div class="card-footer bg-white px-4 py-3"
                         style="border-top: 2px solid #f0f0f0; border-radius: 0 0 16px 16px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total yang Harus Dibayar</span>
                            <span class="fw-bold fs-5" style="color: var(--warna-aksen);">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Upload Bukti Bayar --}}
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-cloud-upload me-2 text-primary"></i>Upload Bukti Pembayaran
                        </h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <p class="text-muted mb-3" style="font-size: 0.88rem;">
                            Setelah transfer, upload screenshot bukti pembayaran di sini atau lewat menu <strong>Pesanan Saya</strong> setelah pesanan tersimpan.
                        </p>
                        <input type="file" name="bukti_bayar" id="bukti_bayar"
                               class="form-control @error('bukti_bayar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg"
                               onchange="previewBukti(this)">
                        @error('bukti_bayar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="preview-container" class="mt-3 d-none text-center">
                            <img id="preview-img" src="" alt="Preview"
                                 class="rounded-2 shadow-sm"
                                 style="max-width: 100%; max-height: 180px; object-fit: contain;">
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Jika bukti belum diupload sekarang, pesanan tetap tersimpan sebagai <strong>Belum Bayar</strong>.
                        </div>
                    </div>
                </div>

            </div>

            {{-- ====== KANAN: QRIS ====== --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm checkout-sticky" style="border-radius: 16px; position: sticky; top: 20px;">

                    {{-- Header --}}
                    <div class="card-header border-0 text-center py-3"
                         style="border-radius: 16px 16px 0 0; background: linear-gradient(135deg, #1a73e8, #4a9eff); color: white;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-qr-code me-2"></i>Bayar via QRIS</h5>
                        <small style="opacity:0.85;">Toko Rama — BRI | NMID: ID1025408811376</small>
                    </div>

                    <div class="card-body text-center px-4 py-3">

                        {{-- QRIS Image --}}
                        @php
                            $qrisFile = null;
                            if (file_exists(public_path('images/qris.jpg')))       $qrisFile = asset('images/qris.jpg');
                            elseif (file_exists(public_path('images/qris.jpeg')))  $qrisFile = asset('images/qris.jpeg');
                            elseif (file_exists(public_path('images/qris.png')))   $qrisFile = asset('images/qris.png');
                        @endphp

                        @if($qrisFile)
                            <img src="{{ $qrisFile }}"
                                 alt="QRIS Toko Rama"
                                 class="img-fluid rounded-3 mb-2 shadow-sm"
                                 style="max-width: 240px; border: 1px solid #e2e8f0;">
                            <div class="mb-3">
                                <a href="{{ $qrisFile }}"
                                   download="QRIS-RamaDigital-Hub"
                                   class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bi bi-download me-1"></i>Download QRIS
                                </a>
                            </div>
                        @else
                            {{-- Placeholder saat QRIS belum diunggah --}}
                            <div class="rounded-3 mx-auto mb-3 d-flex flex-column align-items-center justify-content-center"
                                 style="width:220px;height:220px;background:#f8faff;border:2px dashed #bee5eb;">
                                <i class="bi bi-qr-code" style="font-size:4rem;color:#1a73e8;opacity:0.35;"></i>
                                <small class="text-muted mt-2" style="font-size:0.75rem;">
                                    Simpan file <code>qris.jpg</code><br>ke folder <code>public/images/</code>
                                </small>
                            </div>
                        @endif

                        {{-- Info rekening transfer --}}
                        <div class="rounded-3 py-2 px-3 mb-3 text-start"
                             style="background:#fff8f0;border:1px solid #fed7aa;font-size:0.82rem;">
                            <div class="fw-semibold text-warning mb-1">
                                <i class="bi bi-bank me-1"></i>Transfer Rekening BRI
                            </div>
                            <div class="fw-bold" style="font-size:1rem;letter-spacing:0.5px;">3286-01-053842-53-3</div>
                            <div class="text-muted">a.n. Apriati</div>
                        </div>

                        {{-- Langkah --}}
                        <div class="text-start mb-3" style="font-size:0.82rem;color:#6c757d;">
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
                                <span>Upload screenshot bukti di form sebelah kiri</span>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-4-circle-fill text-primary flex-shrink-0 mt-1"></i>
                                <span>Klik tombol <strong>Kirim Pesanan</strong></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill"
                                style="background: linear-gradient(135deg, var(--warna-utama), #4a9eff); border: none;">
                            <i class="bi bi-send-fill me-2"></i>Kirim Pesanan
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function previewBukti(input) {
    const container = document.getElementById('preview-container');
    const img       = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            container.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
