@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 860px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
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

            {{-- Kiri: Ringkasan Pesanan --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-box-seam me-2 text-primary"></i>Ringkasan Pesanan
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($items as $item)
                        <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                             style="border-color: #f0f0f0 !important;">
                            <div class="rounded-2 flex-shrink-0 overflow-hidden"
                                 style="width: 52px; height: 52px; background: #f0f4f8;">
                                @if($item['product']->gambar && file_exists(public_path('images/produk/' . $item['product']->gambar)))
                                    <img src="{{ asset('images/produk/' . $item['product']->gambar) }}"
                                         style="width:52px;height:52px;object-fit:cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size: 0.9rem;">{{ $item['product']->name_produk }}</div>
                                <small class="text-muted">Rp {{ number_format($item['product']->harga, 0, ',', '.') }} × {{ $item['quantity'] }}</small>
                            </div>
                            <div class="fw-bold" style="color: var(--warna-aksen);">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white px-4 py-3 border-0"
                         style="border-top: 2px solid #f0f0f0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-6">Total Pembayaran</span>
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
                            Setelah transfer, upload screenshot bukti pembayaran (JPG/PNG, maks 5 MB).
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
                                 style="max-width: 100%; max-height: 200px; object-fit: contain;">
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Admin akan memverifikasi pembayaran dan menghubungi kamu via WhatsApp.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: QRIS --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; position: sticky; top: 20px;">
                    <div class="card-header border-0 text-center py-4"
                         style="border-radius: 16px 16px 0 0; background: linear-gradient(135deg, #1a73e8, #4a9eff); color: white;">
                        <i class="bi bi-qr-code fs-1 mb-2 d-block"></i>
                        <h5 class="fw-bold mb-0">Bayar via QRIS</h5>
                        <small class="opacity-85">Scan kode QR dengan aplikasi apapun</small>
                    </div>
                    <div class="card-body text-center px-4 py-4">

                        {{-- QRIS Placeholder — ganti dengan gambar QRIS asli --}}
                        <div class="border rounded-3 p-3 mb-3 d-inline-block"
                             style="background: #fff; border-color: #dee2e6 !important;">
                            <div style="width: 200px; height: 200px; background: #f8faff;
                                        display: flex; flex-direction: column;
                                        align-items: center; justify-content: center;
                                        border-radius: 8px; border: 2px dashed #dee2e6;">
                                <i class="bi bi-qr-code" style="font-size: 5rem; color: #1a73e8; opacity: 0.4;"></i>
                                <small class="text-muted mt-2">QRIS RamaDigital Hub</small>
                                <small class="text-muted" style="font-size: 0.7rem;">(Ganti dengan gambar QRIS asli)</small>
                            </div>
                        </div>

                        <div class="alert border-0 py-2 mb-3"
                             style="background: #f0f9ff; border-radius: 10px; font-size: 0.85rem;">
                            <div class="fw-semibold text-primary mb-1">Nominal Transfer</div>
                            <div class="fw-bold fs-5" style="color: var(--warna-aksen);">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="text-start mb-3" style="font-size: 0.82rem; color: #6c757d;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-1-circle-fill text-primary"></i>
                                <span>Buka aplikasi m-banking / dompet digital</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-2-circle-fill text-primary"></i>
                                <span>Scan QRIS di atas</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-3-circle-fill text-primary"></i>
                                <span>Masukkan nominal <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-4-circle-fill text-primary"></i>
                                <span>Upload screenshot bukti di form sebelah kiri</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill"
                                style="background: linear-gradient(135deg, var(--warna-utama), #4a9eff); border: none;">
                            <i class="bi bi-send-fill me-2"></i>Konfirmasi & Kirim Pesanan
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
