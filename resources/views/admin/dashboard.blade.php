@extends('layouts.admin')

@section('content')

    {{-- Top Bar --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold mb-1" style="color: var(--warna-gelap);">Dashboard Admin</h1>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar3 text-muted"></i>
                <span class="text-muted" id="realtime-date" style="font-size: 1rem;">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>
        {{-- Jam digital realtime --}}
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white; min-width: 160px;">
            <i class="bi bi-clock text-warning"></i>
            <span id="realtime-clock" class="fw-bold fs-5 font-monospace">--:--:--</span>
        </div>
    </div>

    {{-- Welcome Alert --}}
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm"
         role="alert"
         style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
        <i class="bi bi-check-circle-fill flex-shrink-0 me-3 fs-3 text-success"></i>
        <div>
            <h5 class="alert-heading mb-1">Selamat Datang Admin!</h5>
            <p class="mb-0">Kamu berhasil masuk ke Panel Admin <strong>RamaDigital Hub</strong>. Gunakan menu di sebelah kiri untuk mengelola toko.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">
        {{-- Total Produk --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card border-0 h-100" style="border-radius:16px; background: linear-gradient(135deg, #1a73e8, #4a9eff); color: white;">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; background: rgba(255,255,255,0.2); font-size:1.5rem; flex-shrink:0;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Total Produk ATK</div>
                        <div class="fw-bold fs-3 lh-1 mt-1">48 item</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Pesanan Masuk --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card border-0 h-100" style="border-radius:16px; background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white;">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; background: rgba(255,255,255,0.2); font-size:1.5rem; flex-shrink:0;">
                        <i class="bi bi-printer-fill"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pesanan Masuk</div>
                        <div class="fw-bold fs-3 lh-1 mt-1">12 pesanan</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Pesanan Selesai --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card border-0 h-100" style="border-radius:16px; background: linear-gradient(135deg, #10b981, #34d399); color: white;">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; background: rgba(255,255,255,0.2); font-size:1.5rem; flex-shrink:0;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pesanan Selesai</div>
                        <div class="fw-bold fs-3 lh-1 mt-1">89 pesanan</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Pendapatan --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card border-0 h-100" style="border-radius:16px; background: linear-gradient(135deg, #ef4444, #f87171); color: white;">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px; height:52px; background: rgba(255,255,255,0.2); font-size:1.5rem; flex-shrink:0;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="small opacity-75">Pendapatan Bulan Ini</div>
                        <div class="fw-bold fs-3 lh-1 mt-1">Rp 2,4 Jt</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Pesanan Terbaru --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                <i class="bi bi-clock-history me-2 text-primary"></i>Pesanan Cetak Terbaru
            </h6>
            <span class="badge rounded-pill px-3 py-2"
                  style="background: linear-gradient(135deg, #1a73e8, #4a9eff); font-size: 0.75rem;">
                <i class="bi bi-broadcast me-1"></i>Live Data
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8faff;">
                        <tr style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6c757d;">
                            <th class="ps-4 py-3 fw-semibold">No. Pesanan</th>
                            <th class="fw-semibold">Nama Pelanggan</th>
                            <th class="fw-semibold">Jenis Cetak</th>
                            <th class="fw-semibold">Tgl. Masuk</th>
                            <th class="fw-semibold">Status</th>
                            <th class="text-end pe-4 fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold" style="color: var(--warna-utama);">RDH-001</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:32px; height:32px; font-size:0.75rem; background: #6c757d; flex-shrink:0;">BS</div>
                                    Budi Santoso
                                </div>
                            </td>
                            <td class="text-muted" style="font-size: 0.9rem;">HVS A4, 10 lembar, BW</td>
                            <td class="text-muted" style="font-size: 0.9rem;">15 Agt 2024</td>
                            <td><span class="badge rounded-pill px-3 py-2" style="background: #fff3cd; color: #856404; font-size: 0.8rem;">⏳ Diproses</span></td>
                            <td class="text-end pe-4"><a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a></td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold" style="color: var(--warna-utama);">RDH-002</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:32px; height:32px; font-size:0.75rem; background: #10b981; flex-shrink:0;">SA</div>
                                    Siti Aminah
                                </div>
                            </td>
                            <td class="text-muted" style="font-size: 0.9rem;">Foto Glossy, 5 lembar, Color</td>
                            <td class="text-muted" style="font-size: 0.9rem;">15 Agt 2024</td>
                            <td><span class="badge rounded-pill px-3 py-2" style="background: #d1fae5; color: #065f46; font-size: 0.8rem;">✅ Selesai</span></td>
                            <td class="text-end pe-4"><a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a></td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold" style="color: var(--warna-utama);">RDH-003</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:32px; height:32px; font-size:0.75rem; background: #1a73e8; flex-shrink:0;">RM</div>
                                    Rizky Maulana
                                </div>
                            </td>
                            <td class="text-muted" style="font-size: 0.9rem;">Art Paper, 50 lembar, Color</td>
                            <td class="text-muted" style="font-size: 0.9rem;">14 Agt 2024</td>
                            <td><span class="badge rounded-pill px-3 py-2" style="background: #dbeafe; color: #1e40af; font-size: 0.8rem;">🕐 Menunggu</span></td>
                            <td class="text-end pe-4"><a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a></td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold" style="color: var(--warna-utama);">RDH-004</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:32px; height:32px; font-size:0.75rem; background: #8b5cf6; flex-shrink:0;">DR</div>
                                    Dewi Rahayu
                                </div>
                            </td>
                            <td class="text-muted" style="font-size: 0.9rem;">Sticker Vinyl, 2 lembar, Color</td>
                            <td class="text-muted" style="font-size: 0.9rem;">13 Agt 2024</td>
                            <td><span class="badge rounded-pill px-3 py-2" style="background: #d1fae5; color: #065f46; font-size: 0.8rem;">✅ Selesai</span></td>
                            <td class="text-end pe-4"><a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script jam & tanggal realtime --}}
    <script>
        const HARI = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        function updateClock() {
            const now  = new Date();
            const jam  = String(now.getHours()).padStart(2, '0');
            const mnt  = String(now.getMinutes()).padStart(2, '0');
            const dtk  = String(now.getSeconds()).padStart(2, '0');
            const hari = HARI[now.getDay()];
            const tgl  = now.getDate();
            const bln  = BULAN[now.getMonth()];
            const thn  = now.getFullYear();

            document.getElementById('realtime-clock').textContent = `${jam}:${mnt}:${dtk}`;
            document.getElementById('realtime-date').textContent  = `${hari}, ${tgl} ${bln} ${thn}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    </script>

@endsection
