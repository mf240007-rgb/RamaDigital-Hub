@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Verifikasi Pembayaran ATK</h2>
            <small class="text-muted">Konfirmasi bukti transfer dari pelanggan yang memesan produk ATK</small>
        </div>
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white;">
            <i class="bi bi-patch-check-fill text-warning"></i>
            <span class="fw-semibold">{{ $counts['menunggu_konfirmasi'] }} Menunggu</span>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius:12px;border:none;border-left:5px solid #198754;">
            <i class="bi bi-check-circle-fill fs-5 text-success flex-shrink-0"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius:12px;border:none;border-left:5px solid #dc3545;">
            <i class="bi bi-exclamation-circle-fill fs-5 text-danger flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tab Filter --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('admin.verifikasi-atk.index', ['filter' => 'menunggu_konfirmasi']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'menunggu_konfirmasi' ? 'btn-warning fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-hourglass-split me-1"></i>Menunggu
            @if($counts['menunggu_konfirmasi'] > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $counts['menunggu_konfirmasi'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.verifikasi-atk.index', ['filter' => 'lunas']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'lunas' ? 'btn-success fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-check-circle me-1"></i>Lunas
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['lunas'] }}</span>
        </a>
        <a href="{{ route('admin.verifikasi-atk.index', ['filter' => 'belum_bayar']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'belum_bayar' ? 'btn-danger fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-x-circle me-1"></i>Belum Bayar
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['belum_bayar'] }}</span>
        </a>
        <a href="{{ route('admin.verifikasi-atk.index', ['filter' => 'semua']) }}"
           class="btn rounded-pill px-4 {{ $filter === 'semua' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
            <i class="bi bi-list-ul me-1"></i>Semua
            <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['semua'] }}</span>
        </a>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.verifikasi-atk.index') }}"
              class="ms-auto d-flex gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="search" class="form-control rounded-pill"
                   style="width:220px;font-size:0.88rem;"
                   placeholder="Cari nama pelanggan…"
                   value="{{ $keyword }}">
            <button type="submit" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px;">
        <div class="card-header bg-white px-4 py-3"
             style="border-radius:16px 16px 0 0;border-bottom:1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color:var(--warna-gelap);">
                <i class="bi bi-receipt me-2 text-primary"></i>
                Daftar Pesanan ATK —
                <span class="text-muted fw-normal">
                    @if($filter === 'menunggu_konfirmasi') Menunggu Konfirmasi
                    @elseif($filter === 'lunas') Sudah Lunas
                    @elseif($filter === 'belum_bayar') Belum Bayar
                    @else Semua Status
                    @endif
                </span>
            </h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8faff;">
                        <tr style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;color:#6c757d;">
                            <th class="ps-4 py-3 fw-semibold">No</th>
                            <th class="fw-semibold">No. Pesanan</th>
                            <th class="fw-semibold">Pelanggan</th>
                            <th class="fw-semibold">Detail Pesanan</th>
                            <th class="fw-semibold">Total</th>
                            <th class="fw-semibold">Tgl. Pesan</th>
                            <th class="fw-semibold text-center">Bukti Bayar</th>
                            <th class="fw-semibold text-center">Status Bayar</th>
                            <th class="fw-semibold text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                        @php
                            $nama    = $order->user->full_name ?? $order->user->name ?? 'Pelanggan';
                            $inisial = collect(explode(' ',$nama))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $colors  = ['#1a73e8','#10b981','#8b5cf6','#f59e0b','#ef4444','#06b6d4'];
                            $bg      = $colors[$i % count($colors)];
                            $wa      = preg_replace('/^0/','62',$order->user->whatsapp ?? '');

                            $ps = $order->payment_status ?? 'belum_bayar';
                            $pStyle = match($ps) {
                                'lunas'               => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
                                'menunggu_konfirmasi' => ['bg'=>'#fff3cd','text'=>'#856404','icon'=>'bi-hourglass-split','label'=>'Menunggu'],
                                'ditolak'             => ['bg'=>'#fee2e2','text'=>'#991b1b','icon'=>'bi-x-circle-fill','label'=>'Ditolak'],
                                default               => ['bg'=>'#f3f4f6','text'=>'#374151','icon'=>'bi-dash-circle','label'=>'Belum Bayar'],
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted" style="font-size:0.88rem;">
                                {{ ($orders->currentPage()-1)*$orders->perPage()+$i+1 }}
                            </td>

                            {{-- No Pesanan --}}
                            <td>
                                <span class="fw-semibold"
                                      style="color:var(--warna-utama);font-family:monospace;font-size:0.82rem;">
                                    {{ $order->order_number ?? 'RDH-'.$order->id }}
                                </span>
                            </td>

                            {{-- Pelanggan --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width:36px;height:36px;font-size:0.75rem;background:{{ $bg }};">
                                        {{ $inisial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.88rem;color:var(--warna-gelap);">
                                            {{ $nama }}
                                        </div>
                                        <small class="text-muted">{{ $order->user->whatsapp ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Detail --}}
                            <td style="font-size:0.88rem;max-width:180px;">
                                <div class="text-truncate" title="{{ $order->detail_pesanan }}">
                                    {{ $order->detail_pesanan }}
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="fw-semibold" style="font-size:0.88rem;">
                                Rp {{ number_format($order->total_harga,0,',','.') }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="text-muted" style="font-size:0.82rem;">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <span>{{ $order->created_at->format('H:i') }}</span>
                            </td>

                            {{-- Bukti Bayar --}}
                            <td class="text-center">
                                @if($order->bukti_bayar)
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <a href="{{ route('admin.verifikasi-atk.bukti', $order->id) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-info rounded-pill px-2"
                                           style="font-size:0.75rem;"
                                           title="Lihat bukti bayar">
                                            <i class="bi bi-eye me-1"></i>Lihat
                                        </a>
                                        <a href="{{ route('admin.verifikasi-atk.download', $order->id) }}"
                                           class="btn btn-sm btn-outline-secondary rounded-pill px-2"
                                           style="font-size:0.75rem;"
                                           title="Download bukti bayar">
                                            <i class="bi bi-download me-1"></i>Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:0.8rem;">
                                        <i class="bi bi-dash"></i> Tidak ada
                                    </span>
                                @endif
                            </td>

                            {{-- Status Bayar --}}
                            <td class="text-center">
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background:{{ $pStyle['bg'] }};color:{{ $pStyle['text'] }};font-size:0.78rem;">
                                    <i class="bi {{ $pStyle['icon'] }} me-1"></i>{{ $pStyle['label'] }}
                                </span>
                                @if($order->catatan_pembayaran)
                                    <div class="text-muted mt-1" style="font-size:0.72rem;max-width:120px;margin:auto;">
                                        {{ Str::limit($order->catatan_pembayaran, 40) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center pe-4">
                                <div class="d-flex flex-column align-items-center gap-1">

                                    @if($ps === 'menunggu_konfirmasi')
                                        {{-- Tombol Konfirmasi Lunas --}}
                                        <button type="button"
                                                class="btn btn-success btn-sm rounded-pill px-3 w-100"
                                                style="font-size:0.78rem;"
                                                onclick="bukaModalKonfirm({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}', {{ $order->total_harga }})">
                                            <i class="bi bi-check2 me-1"></i>Konfirmasi Lunas
                                        </button>
                                        {{-- Tombol Tolak --}}
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3 w-100"
                                                style="font-size:0.78rem;"
                                                onclick="bukaModalTolak({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                                            <i class="bi bi-x me-1"></i>Tolak
                                        </button>
                                    @elseif($ps === 'lunas')
                                        <span class="text-success" style="font-size:0.78rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Selesai
                                        </span>
                                        {{-- Hubungi WA --}}
                                        @if($order->user->whatsapp)
                                        <a href="https://api.whatsapp.com/send?phone={{ urlencode($wa) }}&text={{ urlencode('Halo '.$nama.', pembayaran pesanan '.$order->order_number.' sudah kami konfirmasi lunas. Pesanan sedang diproses. Terima kasih!') }}"
                                           target="_blank"
                                           class="btn btn-outline-success btn-sm rounded-pill px-2 w-100"
                                           style="font-size:0.75rem;">
                                            <i class="bi bi-whatsapp me-1"></i>WA
                                        </a>
                                        @endif
                                    @elseif($ps === 'ditolak')
                                        <span class="text-danger" style="font-size:0.78rem;">
                                            <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                                        </span>
                                    @else
                                        {{-- Belum bayar — bisa hubungi via WA --}}
                                        @if($order->user->whatsapp)
                                        <a href="https://api.whatsapp.com/send?phone={{ urlencode($wa) }}&text={{ urlencode('Halo '.$nama.', kami mengingatkan bahwa pesanan '.$order->order_number.' belum kami terima pembayarannya. Silakan segera transfer dan upload bukti bayar. Terima kasih!') }}"
                                           target="_blank"
                                           class="btn btn-outline-warning btn-sm rounded-pill px-2 w-100"
                                           style="font-size:0.75rem;">
                                            <i class="bi bi-whatsapp me-1"></i>Ingatkan
                                        </a>
                                        @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                @if($filter === 'menunggu_konfirmasi')
                                    Tidak ada pembayaran yang menunggu konfirmasi.
                                @elseif($filter === 'lunas')
                                    Belum ada pembayaran yang dikonfirmasi lunas.
                                @else
                                    Tidak ada pesanan ditemukan.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($orders->hasPages())
        <div class="card-footer bg-white px-4 py-3"
             style="border-radius:0 0 16px 16px;border-top:1px solid #f0f0f0;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- ── Modal Konfirmasi Lunas ── --}}
    <div class="modal fade" id="modalKonfirm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background:#f0fdf4;">
                    <div>
                        <h5 class="fw-bold mb-1 text-success">
                            <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi Lunas
                        </h5>
                        <p class="text-muted mb-0" id="labelNoKonfirm" style="font-size:0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formKonfirm" method="POST">
                    @csrf
                    <input type="hidden" name="aksi" value="lunas">
                    <div class="modal-body px-4 py-3">
                        <div class="mb-3 p-3 rounded-3" style="background:#f8faff;border:1px solid #e2e8f0;">
                            <div class="text-muted" style="font-size:0.82rem;">Total yang dikonfirmasi:</div>
                            <div class="fw-bold fs-5" id="labelTotalKonfirm" style="color:var(--warna-aksen);"></div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold mb-1" style="font-size:0.88rem;">
                                Catatan (Opsional)
                            </label>
                            <input type="text" name="catatan" class="form-control form-control-sm"
                                   placeholder="Misal: Transfer via BRI dikonfirmasi">
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-check2 me-1"></i>Tandai Lunas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Tolak ── --}}
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
                <div class="modal-header border-0 px-4 pt-4 pb-2" style="background:#fff5f5;">
                    <div>
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>Tolak Pembayaran
                        </h5>
                        <p class="text-muted mb-0" id="labelNoTolak" style="font-size:0.85rem;"></p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTolak" method="POST">
                    @csrf
                    <input type="hidden" name="aksi" value="tolak">
                    <div class="modal-body px-4 py-3">
                        <label class="form-label fw-semibold mb-1" style="font-size:0.88rem;">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea name="catatan" class="form-control" rows="3" required
                                  placeholder="Misal: Bukti bayar tidak jelas / nominal tidak sesuai"></textarea>
                        <div class="form-text">Alasan ini akan disimpan sebagai catatan pembayaran.</div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-fill fw-semibold">
                            <i class="bi bi-x me-1"></i>Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function bukaModalKonfirm(orderId, orderNumber, total) {
            document.getElementById('formKonfirm').action =
                '/admin/verifikasi-atk/' + orderId + '/konfirmasi';
            document.getElementById('labelNoKonfirm').textContent = 'Pesanan: ' + orderNumber;
            document.getElementById('labelTotalKonfirm').textContent =
                'Rp ' + total.toLocaleString('id-ID');
            document.querySelector('#formKonfirm input[name="catatan"]').value = '';
            new bootstrap.Modal(document.getElementById('modalKonfirm')).show();
        }

        function bukaModalTolak(orderId, orderNumber) {
            document.getElementById('formTolak').action =
                '/admin/verifikasi-atk/' + orderId + '/konfirmasi';
            document.getElementById('labelNoTolak').textContent = 'Pesanan: ' + orderNumber;
            document.querySelector('#formTolak textarea').value = '';
            new bootstrap.Modal(document.getElementById('modalTolak')).show();
        }
    </script>

@endsection
