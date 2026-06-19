@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Tambah Produk ATK</h2>
                    <small class="text-muted">Isi semua informasi produk di bawah ini</small>
                </div>
                <a href="{{ route('admin.produk.index') }}"
                   class="btn btn-outline-secondary d-flex align-items-center gap-1 rounded-pill px-3">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4"
                     role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #dc3545;">
                    <i class="bi bi-exclamation-circle-fill text-danger flex-shrink-0"></i>
                    <div>
                        <strong>Mohon periksa kembali:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Card: Informasi Dasar --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 pt-4 pb-3 border-0">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-info-circle-fill me-2 text-primary"></i>Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body px-4 pb-4 pt-1">

                        {{-- Nama Produk --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="nama_produk">
                                Nama Produk ATK <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="nama_produk"
                                   name="nama_produk"
                                   class="form-control @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk') }}"
                                   placeholder="Contoh: Buku Tulis Sidu 38"
                                   style="border-radius: 10px; padding: 12px 14px;"
                                   required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="category_id">Kategori</label>
                            <select name="category_id"
                                    id="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror"
                                    style="border-radius: 10px; padding: 12px 14px;">
                                <option value="">— Pilih kategori (opsional) —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga & Stok --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="harga">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                                    <span class="input-group-text bg-light border-end-0 fw-semibold text-muted">Rp</span>
                                    <input type="number"
                                           id="harga"
                                           name="harga"
                                           class="form-control border-start-0 @error('harga') is-invalid @enderror"
                                           value="{{ old('harga') }}"
                                           placeholder="5000"
                                           min="0"
                                           style="padding: 12px 14px;"
                                           required>
                                </div>
                                @error('harga')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="stok">
                                    Stok Awal <span class="text-danger">*</span>
                                </label>
                                <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                                    <input type="number"
                                           id="stok"
                                           name="stok"
                                           class="form-control border-end-0 @error('stok') is-invalid @enderror"
                                           value="{{ old('stok') }}"
                                           placeholder="20"
                                           min="0"
                                           style="padding: 12px 14px;"
                                           required>
                                    <span class="input-group-text bg-light border-start-0 fw-semibold text-muted">Pcs</span>
                                </div>
                                @error('stok')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Card: Upload Gambar --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 pt-4 pb-3 border-0">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-image-fill me-2 text-primary"></i>Foto Produk
                            <span class="text-muted fw-normal">(Opsional)</span>
                        </h6>
                    </div>
                    <div class="card-body px-4 pb-4 pt-1">

                        {{-- Preview Area --}}
                        <div id="imagePreviewArea"
                             class="rounded-3 border-2 border d-flex flex-column align-items-center justify-content-center mb-3 position-relative"
                             style="height: 180px; border-style: dashed !important; border-color: #d0d7e3 !important;
                                    background: #f8faff; cursor: pointer; transition: all 0.2s;"
                             onclick="document.getElementById('gambar').click()"
                             ondragover="event.preventDefault(); this.style.borderColor='#1a73e8'"
                             ondragleave="this.style.borderColor='#d0d7e3'"
                             ondrop="handleDrop(event)">
                            <div id="previewPlaceholder" class="text-center">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted opacity-50 d-block mb-2"></i>
                                <span class="text-muted">Klik atau drag & drop gambar ke sini</span>
                                <small class="text-muted d-block mt-1">JPG, JPEG, PNG — Maks. 2MB</small>
                            </div>
                            <img id="imagePreview" src="" alt="Preview"
                                 class="rounded-2 d-none"
                                 style="max-height: 160px; max-width: 100%; object-fit: contain;">
                        </div>

                        <input type="file"
                               id="gambar"
                               name="gambar"
                               class="d-none @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg"
                               onchange="previewImage(event)">
                        @error('gambar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <button type="button"
                                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                onclick="document.getElementById('gambar').click()">
                            <i class="bi bi-folder2-open me-1"></i>Pilih File
                        </button>
                        <span id="fileNameLabel" class="text-muted small ms-2">Belum ada file dipilih</span>

                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.produk.index') }}"
                       class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                    <button type="submit"
                            class="btn btn-success rounded-pill px-5 fw-semibold d-flex align-items-center gap-2"
                            style="background: linear-gradient(135deg, #10b981, #34d399); border: none;">
                        <i class="bi bi-save2-fill"></i> Simpan Produk
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file  = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
            document.getElementById('previewPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(file);
        document.getElementById('fileNameLabel').textContent = file.name;
    }

    function handleDrop(event) {
        event.preventDefault();
        document.getElementById('imagePreviewArea').style.borderColor = '#d0d7e3';
        const file = event.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            // Assign ke input file
            const dt = new DataTransfer();
            dt.items.add(file);
            const input = document.getElementById('gambar');
            input.files = dt.files;
            previewImage({ target: input });
        }
    }
</script>

@endsection
