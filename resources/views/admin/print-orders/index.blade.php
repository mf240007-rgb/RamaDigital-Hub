@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Pesanan Cetak</h2>
            <small class="text-muted">Daftar semua pesanan jasa cetak dokumen dari pelanggan</small>
        </div>
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white;">
            <i class="bi bi-printer-fill text-warning"></i>
            <span class="fw-semibold">{{ $orders->total() }} Pesanan</span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #198754;">
            <i class="bi bi-check-circle-fill fs-5 text-success flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #dc3545;">
            <i class="bi bi-exclamation-circle-fill fs-5 text-danger flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter & Search --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body px-4 py-3">
            <form method="GET" action="{{ route('admin.print-orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold mb-1" style="font-size: 0.85rem;">Cari Pelanggan / Detail</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Nama pelanggan atau detail pesanan..."
                               value="{{ $keyword }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1" style="font-size: 0.85rem;">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu Antrean" {{ $status === 'Menunggu Antrean' ? 'selected' : '' }}>Menunggu Antrean</option>
                        <option value="diproses"        {{ $status === 'diproses'         ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai"         {{ $status === 'selesai'          ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
                @if($keyword || $status)
                <div class="col-md-2">
                    <a href="{{ route('admin.print-orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabel Pesanan --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                <i class="bi bi-printer me-2 text-primary"></i>Daftar Pesanan Jasa Cetak
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
                            <th class="ps-4 py-3 fw-semibold">No</th>
                            <th class="fw-semibold">Pelanggan</th>
                            <th class="fw-semibold">Detail Pesanan</th>
                            <th class="fw-semibold">Tanggal Masuk</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold text-center">Dokumen</th>
                            <th class="fw-semibold text-center pe-4">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        @php
                            $nama    = $order->user->full_name ?? $order->user->name ?? 'Pelanggan';
                            $inisial = collect(explode(' ', $nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $colors  = ['#1a73e8','#10b981','#8b5cf6','#f59e0b','#ef4444','#06b6d4','#ec4899'];
                            $bgColor = $colors[$index % count($colors)];

                            $statusStyle = match($order->status) {
                                'Menunggu Antrean' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'bi-clock'],
                                'diproses'         => ['bg' => '#fff3cd', 'text' => '#856404', 'icon' => 'bi-gear-fill'],
                                'selesai'          => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'bi-check-circle-fill'],
                                default            => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'bi-dash'],
                            };
                        @endphp
                        <tr>
                            {{-- No --}}
                            <td class="ps-4 text-muted" style="font-size: 0.9rem;">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
                            </td>

                            {{-- Pelanggan --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width: 36px; height: 36px; font-size: 0.75rem; background: {{ $bgColor }};">
                                        {{ $inisial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size: 0.9rem; color: var(--warna-gelap);">{{ $nama }}</div>
                                        <small class="text-muted">{{ $order->user->whatsapp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Detail --}}
                            <td style="font-size: 0.9rem; max-width: 220px;">
                                <div>{{ $order->detail_pesanan }}</div>
                                @if($order->catatan)
                                    <small class="text-muted">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ $order->catatan }}
                                    </small>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="text-muted" style="font-size: 0.85rem;">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small>{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }}; font-size: 0.8rem;">
                                    <i class="bi {{ $statusStyle['icon'] }} me-1"></i>{{ ucfirst($order->status) }}
                                </span>
                            </td>

                            {{-- Dokumen --}}
                            <td class="text-center">
                                @if($order->file_dokumen)
                                    <a href="{{ route('admin.print-orders.download', $order->id) }}"
                                       class="btn btn-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1"
                                       title="Download dokumen pelanggan">
                                        <i class="bi bi-download"></i>
                                        <span>Download</span>
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">
                                        <i class="bi bi-dash"></i> Tidak ada
                                    </span>
                                @endif
                            </td>

                            {{-- Ubah Status --}}
                            <td class="text-center pe-4">
                                <form action="{{ route('admin.print-orders.status', $order->id) }}" method="POST"
                                      class="d-flex align-items-center gap-2 justify-content-center">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm"
                                            style="width: 150px; font-size: 0.82rem;"
                                            onchange="this.form.submit()">
                                        <option value="Menunggu Antrean" {{ $order->status === 'Menunggu Antrean' ? 'selected' : '' }}>
                                            Menunggu Antrean
                                        </option>
                                        <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>
                                            Diproses
                                        </option>
                                        <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-printer fs-1 d-block mb-2 opacity-25"></i>
                                @if($keyword || $status)
                                    Tidak ada pesanan yang cocok dengan filter.
                                    <br><a href="{{ route('admin.print-orders.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Reset Filter</a>
                                @else
                                    Belum ada pesanan jasa cetak masuk.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="card-footer bg-white px-4 py-3" style="border-radius: 0 0 16px 16px; border-top: 1px solid #f0f0f0;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

@endsection
