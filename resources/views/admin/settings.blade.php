@extends('layouts.admin')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--warna-gelap);">Pengaturan Akun</h2>
            <small class="text-muted">Ubah username dan password login Admin Panel</small>
        </div>
        <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm"
             style="background: var(--warna-gelap); color: white;">
            <i class="bi bi-person-gear text-warning"></i>
            <span class="fw-semibold">{{ session('admin_username', 'admin') }}</span>
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

    <form action="{{ route('admin.settings.update') }}" method="POST" autocomplete="off">
        @csrf

        <div class="row g-4">

            {{-- Kolom kiri: Ubah Username & Password --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>Ubah Kredensial Login
                        </h6>
                    </div>
                    <div class="card-body px-4 py-4">

                        {{-- Username baru --}}
                        <div class="mb-4">
                            <label for="newUsername" class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i> Username Baru
                            </label>
                            <input type="text"
                                   class="form-control @error('new_username') is-invalid @enderror"
                                   id="newUsername"
                                   name="new_username"
                                   placeholder="Kosongkan jika tidak ingin mengubah"
                                   value="{{ old('new_username') }}"
                                   autocomplete="off">
                            @error('new_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password baru --}}
                        <div class="mb-3">
                            <label for="newPassword" class="form-label fw-semibold">
                                <i class="bi bi-key me-1"></i> Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('new_password') is-invalid @enderror"
                                       id="newPassword"
                                       name="new_password"
                                       placeholder="Kosongkan jika tidak ingin mengubah"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('newPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Minimal 6 karakter.</div>
                        </div>

                        {{-- Konfirmasi password baru --}}
                        <div class="mb-0">
                            <label for="newPasswordConfirm" class="form-label fw-semibold">
                                <i class="bi bi-key-fill me-1"></i> Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="newPasswordConfirm"
                                       name="new_password_confirmation"
                                       placeholder="Ulangi password baru"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('newPasswordConfirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Kolom kanan: Konfirmasi & Info --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-header bg-white px-4 py-3 border-0"
                         style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="fw-bold mb-0" style="color: var(--warna-gelap);">
                            <i class="bi bi-lock me-2 text-warning"></i>Konfirmasi Perubahan
                        </h6>
                    </div>
                    <div class="card-body px-4 py-4 d-flex flex-column">

                        {{-- Info akun saat ini --}}
                        <div class="p-3 rounded-3 mb-4"
                             style="background: #f8faff; border: 1px solid #e8eeff;">
                            <p class="text-muted mb-2" style="font-size: 0.82rem; font-weight: 600; letter-spacing: 0.05em;">
                                AKUN AKTIF SAAT INI
                            </p>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                     style="width: 44px; height: 44px; background: var(--warna-utama); font-size: 1.1rem; flex-shrink: 0;">
                                    {{ strtoupper(substr(session('admin_username', 'A'), 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold" style="color: var(--warna-gelap);">
                                        {{ session('admin_username', 'admin') }}
                                    </div>
                                    <small class="text-muted">Administrator</small>
                                </div>
                            </div>
                        </div>

                        {{-- Password saat ini --}}
                        <div class="mb-4">
                            <label for="currentPassword" class="form-label fw-semibold">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="currentPassword"
                                       name="current_password"
                                       placeholder="Wajib diisi untuk konfirmasi"
                                       autocomplete="current-password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('currentPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Diperlukan untuk memverifikasi identitas kamu.</div>
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold flex-fill">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-outline-secondary rounded-pill px-4">
                                Batal
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon  = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

@endsection
