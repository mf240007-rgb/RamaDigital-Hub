@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Data Pelanggan
        </a>
        <h2 class="mb-1">Riwayat Pesanan</h2>
        <p class="text-muted">Riwayat pesanan untuk pelanggan <strong>{{ $customer->full_name ?? $customer->name }}</strong>.</p>
    </div>

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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ ucfirst($order->item_type) }}</td>
                                <td>{{ $order->detail_pesanan }}</td>
                                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pesanan untuk pelanggan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
