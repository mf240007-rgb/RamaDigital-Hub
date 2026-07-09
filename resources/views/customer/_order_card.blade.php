@php
    $isJasa           = $order->item_type === 'jasa';
    $ps               = $order->getCustomerDisplayState();
    $canCancelJasa    = $isJasa && $order->status === 'Menunggu Antrean' && $order->payment_status !== 'lunas' && $order->status !== 'dibatalkan';
    $remainingBalance = $isJasa ? $order->getRemainingBalance() : 0;
    $hasPriceUpdate   = $isJasa && !empty($order->harga_final) && (int)$order->harga_final > 0 && (int)$order->harga_final !== (int)$order->total_harga;
    $showSisaPaymentForm = $isJasa
        && $order->status === 'selesai'
        && $remainingBalance > 0
        && !in_array($order->payment_status, ['lunas', 'sisa_dibayar']);

    if ($isJasa) {
        $payStyle = match(true) {
            $order->status === 'selesai'                           => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill','label'=>'Selesai'],
            $order->payment_status === 'lunas'                     => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill','label'=>'Lunas'],
            $order->payment_status === 'dp_diterima'               => ['bg'=>'#d1fae5','text'=>'#065f46','icon'=>'bi-check-circle-fill','label'=>'DP Dikonfirmasi'],
            $order->payment_status === 'sisa_dibayar'              => ['bg'=>'#fff3cd','text'=>'#856404','icon'=>'bi-hourglass-split','label'=>'Menunggu Pelunasan Sisa'],
            $order->payment_status === 'menunggu_konfirmasi'       => ['bg'=>'#fff3cd','text'=>'#856404','icon'=>'bi-hourglass-split','label'=>'DP Menunggu Konfirmasi'],
            $order->status === 'diproses'                          => ['bg'=>'#dbeafe','text'=>'#1e40af','icon'=>'bi-gear-fill','label'=>'Diproses'],
            $order->status === 'dibatalkan'                        => ['bg'=>'#fee2e2','text'=>'#991b1b','icon'=>'bi-x-circle-fill','label'=>'Dibatalkan'],
            default                                                => ['bg'=>'#fff3cd','text'=>'#856404','icon'=>'bi-hourglass-split','label'=>'Menunggu Antrean'],
        };
    } else {
        $payStyle = $order->paymentBadge();
    }

    $accentColor = match($ps) {
        'lunas','selesai'           => '#10b981',
        'menunggu_konfirmasi',
        'Menunggu Antrean'          => '#f59e0b',
        'diproses'                  => '#3b82f6',
        default                     => '#ef4444',
    };
    $headerClass = $isJasa
        ? 'order-header-' . ($order->status === 'Menunggu Antrean' ? 'menunggu' : $order->status)
        : 'order-header-' . $ps;

    $tampilNota = (!$isJasa && $ps === 'lunas') || ($isJasa && $order->status === 'selesai' && $order->payment_status === 'lunas');
@endphp

<div class="card border-0 shadow-sm order-card" data-status="{{ $ps }}">

    {{-- HEADER --}}
    <div class="px-4 pt-4 pb-3 {{ $headerClass }} position-relative">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div class="flex-grow-1">
                <div class="fw-bold mb-1" style="font-family:monospace;font-size:1.05rem;color:var(--warna-gelap);letter-spacing:.5px;">
                    {{ $order->order_number ?? 'RDH-'.$order->id }}
                </div>
                <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.8rem;">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y') }}</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $order->created_at->format('H:i') }} WIB</span>
                </div>
            </div>
            <span class="badge rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1 flex-shrink-0"
                  style="background:{{ $payStyle['bg'] }};color:{{ $payStyle['text'] }};font-size:.82rem;">
                <i class="bi {{ $payStyle['icon'] }}"></i>{{ $payStyle['label'] }}
            </span>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <span class="info-chip" style="background:#f8fafc;color:#0f172a;border:1px solid #e2e8f0;">
                <i class="bi {{ $isJasa ? 'bi-printer' : 'bi-bag' }}"></i>
                {{ $isJasa ? 'Jasa Cetak' : 'Produk ATK' }}
            </span>
            <span class="info-chip" style="background:#ffffff;color:#0f172a;border:1px solid #e2e8f0;">
                <i class="bi bi-cash-coin"></i>
                Rp {{ number_format($order->getDisplayTotalHarga(), 0, ',', '.') }}
            </span>
            @if($hasPriceUpdate)
                <span class="info-chip" style="background:#fef9c3;color:#854d0e;border:1px solid #fde047;"
                      title="Harga berubah dari estimasi awal Rp {{ number_format($order->total_harga, 0, ',', '.') }}">
                    <i class="bi bi-exclamation-triangle-fill"></i>Harga Diperbarui
                </span>
            @endif
            @if($isJasa)
                <span class="info-chip" style="background:#fff7ed;color:#9a2c00;border:1px solid #fdba74;">
                    <i class="bi bi-credit-card-2-front"></i>DP {{ number_format($order->getDpAmount(), 0, ',', '.') }}
                </span>
                @if($remainingBalance > 0)
                    <span class="info-chip" style="background:#fef2f2;color:#b91c1c;border:1px solid #fda4af;">
                        <i class="bi bi-wallet2"></i>Sisa {{ number_format($remainingBalance, 0, ',', '.') }}
                    </span>
                @endif
            @endif
        </div>
    </div>

    <hr class="order-divider">

    {{-- BODY --}}
    <div class="px-4 py-3">
        @if($hasPriceUpdate)
            <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-center gap-2"
                 style="border-radius:12px;border:none;background:#fff7ed;color:#9a2c00;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div><strong>Harga final telah diperbarui oleh admin.</strong> Silakan cek kembali total pembayaran Anda.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
            <div>
                <div class="text-uppercase text-muted fw-semibold" style="font-size:.75rem;letter-spacing:.06em;">Ringkasan Pesanan</div>
                <div class="text-muted" style="font-size:.86rem;">Item dan jumlah dibuat seragam untuk tiap transaksi.</div>
            </div>
            <button class="btn btn-link p-0 detail-link" type="button"
                    data-bs-toggle="collapse" data-bs-target="#detailOrder{{ $order->id }}">Detail</button>
        </div>

        @php $summaryRows = $order->summaryRows(); @endphp
        <div class="summary-list">
            @foreach($summaryRows as $row)
                <div class="summary-item">
                    <div class="summary-label text-truncate">{{ $row['label'] }}</div>
                    <div class="summary-value">{{ $row['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="collapse mt-3" id="detailOrder{{ $order->id }}">
            <div class="detail-panel p-3">
                <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Detail Tambahan</div>
                <div class="fw-semibold" style="color:var(--warna-gelap);font-size:.95rem;line-height:1.5;">{{ $order->detail_pesanan }}</div>

                @if($isJasa)
                    @php $dokumenDisplayNames = $order->getDokumenDisplayNames(); @endphp
                    <div class="mt-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-files text-primary"></i>
                            <div class="fw-semibold" style="font-size:.9rem;color:var(--warna-gelap);">Dokumen yang Dicetak</div>
                        </div>
                        @if(!empty($dokumenDisplayNames))
                            <ul class="mb-0 ps-3" style="font-size:.88rem;color:#334155;line-height:1.6;">
                                @foreach($dokumenDisplayNames as $fn)<li>{{ $fn }}</li>@endforeach
                            </ul>
                        @else
                            <div class="text-muted" style="font-size:.88rem;">Belum ada dokumen.</div>
                        @endif
                    </div>
                    @if($order->catatan)
                        <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <i class="bi bi-chat-left-text-fill text-info flex-shrink-0 mt-1"></i>
                            <div style="font-size:.88rem;color:#0369a1;"><strong>Catatan:</strong><br>{{ $order->catatan }}</div>
                        </div>
                    @endif
                @endif

                @if($order->catatan_pembayaran)
                    @php
                        $cBg   = $ps === 'ditolak' ? '#fff5f5' : ($ps === 'menunggu_persetujuan_batal' ? '#fce7f3' : '#f0f9ff');
                        $cBdr  = $ps === 'ditolak' ? '#fecaca' : ($ps === 'menunggu_persetujuan_batal' ? '#fbcfe8' : '#bae6fd');
                        $cIcon = $ps === 'ditolak' ? 'bi-exclamation-triangle-fill text-danger' : ($ps === 'menunggu_persetujuan_batal' ? 'bi-clock-history text-danger' : 'bi-chat-left-text-fill text-info');
                        $cTxt  = $ps === 'ditolak' ? '#991b1b' : ($ps === 'menunggu_persetujuan_batal' ? '#9d174d' : '#0369a1');
                    @endphp
                    <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2" style="background:{{ $cBg }};border:1px solid {{ $cBdr }};">
                        <i class="bi {{ $cIcon }} flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                        <div style="font-size:.88rem;color:{{ $cTxt }};">
                            @if($ps === 'ditolak')<strong>Alasan Penolakan:</strong><br>@endif
                            {{ $order->catatan_pembayaran }}
                        </div>
                    </div>
                @endif

                @if($order->cancellation_reason && $ps === 'menunggu_persetujuan_batal')
                    <div class="mt-3 p-3 rounded-3 d-flex align-items-start gap-2" style="background:#fce7f3;border:1px solid #fbcfe8;">
                        <i class="bi bi-clock-history text-danger flex-shrink-0 mt-1" style="font-size:.9rem;"></i>
                        <div style="font-size:.88rem;color:#9d174d;">
                            <strong>Alasan Pembatalan yang Diajukan:</strong><br>
                            {{ $order->cancellation_reason }}
                            <div class="mt-1 text-muted" style="font-size:.78rem;">
                                Diajukan {{ $order->cancellation_requested_at?->format('d M Y H:i') }} WIB
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <hr class="order-divider">

    {{-- FOOTER --}}
    <div class="px-4 py-3 card-actions">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="status-note">
                @if($isJasa && $order->payment_status === 'lunas') Pesanan telah lunas dan selesai.
                @elseif($isJasa && $order->payment_status === 'dp_diterima') DP sudah dikonfirmasi. Pesanan sedang diproses.
                @elseif($isJasa && $order->payment_status === 'sisa_dibayar') Bukti pelunasan sisa sudah dikirim, menunggu verifikasi admin.
                @elseif($isJasa && $order->status === 'Menunggu Antrean') Menunggu pembayaran awal (DP) sebelum diproses admin.
                @elseif($isJasa && $order->payment_status === 'menunggu_konfirmasi') Bukti DP sudah dikirim, menunggu verifikasi admin.
                @elseif($ps === 'ditolak') Bukti pembayaran ditolak. Silakan upload ulang.
                @elseif($ps === 'menunggu_konfirmasi') Menunggu verifikasi admin.
                @elseif($ps === 'menunggu_persetujuan_batal') Permintaan pembatalan sedang diproses admin.
                @elseif($ps === 'lunas') Pembayaran telah dikonfirmasi.
                @elseif($isJasa && $order->status === 'selesai') Pesanan selesai dikerjakan.
                @else Pesanan sedang diproses.
                @endif
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                @if($ps === 'ditolak' || ($isJasa && $order->status === 'Menunggu Antrean' && !$order->bukti_bayar))
                    <form action="{{ route('customer.orders.upload-bukti', $order->id) }}" method="POST" enctype="multipart/form-data"
                          class="d-inline-flex align-items-center gap-2 flex-wrap">
                        @csrf
                        <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/jpg"
                               class="form-control form-control-sm" style="max-width:220px;">
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            <i class="bi bi-send me-1"></i>Kirim Bukti {{ $isJasa ? 'DP' : '' }}
                        </button>
                    </form>
                @elseif($ps === 'menunggu_konfirmasi' || $canCancelJasa)
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold"
                            onclick="bukaModalBatalPelanggan({{ $order->id }}, '{{ addslashes($order->order_number ?? '#'.$order->id) }}')">
                        <i class="bi bi-x-circle me-1"></i>{{ $isJasa ? 'Batalkan Pesanan' : 'Ajukan Pembatalan' }}
                    </button>
                @elseif($showSisaPaymentForm)
                    <form action="{{ route('customer.orders.upload-bukti', $order->id) }}" method="POST" enctype="multipart/form-data"
                          class="d-inline-flex align-items-center gap-2 flex-wrap">
                        @csrf
                        <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/jpg"
                               class="form-control form-control-sm" style="max-width:220px;">
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            <i class="bi bi-send me-1"></i>Kirim Bukti Sisa
                        </button>
                    </form>
                @elseif($ps === 'menunggu_persetujuan_batal')
                    @php $waMsg = urlencode('Halo Admin, saya ingin menindaklanjuti permintaan pembatalan pesanan ' . ($order->order_number ?? '#'.$order->id) . '. Terima kasih.'); @endphp
                    <a href="https://api.whatsapp.com/send?phone=6285273300045&text={{ $waMsg }}" target="_blank"
                       class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i>Hubungi Admin
                    </a>
                @endif

                @if($tampilNota)
                    <a href="{{ route('customer.orders.nota', $order->id) }}" target="_blank"
                       class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                        <i class="bi bi-receipt me-1"></i>Lihat Nota
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>{{-- /order-card --}}
