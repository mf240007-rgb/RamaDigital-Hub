@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 860px;">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 page-header-row">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">
                <i class="bi bi-cart3 me-2 text-primary"></i>Keranjang Belanja
            </h2>
            <small class="text-muted">
                @if(!empty($products) && count($products) > 0)
                    {{ count($products) }} produk dalam keranjang
                @else
                    Keranjang kamu masih kosong
                @endif
            </small>
        </div>
        <a href="{{ route('katalog.index') }}"
           class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Lanjut Belanja
        </a>
    </div>

    @if(empty($products) || count($products) === 0)
        {{-- Empty State --}}
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 16px;">
            <div class="card-body py-5">
                <i class="bi bi-cart-x" style="font-size: 4rem; color: #d0d7e3;"></i>
                <h5 class="fw-bold mt-3 mb-2" style="color: var(--warna-gelap);">Keranjang Kosong</h5>
                <p class="text-muted mb-4">Kamu belum menambahkan produk apapun ke keranjang.</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-bag-plus me-2"></i>Mulai Belanja
                </a>
            </div>
        </div>

    @else
        <div class="row g-4 align-items-start">

            {{-- Kolom Kiri: Daftar Produk --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-box-seam me-2 text-primary"></i>Produk Dipilih
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($products as $p)
                           <div class="d-flex align-items-center gap-3 px-4 py-3 cart-item-row
                                    {{ !$loop->last ? 'border-bottom' : '' }}"
                             style="border-color: #f0f0f0 !important;">

                            {{-- Gambar Produk --}}
                            <div class="rounded-2 flex-shrink-0 d-flex align-items-center justify-content-center overflow-hidden"
                                 style="width: 64px; height: 64px; background: #f0f4f8;">
                                @if($p['product']->gambar && file_exists(public_path('images/produk/' . $p['product']->gambar)))
                                    <img src="{{ asset('images/produk/' . $p['product']->gambar) }}"
                                         alt="{{ $p['product']->name_produk }}"
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <i class="bi bi-image text-muted fs-3 opacity-50"></i>
                                @endif
                            </div>

                            {{-- Info Produk --}}
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fw-semibold text-truncate" style="color: var(--warna-gelap); max-width: 100%;">
                                    {{ $p['product']->name_produk }}
                                </div>
                                <div style="color: var(--warna-aksen); font-weight: 700; font-size: 0.95rem;">
                                    Rp {{ number_format($p['product']->harga, 0, ',', '.') }}
                                </div>
                            </div>

                            {{-- Form Update Jumlah --}}
                            <form method="POST" action="{{ route('cart.update') }}"
                                  class="d-flex align-items-center gap-2 flex-shrink-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p['product']->id }}">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" name="quantity"
                                           value="{{ $p['quantity'] }}" min="1"
                                           class="form-control text-center"
                                           style="border-radius: 8px 0 0 8px;">
                                    <button class="btn btn-outline-primary btn-sm" type="submit"
                                            style="border-radius: 0 8px 8px 0; font-size: 0.75rem;">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </div>
                            </form>

                            {{-- Subtotal --}}
                            <div class="text-end flex-shrink-0" style="min-width: 90px;">
                                <div class="fw-bold" style="color: var(--warna-gelap);">
                                    Rp {{ number_format($p['subtotal'], 0, ',', '.') }}
                                </div>
                                <small class="text-muted">subtotal</small>
                            </div>

                            {{-- Tombol Hapus --}}
                            <form method="POST" action="{{ route('cart.remove') }}" class="flex-shrink-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p['product']->id }}">
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 34px; height: 34px;"
                                        onclick="return confirm('Hapus {{ addslashes($p['product']->name_produk) }} dari keranjang?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-receipt me-2 text-primary"></i>Ringkasan Pesanan
                        </h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 0.9rem;">
                            <span>{{ count($products) }} produk</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold mb-4" style="font-size: 1.05rem; color: var(--warna-gelap);">
                            <span>Total</span>
                            <span style="color: var(--warna-aksen);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <button class="btn btn-primary w-100 rounded-pill fw-semibold py-2"
                                style="background: linear-gradient(135deg, var(--warna-utama), #4a9eff); border: none;"
                                onclick="window.location='{{ route('checkout.index') }}'">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </button>
                        <a href="{{ route('katalog.index') }}"
                           class="btn btn-outline-secondary w-100 rounded-pill mt-2">
                            <i class="bi bi-bag me-1"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
