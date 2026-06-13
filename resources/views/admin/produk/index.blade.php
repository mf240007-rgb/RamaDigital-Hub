@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Produk ATK</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.produk.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="bi bi-tags me-1"></i> + Kelola Kategori
            </button>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <form action="{{ route('admin.produk.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama produk ATK..." value="{{ $keyword ?? '' }}">
                <button type="submit" class="btn btn-primary d-flex align-items-center">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
                @if(request()->has('search'))
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div> {{-- PERBAIKAN: Penutup tag row pencarian --}}

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
        </div>
        <div>
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%" class="text-center">Gambar</th>
                            <th>Nama Produk</th>
                            <th width="16%">Kategori</th>
                            <th width="15%" class="text-end">Harga</th>
                            <th width="10%" class="text-center">Stok</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            
                            <td class="text-center">
                                @if($p->gambar && file_exists(public_path('images/produk/' . $p->gambar)))
                                    <img src="{{ asset('images/produk/' . $p->gambar) }}" alt="{{ $p->name_produk }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>

                            <td>{{ $p->name_produk }}</td>

                            <td>
                                {{ $p->category?->name ?? '-' }}
                            </td>
                            
                            <td class="text-end">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            
                            <td class="text-center">{{ $p->stok }} Pcs</td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.produk.edit', $p->id) }}" class="btn btn-warning btn-sm align-items-center d-flex">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm align-items-center d-flex">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> {{-- Penutup container-fluid --}}

<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel"><i class="bi bi-tags-fill me-2"></i>Kelola Kategori Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="mb-4">
                    @csrf
                    <label class="form-label fw-bold">Tambah Kategori Baru</label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Alat Tulis, Kertas" required>
                        <button type="submit" class="btn btn-success">Simpan Kategori</button>
                    </div>
                </form>

                <hr>

                <label class="form-label fw-bold mb-2">Daftar Kategori Saat Ini</label>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-sm table-bordered mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kategori</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $cat)
                                    <tr>
                                        <td class="px-2">{{ $cat->name }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk terkait akan kehilangan kategorinya.');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger py-0 px-2">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted small py-3">Belum ada kategori yang ditambahkan.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection