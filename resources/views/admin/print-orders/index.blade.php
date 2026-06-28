@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Pesanan Cetak</h2>
            <small class="text-muted">Daftar semua pesanan jasa cetak dokumen dari pelanggan</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
                 style="background: var(--warna-gelap); color: white;">
                <i class="bi bi-printer-fill text-warning"></i>
                <span class="fw-semibold">{{ $orders->total() }} Pesanan</span>
            </div>
            {{-- Tombol Hapus Filter (buka modal) --}}
            <button type="button"
                    class="btn btn-outline-danger rounded-3 px-3 py-2 d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#modalHapusFilter"
                    title="Hapus pesanan berdasarkan filter">
                <i class="bi bi-funnel-fill"></i>
                <span class="fw-semibold" style="font-size: 0.9rem;">Hapus via Filter</span>
            </button>
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
                        <option value="dibatalkan"      {{ $status === 'dibatalkan'       ? 'selected' : '' }}>Dibatalkan</option>
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

    {{-- Form Hapus Massal (Checkbox) --}}
    <form id="formHapusMassal"
          action="{{ route('admin.print-orders.bulk-delete') }}"
          method="POST">
        @csrf
        <input type="hidden" name="bulk_mode" value="selected">

    {{-- Toolbar Hapus Massal (muncul saat ada yang dicentang) --}}
    <div id="toolbarHapusMassal"
         class="d-none mb-3 px-4 py-2 rounded-3 d-flex align-items-center gap-3"
         style="background: #fff5f5; border: 1px solid #fecaca;">
        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
        <span class="fw-semibold text-danger" id="labelJumlahDipilih">0 pesanan dipilih</span>
        <button type="button"
                class="btn btn-danger btn-sm rounded-pill px-3 ms-auto"
                onclick="konfirmasiHapusMassal()">
            <i class="bi bi-trash3-fill me-1"></i>Hapus yang Dipilih
        </button>
        <button type="button"
                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                onclick="batalPilihan()">
            Batal
        </button>
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
                            <th class="ps-3 py-3 align-middle" style="width: 36px;">
                                <input type="checkbox" class="form-check-input" id="checkAll"
                                       title="Pilih semua" onchange="toggleCheckAll(this)">
                            </th>
                            <th class="fw-semibold py-3 align-middle" style="width: 40px;">No</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 150px;">No. Pesanan</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 170px;">Pelanggan</th>
                            <th class="fw-semibold py-3 align-middle">Detail Pesanan</th>
                            <th class="fw-semibold py-3 align-middle" style="width: 110px;">Tgl. Masuk</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 110px;">Dokumen</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 140px;">Ubah Status</th>
                            <th class="fw-semibold py-3 align-middle text-center" style="width: 110px;">Batalkan</th>
                            <th class="fw-semibold py-3 align-middle text-center pe-3" style="width: 60px;">Hapus</th>
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
                                'dibatalkan'       => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'bi-x-circle-fill'],
                                default            => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'bi-dash'],
                            };
                        @endphp
                        <tr class="order-row" data-id="{{ $order->id }}">
                            {{-- Checkbox --}}
                            <td class="ps-3">
                                <input type="checkbox"
                                       class="form-check-input order-checkbox"
                                       name="order_ids[]"
                                       value="{{ $order->id }}"
                                       onchange="updateToolbar()">
                            </td>

                            {{-- No --}}
                            <td class="text-muted text-center" style="font-size: 0.88rem;">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
                            </td>

                            {{-- No. Pesanan --}}
                            <td>
                                @if($order->order_number)
                                    <span class="badge rounded-pill px-2 py-1 fw-semibold"
                                          style="background:#f0f4ff;color:#1a73e8;font-size:0.75rem;letter-spacing:0.3px;font-family:monospace;white-space:nowrap;">
                                        {{ $order->order_number }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Pelanggan --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width:32px;height:32px;font-size:0.72rem;background:{{ $bgColor }};">
                                        {{ $inisial }}
                                    </div>
                                    <div style="min-width:0;">
                                        <div class="fw-semibold text-truncate" style="font-size:0.85rem;color:var(--warna-gelap);max-width:120px;">{{ $nama }}</div>
                                        <small class="text-muted">{{ $order->user->whatsapp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Detail --}}
                            <td style="font-size:0.85rem;">
                                <div>{{ $order->detail_pesanan }}</div>
                                @if($order->catatan)
                                    <small class="text-muted"><i class="bi bi-chat-left-text me-1"></i>{{ $order->catatan }}</small>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="text-muted" style="font-size:0.82rem;white-space:nowrap;">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small>{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>

                            {{-- Dokumen --}}
                            <td class="text-center">
                                @if($order->file_dokumen)
                                    <a href="{{ route('admin.print-orders.download', $order->id) }}"
                                       class="btn btn-primary btn-sm rounded-pill px-3"
                                       style="font-size:0.78rem;white-space:nowrap;"
                                       title="Download dokumen">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:0.82rem;">— Tidak ada</span>
                                @endif
                            </td>

                            {{-- Ubah Status --}}
                            <td class="text-center">
                                @if($order->status === 'dibatalkan')
                                    <small class="text-danger fw-semibold" style="font-size:0.75rem;">
                                        <i class="bi bi-x-circle-fill me-1"></i>Dibatalkan<br>
                                        <span class="text-muted fw-normal">oleh {{ ucfirst($order->dibatalkan_oleh ?? '—') }}</span>
                                    </small>
                                @else
                                    <form action="{{ route('admin.print-orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <select name="status"
                                                class="form-select form-select-sm w-100"
                                                style="font-size:0.8rem;"
                                                onchange="this.form.submit()">
                                            <option value="Menunggu Antrean" {{ $order->status === 'Menunggu Antrean' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="diproses"         {{ $order->status === 'diproses'         ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai"          {{ $order->status === 'selesai'          ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </form>
                                @endif
                            </td>

                            {{-- Batalkan --}}
                            <td class="text-center">
                                @if(in_array($order->status, ['Menunggu Antrean', 'diproses']))
                                    <button type="button"
                                            class="btn btn-sm btn-outline-warning rounded-pill px-2"
                                            style="font-size:0.75rem;white-space:nowrap;"
                                            onclick="bukaModalBatalAdmin({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')"
                                            title="Batalkan pesanan ini">
                                        <i class="bi bi-x-lg me-1"></i>Batalkan
                                    </button>
                                @elseif($order->status === 'dibatalkan' && $order->alasan_pembatalan)
                                    <button type="button"
                                            class="btn btn-sm btn-link text-danger p-0"
                                            style="font-size:0.75rem;white-space:nowrap;"
                                            data-bs-toggle="tooltip"
                                            title="{{ $order->alasan_pembatalan }}">
                                        <i class="bi bi-chat-left-text me-1"></i>Lihat alasan
                                    </button>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Hapus Satu --}}
                            <td class="text-center pe-3">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                        style="width:32px;height:32px;"
                                        title="Hapus pesanan ini"
                                        onclick="hapusSatuPesanan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                    <i class="bi bi-trash3" style="font-size:0.8rem;"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
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

    </form>{{-- Akhir formHapusMassal --}}

    {{-- Form hapus satu pesanan (di luar formHapusMassal agar tidak nested) --}}
    <form id="formHapusSatu" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>


    {{-- =============================================
         MODAL: Batalkan Pesanan (Admin)
         ============================================= --}}
    <div class="modal fade" id="modalBatalAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background: #fffbeb;">
                    <div>
                        <h5 class="fw-bold mb-1 text-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Pesanan
                        </h5>
                        <p class="text-muted mb-0" id="labelNomorPesananBatal" style="font-size: 0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formBatalAdmin" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <label class="form-label fw-semibold mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="alasan_pembatalan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Contoh: File dokumen tidak dapat diproses, stok kertas habis, dll."
                                  maxlength="500"
                                  required></textarea>
                        <div class="form-text">Alasan ini akan terlihat oleh pelanggan saat mengecek status pesanan.</div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-warning rounded-pill flex-fill fw-semibold text-dark">
                            <i class="bi bi-x-lg me-1"></i>Konfirmasi Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =============================================
         MODAL: Hapus via Filter
         ============================================= --}}
    <div class="modal fade" id="modalHapusFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">

                <div class="modal-header border-0 px-4 pt-4 pb-2"
                     style="background: #fff5f5;">
                    <div>
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-funnel-fill me-2"></i>Hapus Pesanan via Filter
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                            Hapus banyak pesanan sekaligus berdasarkan status dan umur pesanan.
                        </p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.print-orders.bulk-delete') }}" method="POST"
                      onsubmit="return konfirmasiHapusFilter(this)">
                    @csrf
                    <input type="hidden" name="bulk_mode" value="filter">

                    <div class="modal-body px-4 py-3">

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Status Pesanan</label>
                            <select name="filter_status" class="form-select" required>
                                <option value="selesai" selected>Selesai</option>
                                <option value="Menunggu Antrean">Menunggu Antrean</option>
                                <option value="diproses">Diproses</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="semua">Semua Status</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Lebih dari</label>
                            <select name="filter_older" class="form-select" required>
                                <option value="30" selected>30 hari yang lalu</option>
                                <option value="7">7 hari yang lalu</option>
                                <option value="14">14 hari yang lalu</option>
                                <option value="60">60 hari yang lalu</option>
                                <option value="90">90 hari yang lalu</option>
                            </select>
                        </div>

                        <div class="alert alert-warning border-0 py-2 px-3 mb-0"
                             style="border-radius: 10px; background: #fef9c3; font-size: 0.83rem;">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Pesanan yang dihapus <strong>tidak dapat dikembalikan</strong>. File dokumen juga akan ikut terhapus.
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-trash3-fill me-1"></i>Hapus Sekarang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- JavaScript --}}
    <script>
        // ── Checkbox: Pilih Semua ──────────────────────────────
        function toggleCheckAll(master) {
            document.querySelectorAll('.order-checkbox').forEach(cb => {
                cb.checked = master.checked;
            });
            updateToolbar();
        }

        // ── Update toolbar saat ada perubahan centang ──────────
        function updateToolbar() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            const toolbar = document.getElementById('toolbarHapusMassal');
            const label   = document.getElementById('labelJumlahDipilih');

            if (checked.length > 0) {
                toolbar.classList.remove('d-none');
                toolbar.classList.add('d-flex');
                label.textContent = checked.length + ' pesanan dipilih';
            } else {
                toolbar.classList.add('d-none');
                toolbar.classList.remove('d-flex');
            }

            // Sync state checkAll
            const all  = document.querySelectorAll('.order-checkbox');
            const master = document.getElementById('checkAll');
            if (master) {
                master.indeterminate = checked.length > 0 && checked.length < all.length;
                master.checked = checked.length === all.length && all.length > 0;
            }
        }

        // ── Konfirmasi hapus massal (checkbox) ────────────────
        function konfirmasiHapusMassal() {
            const jumlah = document.querySelectorAll('.order-checkbox:checked').length;
            if (jumlah === 0) {
                alert('Pilih minimal satu pesanan.');
                return;
            }
            if (confirm(jumlah + ' pesanan akan dihapus permanen beserta file dokumennya. Lanjutkan?')) {
                document.getElementById('formHapusMassal').submit();
            }
        }

        // ── Batal pilihan ─────────────────────────────────────
        function batalPilihan() {
            document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = false);
            const master = document.getElementById('checkAll');
            if (master) { master.checked = false; master.indeterminate = false; }
            updateToolbar();
        }

        // ── Konfirmasi hapus filter ───────────────────────────
        function konfirmasiHapusFilter(form) {
            const status = form.querySelector('[name="filter_status"]').options[form.querySelector('[name="filter_status"]').selectedIndex].text;
            const older  = form.querySelector('[name="filter_older"]').options[form.querySelector('[name="filter_older"]').selectedIndex].text;
            return confirm('Hapus semua pesanan "' + status + '" yang lebih tua dari ' + older + '?\n\nTindakan ini tidak dapat dibatalkan.');
        }

        // ── Hapus Satu Pesanan (via form terpisah, hindari nested form) ──
        function hapusSatuPesanan(orderId, orderNumber) {
            if (!confirm('Hapus pesanan ' + orderNumber + '? Tindakan ini tidak dapat dibatalkan.')) return;
            const form = document.getElementById('formHapusSatu');
            form.action = '/admin/pesanan-cetak/' + orderId;
            form.submit();
        }

        // ── Modal Batalkan (Admin) ────────────────────────────
        function bukaModalBatalAdmin(orderId, orderNumber) {
            const modal = document.getElementById('modalBatalAdmin');
            const form  = document.getElementById('formBatalAdmin');
            const label = document.getElementById('labelNomorPesananBatal');

            form.action = '/admin/pesanan-cetak/' + orderId + '/cancel';
            label.textContent = 'Pesanan: ' + orderNumber;

            // Reset textarea
            modal.querySelector('textarea[name="alasan_pembatalan"]').value = '';

            new bootstrap.Modal(modal).show();
        }

        // ── Init tooltips ─────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el, { placement: 'top' });
            });
        });
    </script>

@endsection
