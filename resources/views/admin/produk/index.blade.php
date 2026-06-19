@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Kelola Produk ATK</h2>
            <small class="text-muted">Manajemen data produk alat tulis kantor</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.produk.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
            <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="bi bi-tags"></i> Kelola Kategori
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
            <i class="bi bi-check-circle-fill fs-5 text-success flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #dc3545;">
            <i class="bi bi-exclamation-circle-fill fs-5 text-danger flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('admin.produk.index') }}" method="GET">
                <div class="d-flex gap-2 flex-wrap">
                    <div class="input-group" style="max-width: 420px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                               placeholder="Cari nama produk ATK..."
                               value="{{ $keyword ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Cari</button>
                    @if(request()->has('search') && request('search') !== '')
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
            <span class="text-muted" style="font-size: 0.9rem;">
                Menampilkan <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong>
                dari <strong>{{ $products->total() }}</strong> produk
            </span>
            <span class="badge rounded-pill px-3 py-2"
                  style="background: linear-gradient(135deg, #1a73e8, #4a9eff); font-size: 0.75rem;">
                {{ $products->total() }} Total
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8faff;">
                        <tr style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d;">
                            <th class="ps-4 py-3 fw-semibold text-center align-middle" style="width: 5%;">No</th>
                            <th class="fw-semibold text-center align-middle" style="width: 9%;">Gambar</th>
                            <th class="fw-semibold align-middle">Nama Produk</th>
                            <th class="fw-semibold align-middle" style="width: 14%;">Kategori</th>
                            <th class="fw-semibold text-end align-middle" style="width: 13%;">Harga</th>
                            <th class="fw-semibold text-center align-middle" style="width: 10%;">Stok</th>
                            <th class="fw-semibold text-center align-middle" style="width: 18%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            {{-- Nomor --}}
                            <td class="ps-4 text-center text-muted" style="font-size: 0.9rem;">
                                {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                            </td>

                            {{-- Gambar --}}
                            <td class="text-center">
                                @if($p->gambar && file_exists(public_path('images/produk/' . $p->gambar)))
                                    <img src="{{ asset('images/produk/' . $p->gambar) }}"
                                         alt="{{ $p->name_produk }}"
                                         class="rounded-2 shadow-sm"
                                         style="width: 52px; height: 52px; object-fit: cover;">
                                @else
                                    <div class="rounded-2 d-flex align-items-center justify-content-center mx-auto"
                                         style="width: 52px; height: 52px; background: #f0f4f8; color: #adb5bd;">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- Nama Produk --}}
                            <td>
                                <span class="fw-semibold" style="color: var(--warna-gelap);">{{ $p->name_produk }}</span>
                            </td>

                            {{-- Kategori --}}
                            <td>
                                @if($p->category)
                                    <span class="badge rounded-pill px-3 py-1"
                                          style="background: #eff6ff; color: #1e40af; font-size: 0.8rem; font-weight: 500;">
                                        {{ $p->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">—</span>
                                @endif
                            </td>

                            {{-- Harga --}}
                            <td class="text-end fw-semibold" style="color: var(--warna-gelap);">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </td>

                            {{-- Stok --}}
                            <td class="text-center">
                                @php $stokRendah = $p->stok <= 10; @endphp
                                <span class="badge rounded-pill px-3 py-2"
                                      style="font-size: 0.82rem;
                                             background: {{ $stokRendah ? '#fef2f2' : '#f0fdf4' }};
                                             color: {{ $stokRendah ? '#b91c1c' : '#15803d' }};">
                                    @if($stokRendah)<i class="bi bi-exclamation-triangle-fill me-1"></i>@endif
                                    {{ $p->stok }} Pcs
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.produk.edit', $p->id) }}"
                                       class="btn btn-sm btn-warning d-flex align-items-center gap-1 rounded-pill px-3">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger d-flex align-items-center gap-1 rounded-pill px-3"
                                            onclick="confirmDelete('{{ route('admin.produk.destroy', $p->id) }}', '{{ addslashes($p->name_produk) }}')">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                @if($keyword)
                                    Tidak ada produk yang cocok dengan "<strong>{{ $keyword }}</strong>"
                                @else
                                    Belum ada produk yang ditambahkan.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination di bawah tabel --}}
        @if($products->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end px-4 py-3"
             style="border-radius: 0 0 16px 16px; border-top: 1px solid #f0f0f0;">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-body text-center p-5">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 64px; height: 64px; background: #fef2f2;">
                        <i class="bi bi-trash-fill text-danger fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Hapus Produk?</h5>
                    <p class="text-muted mb-0">Produk <strong id="deleteProductName"></strong> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Kelola Kategori --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold" id="categoryModalLabel">
                    <i class="bi bi-tags-fill me-2 text-primary"></i>Kelola Kategori Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">

                {{-- Tambah Kategori --}}
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="mb-4">
                    @csrf
                    <label class="form-label fw-semibold">Tambah Kategori Baru</label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control"
                               placeholder="Contoh: Alat Tulis, Kertas" required>
                        <button type="submit" class="btn btn-success px-3">
                            <i class="bi bi-plus-lg me-1"></i>Simpan
                        </button>
                    </div>
                </form>

                <hr class="my-3">

                <label class="form-label fw-semibold mb-2">Daftar Kategori</label>
                <div style="max-height: 260px; overflow-y: auto;">
                    @if(isset($categories) && $categories->count() > 0)
                        @foreach($categories as $cat)
                        <div class="d-flex justify-content-between align-items-center py-2 px-3 mb-1 rounded-2"
                             style="background: #f8faff;">
                            <span class="fw-medium">{{ $cat->name }}</span>
                            <form action="{{ route('admin.kategori.destroy', $cat->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori ini? Produk terkait akan kehilangan kategorinya.')"
                                  class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-tags fs-2 d-block mb-2 opacity-25"></i>
                            Belum ada kategori yang ditambahkan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script konfirmasi hapus --}}
<script>
    function confirmDelete(actionUrl, productName) {
        document.getElementById('deleteProductName').textContent = productName;
        document.getElementById('deleteForm').action = actionUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>

@endsection
