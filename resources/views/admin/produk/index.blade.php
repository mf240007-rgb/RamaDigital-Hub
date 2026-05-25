@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Produk ATK</h2>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
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

    <div class="d-flex justify-content-between align-items-center mt-3">
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
@endsection
