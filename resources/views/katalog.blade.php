@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark">Katalog Produk ATK</h1>
        <p class="text-muted">Temukan berbagai kebutuhan alat tulis kantor berkualitas tinggi di RamaDigital Hub</p>
    </div>

    <form action="{{ route('katalog.index') }}" method="GET" class="row g-2 justify-content-center mb-4">
        <div class="col-lg-6 col-md-8">
            <div class="input-group shadow-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari nama produk atau kategori..." value="{{ $search ?? request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </div>
        @if(request('search') || request('category'))
            <div class="col-12 text-center">
                <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Reset Filter</a>
            </div>
        @endif
    </form>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="{{ route('katalog.index', ['search' => request('search')]) }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 shadow-sm">
            Semua Produk
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('katalog.index', ['category' => $cat->name, 'search' => request('search')]) }}" class="btn {{ request('category') == $cat->name ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 shadow-sm">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="row g-4">
        @forelse($products as $p)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 product-card p-2 position-relative bg-white">
                    
                    <span class="position-absolute top-0 start-0 m-3 badge bg-primary text-white px-3 py-1 rounded-pill small" style="z-index: 10;">
                        {{ $p->category?->name ?? 'Umum' }}
                    </span>

                    <div class="text-center p-3" style="background: #f8f9fa; border-radius: 8px; height: 180px; display: flex; align-items: center; justify-content: center;">
                        @if($p->gambar && file_exists(public_path('images/produk/' . $p->gambar)))
                            <img src="{{ asset('images/produk/' . $p->gambar) }}" alt="{{ $p->name_produk }}" class="img-fluid" style="height: 160px; object-fit: contain; width: 100%;">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 160px;">
                                <i class="bi bi-image" style="font-size: 3rem; opacity: 0.3;"></i>
                                <small class="mt-1 opacity-50">Tidak ada gambar</small>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between pt-3 px-2 pb-1">
                        <div>
                            <h5 class="card-title h6 fw-bold text-dark mb-1 text-truncate" title="{{ $p->name_produk }}">
                                {{ $p->name_produk }}
                            </h5>
                            <p class="harga mb-2">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                @if($p->stok <= 5)
                                    <span class="badge bg-warning text-dark stock-badge">Stok Menipis: {{ $p->stok }} pcs</span>
                                @else
                                    <span class="badge bg-success-subtle text-success stock-badge">Stok: {{ $p->stok }} pcs</span>
                                @endif
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">
                                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted fs-3 mb-2"><i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary"></i> Produk Tidak Tersedia</div>
                <p class="text-muted small">
                    @if(request('search'))
                        Tidak ada produk yang cocok dengan kata kunci "{{ request('search') }}".
                    @elseif(request('category'))
                        Belum ada data produk untuk kategori "{{ request('category') }}".
                    @else
                        Belum ada data produk yang tersedia.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection