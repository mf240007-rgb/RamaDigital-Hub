@extends('layouts.admin')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Data Pelanggan</h2>
            <small class="text-muted">Daftar pelanggan terdaftar di sistem</small>
        </div>
        {{-- Jumlah total pelanggan --}}
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white;">
            <i class="bi bi-people-fill text-warning"></i>
            <span class="fw-semibold">{{ $customers->count() }} Pelanggan</span>
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

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3"
             style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
            <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                <i class="bi bi-person-lines-fill me-2 text-primary"></i>Daftar Pelanggan
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
                            <th class="ps-4 py-3 fw-semibold align-middle" style="width: 5%;">No</th>
                            <th class="fw-semibold align-middle" style="width: 30%;">Nama Pelanggan</th>
                            <th class="fw-semibold align-middle" style="width: 20%;">No. WhatsApp</th>
                            <th class="fw-semibold align-middle text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                        @php
                            $nama      = $customer->full_name ?? $customer->name ?? 'Pelanggan';
                            $inisial   = collect(explode(' ', $nama))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                            $colors    = ['#1a73e8','#10b981','#8b5cf6','#f59e0b','#ef4444','#06b6d4','#ec4899'];
                            $warnaBg   = $colors[$index % count($colors)];
                            $waNumber  = preg_replace('/^0/', '62', $customer->whatsapp ?? '');
                        @endphp
                        <tr>
                            {{-- No --}}
                            <td class="ps-4 text-muted" style="font-size: 0.9rem;">{{ $index + 1 }}</td>

                            {{-- Nama + Avatar --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                         style="width: 40px; height: 40px; font-size: 0.8rem; background: {{ $warnaBg }};">
                                        {{ $inisial }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color: var(--warna-gelap);">{{ $nama }}</div>
                                        <small class="text-muted">Pelanggan</small>
                                    </div>
                                </div>
                            </td>

                            {{-- No. WhatsApp --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-whatsapp text-success"></i>
                                    <span style="font-size: 0.9rem;">{{ $customer->whatsapp ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">

                                    {{-- Lihat Riwayat --}}
                                    <a href="{{ route('admin.customers.history', $customer->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 d-flex align-items-center gap-1">
                                        <i class="bi bi-journal-text"></i>
                                        <span>Riwayat</span>
                                    </a>

                                    {{-- Hubungi via WhatsApp --}}
                                    <a href="https://api.whatsapp.com/send?phone={{ urlencode($waNumber) }}&text={{ urlencode('Halo ' . $nama . ', ada yang bisa kami bantu?') }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-success rounded-pill px-3 d-flex align-items-center gap-1">
                                        <i class="bi bi-whatsapp"></i>
                                        <span>Hubungi</span>
                                    </a>

                                    {{-- Reset Password --}}
                                    <form action="{{ route('admin.customers.reset', $customer->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-warning rounded-pill px-3 d-flex align-items-center gap-1"
                                                onclick="return confirm('Reset password {{ addslashes($nama) }} ke password default?')">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            <span>Reset</span>
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 d-flex align-items-center gap-1"
                                            onclick="confirmDeleteCustomer('{{ route('admin.customers.destroy', $customer->id) }}', '{{ addslashes($nama) }}')">
                                        <i class="bi bi-trash"></i>
                                        <span>Hapus</span>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Belum ada data pelanggan yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-body text-center p-5">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 64px; height: 64px; background: #fef2f2;">
                        <i class="bi bi-person-x-fill text-danger fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Hapus Pelanggan?</h5>
                    <p class="text-muted mb-0">
                        Akun <strong id="deleteCustomerName"></strong> akan dihapus permanen beserta seluruh datanya.
                    </p>
                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Batal</button>
                        <form id="deleteCustomerForm" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">
                                <i class="bi bi-trash me-1"></i>Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteCustomer(actionUrl, customerName) {
            document.getElementById('deleteCustomerName').textContent = customerName;
            document.getElementById('deleteCustomerForm').action = actionUrl;
            new bootstrap.Modal(document.getElementById('deleteCustomerModal')).show();
        }
    </script>

@endsection
