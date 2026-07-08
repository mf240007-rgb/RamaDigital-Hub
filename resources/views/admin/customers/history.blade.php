@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Data Pelanggan
        </a>
        <h2 class="mb-1">Riwayat Pesanan</h2>
        <p class="text-muted">Riwayat pesanan untuk pelanggan <strong>{{ $customer->full_name ?? $customer->name }}</strong>.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
             role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #dc3545;">
            <i class="bi bi-exclamation-circle-fill fs-5 text-danger flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Detail Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($order->item_type === 'jasa')
                                        <span class="badge rounded-pill px-3 py-2" style="background: #dbeafe; color: #1e40af;">
                                            <i class="bi bi-printer me-1"></i>Jasa Cetak
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background: #d1fae5; color: #065f46;">
                                            <i class="bi bi-box-seam me-1"></i>Produk ATK
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $order->detail_pesanan }}
                                    @if($order->catatan)
                                        <br><small class="text-muted"><i class="bi bi-chat-left-text me-1"></i>{{ $order->catatan }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($order->total_harga > 0)
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted fst-italic">Menunggu konfirmasi</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColor = match($order->status) {
                                            'Menunggu Antrean' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'bi-clock',       'label' => 'Menunggu Antrean'],
                                            'diproses'        => ['bg' => '#fff3cd', 'text' => '#856404', 'icon' => 'bi-gear',         'label' => 'Diproses'],
                                            'selesai'         => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'bi-check-circle', 'label' => 'Selesai'],
                                            default           => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'bi-dash',         'label' => $order->status],
                                        };
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; font-size: 0.8rem;">
                                        <i class="bi {{ $statusColor['icon'] }} me-1"></i>{{ $statusColor['label'] ?? $order->status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="text-center">
                                    @if($order->file_dokumen)
                                        <a href="{{ route('admin.orders.download', $order->id) }}"
                                           class="btn btn-sm btn-primary rounded-pill px-3 d-inline-flex align-items-center gap-1"
                                           title="Download dokumen cetak">
                                            <i class="bi bi-download"></i>
                                            <span>Download</span>
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size: 0.85rem;">
                                            <i class="bi bi-dash"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-journal-x d-block fs-2 mb-2 opacity-25"></i>
                                    Belum ada riwayat pesanan untuk pelanggan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
