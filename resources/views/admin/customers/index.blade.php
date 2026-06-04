@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Data Pelanggan</h2>
            <p class="text-muted mb-0">Daftar pelanggan dengan role <strong>pelanggan</strong> yang terdaftar di sistem.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>No. WhatsApp</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->full_name ?? $customer->name }}</td>
                                <td>{{ $customer->whatsapp ?? '-' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.customers.history', $customer->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-journal-text me-1"></i>Lihat Riwayat
                                    </a>
                                    <a href="https://api.whatsapp.com/send?phone={{ urlencode($customer->whatsapp ?? '') }}&text={{ urlencode('Halo Admin, mohon reset password akun saya untuk pelanggan: ' . ($customer->full_name ?? $customer->name) . '. Terima kasih.') }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-success me-1">
                                        <i class="bi bi-whatsapp me-1"></i>Hubungi
                                    </a>
                                    <form action="{{ route('admin.customers.reset', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning text-dark">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Password
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
