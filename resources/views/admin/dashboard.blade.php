@extends('layouts.admin')

@section('content')
    {{-- Top Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--warna-gelap);">Dashboard Admin</h4>
            <small class="text-muted">{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-4 text-secondary"></i>
            <span class="fw-semibold" style="color: var(--warna-gelap);">{{ session('admin_username', 'Admin') }}</span>
        </div>
    </div>

    {{-- Welcome Alert --}}
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
        <i class="bi bi-check-circle-fill flex-shrink-0 me-3 fs-3 text-success"></i>
        <div>
            <h5 class="alert-heading mb-1">Selamat Datang Admin!</h5>
            <p class="mb-0">Kamu berhasil masuk ke Panel Admin <strong>RamaDigital Hub</strong>. Gunakan menu di sebelah kiri untuk mengelola toko.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-box-seam-fill"></i></div>
                    <div>
                        <div class="text-muted small">Total Produk ATK</div>
                        <div class="fw-bold fs-4">48 item</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-printer-fill"></i></div>
                    <div>
                        <div class="text-muted small">Pesanan Masuk</div>
                        <div class="fw-bold fs-4">12 pesanan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-check2-circle"></i></div>
                    <div>
                        <div class="text-muted small">Pesanan Selesai</div>
                        <div class="fw-bold fs-4">89 pesanan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-currency-dollar"></i></div>
                    <div>
                        <div class="text-muted small">Pendapatan Bulan Ini</div>
                        <div class="fw-bold fs-4">Rp 2,4 Jt</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent orders table (static sample) --}}
    <div class="card" style="border: none; border-radius: 14px; box-shadow: 0 3px 15px rgba(0,0,0,0.07);">
        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="border-radius: 14px 14px 0 0; border-bottom: 1px solid #f0f0f0; padding: 18px 22px;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);"><i class="bi bi-clock-history me-2"></i> Pesanan Cetak Terbaru</h6>
            <span class="badge bg-primary rounded-pill">Live Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3">No. Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Jenis Cetak</th>
                            <th>Tgl. Masuk</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 fw-semibold">RDH-001</td>
                            <td>Budi Santoso</td>
                            <td>HVS A4, 10 lembar, BW</td>
                            <td>15 Agt 2024</td>
                            <td><span class="badge bg-warning text-dark">Diproses</span></td>
                            <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-semibold">RDH-002</td>
                            <td>Siti Aminah</td>
                            <td>Foto Glossy, 5 lembar, Color</td>
                            <td>15 Agt 2024</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-semibold">RDH-003</td>
                            <td>Rizky Maulana</td>
                            <td>Art Paper, 50 lembar, Color</td>
                            <td>14 Agt 2024</td>
                            <td><span class="badge bg-info text-dark">Menunggu</span></td>
                            <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                        </tr>
                        <tr>
                            <td class="ps-4 fw-semibold">RDH-004</td>
                            <td>Dewi Rahayu</td>
                            <td>Sticker Vinyl, 2 lembar, Color</td>
                            <td>13 Agt 2024</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection