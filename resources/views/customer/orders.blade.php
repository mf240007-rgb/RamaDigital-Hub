@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 1180px;">
    <div class="d-flex justify-content-between align-items-start mb-4 page-header-row flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">
                <i class="bi bi-receipt me-2 text-primary"></i>Pesanan Saya
            </h2>
            <small class="text-muted">Pantau status pembayaran dan unggah bukti kapan saja.</small>
        </div>
        <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-bag-plus me-1"></i>Tambah Belanja
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-5 text-center">
                <i class="bi bi-inbox display-4 text-muted opacity-25"></i>
                <h5 class="mt-3 mb-2">Belum ada pesanan ATK</h5>
                <p class="text-muted mb-0">Setelah checkout, pesanan dan status pembayaran akan muncul di halaman ini.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($orders as $order)
                @php
                    $ps = $order->payment_status ?? 'ditolak';
                    $payStyle = $order->paymentBadge();
                @endphp
                <div class="col-12">
                    <div class="card border-0 shadow-sm order-card" style="border-radius: 18px; overflow: hidden;">
                        <div class="card-header bg-white px-4 py-3 border-0" style="border-bottom: 1px solid #eef2f7;">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <div class="fw-bold" style="color: var(--warna-gelap); font-family: monospace;">
                                        {{ $order->order_number ?? 'RDH-'.$order->id }}
                                    </div>
                                    <small class="text-muted">Dibuat {{ $order->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="badge rounded-pill px-3 py-2" style="background: {{ $payStyle['bg'] }}; color: {{ $payStyle['text'] }}; font-size: 0.82rem;">
                                        <i class="bi {{ $payStyle['icon'] }} me-1"></i>{{ $payStyle['label'] }}
                                    </span>
                                    <small class="text-muted">Status pesanan: <strong>{{ $order->status }}</strong></small>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-8 p-4 border-lg-end" style="border-color: #eef2f7 !important;">
                                    <div class="mb-3">
                                        <div class="text-muted small mb-1">Detail Pesanan</div>
                                        <div class="fw-semibold">{{ $order->detail_pesanan }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted small mb-1">Total Pembayaran</div>
                                        <div class="fw-bold fs-5" style="color: var(--warna-aksen);">
                                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted small mb-1">Catatan Verifikasi</div>
                                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                                            {{ $order->catatan_pembayaran ?: 'Belum ada catatan.' }}
                                        </div>
                                    </div>

                                    @if($order->bukti_bayar)
                                        <div class="mb-3">
                                            <div class="text-muted small mb-1">Bukti Pembayaran</div>
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <a href="{{ route('customer.orders.bukti', $order->id) }}" target="_blank" class="d-inline-block">
                                                    <img src="{{ route('customer.orders.bukti', $order->id) }}"
                                                         alt="Bukti pembayaran"
                                                         class="rounded-3 shadow-sm"
                                                         style="width: 120px; height: 120px; object-fit: cover; border: 1px solid #e5e7eb;">
                                                </a>
                                                <div>
                                                    <a href="{{ route('customer.orders.bukti', $order->id) }}" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3">
                                                        <i class="bi bi-eye me-1"></i>Lihat Bukti
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-4 p-4" style="background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);">
                                    <div class="mb-3">
                                        <div class="text-muted small mb-1">Status Pembayaran</div>
                                        <div class="fw-semibold" style="color: {{ $payStyle['text'] }};">
                                            <i class="bi {{ $payStyle['icon'] }} me-1"></i>{{ $payStyle['label'] }}
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-muted small mb-1">Pembaruan Terakhir</div>
                                        <div>{{ $order->updated_at->format('d M Y H:i') }}</div>
                                    </div>

                                    @if($ps === 'ditolak')
                                        <form action="{{ route('customer.orders.upload-bukti', $order->id) }}" method="POST" enctype="multipart/form-data" class="p-3 rounded-4" style="background: #fff; border: 1px dashed #cbd5e1;">
                                            @csrf
                                            <label class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                                            <input type="file"
                                                   name="bukti_bayar"
                                                   class="form-control form-control-sm mb-2 @error('bukti_bayar') is-invalid @enderror"
                                                   accept="image/jpeg,image/png,image/jpg"
                                                   required>
                                            @error('bukti_bayar')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
                                                <i class="bi bi-upload me-1"></i>Kirimi Bukti
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-success mb-0" style="border-radius: 14px;">
                                            Bukti sudah diterima dan menunggu proses verifikasi admin.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
