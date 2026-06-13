@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Edit Produk ATK</h2>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Produk ATK</label>
                            <input
                                type="text"
                                name="nama_produk"
                                class="form-control"
                                required
                                value="{{ old('nama_produk', $produk->name_produk) }}"
                                placeholder="Contoh: Buku Tulis Sidu"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Pilih kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input
                                    type="number"
                                    name="harga"
                                    class="form-control"
                                    required
                                    value="{{ old('harga', $produk->harga) }}"
                                    placeholder="Contoh: 5000"
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input
                                    type="number"
                                    name="stok"
                                    class="form-control"
                                    required
                                    value="{{ old('stok', $produk->stok) }}"
                                    placeholder="Contoh: 20"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto/Gambar Produk (Opsional)</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                            <small class="text-muted d-block mt-2">
                                Gambar saat ini: {{ $produk->gambar ?: 'Belum ada gambar' }}
                                (Kosongkan jika tidak ingin mengubah gambar)
                            </small>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Produk ATK
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection