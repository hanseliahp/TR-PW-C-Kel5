@extends('layouts.auth')

@section('title', 'Masuk Portal - SIPFK')

@section('content')
<div class="card card-sipfk border-0 shadow-lg p-3 p-sm-4 bg-white" style="border-radius: var(--radius-xl);">
    {{-- Role Selector Tabs: Admin vs Pelapor --}}
    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill border" id="authRoleTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link active rounded-pill fw-semibold small py-2" 
                id="tab-admin" 
                data-bs-toggle="pill" 
                data-bs-target="#panel-admin" 
                type="button" 
                role="tab" 
                aria-controls="panel-admin" 
                aria-selected="true"
            >
                <i class="bi bi-shield-lock me-1"></i>
                <span>Admin Sarpras</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link rounded-pill fw-semibold small py-2 text-secondary" 
                id="tab-pelapor" 
                data-bs-toggle="pill" 
                data-bs-target="#panel-pelapor" 
                type="button" 
                role="tab" 
                aria-controls="panel-pelapor" 
                aria-selected="false"
            >
                <i class="bi bi-person me-1"></i>
                <span>Pelapor / Mahasiswa</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="authRoleTabContent">
        {{-- ==========================================
             PANEL 1: LOGIN ADMINISTRATOR (FR-01, FR-02)
             ========================================== --}}
        <div class="tab-pane fade show active" id="panel-admin" role="tabpanel" aria-labelledby="tab-admin">
            <div class="text-center mb-3">
                <h2 class="h5 fw-bold text-dark mb-1">Masuk Panel Administrator</h2>
                <p class="text-secondary small mb-0">Kelola tiket keluhan, perbarui status, dan tindak lanjut sarana.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Input Email dengan Gaya Modern Seamless --}}
                <div class="mb-3">
                    <label for="email" class="form-label-zd">
                        Alamat Email Admin <span class="required-star">*</span>
                    </label>
                    <div class="input-clean-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control form-control-zd @error('email') is-invalid @enderror" 
                            value="{{ old('email', 'admin@sipfk.test') }}" 
                            placeholder="admin@sipfk.test" 
                            required 
                            autocomplete="email" 
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Input Password dengan Gaya Modern Seamless --}}
                <div class="mb-3">
                    <label for="password" class="form-label-zd">
                        Kata Sandi <span class="required-star">*</span>
                    </label>
                    <div class="input-clean-group">
                        <i class="bi bi-lock input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control form-control-zd @error('password') is-invalid @enderror" 
                            placeholder="••••••••" 
                            required 
                            autocomplete="current-password"
                        >
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat sesi perangkat ini
                        </label>
                    </div>
                    <span class="badge rounded-pill bg-light text-muted border small">Role Admin</span>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-dark w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-semibold mb-3" style="border-radius: 12px; background: #0F172A; border-color: #0F172A; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Masuk ke Dashboard Admin</span>
                </button>
            </form>

            {{-- Petunjuk Akun Demo Pengujian Evaluator (DatabaseSeeder) --}}
            <div class="pt-3 border-top">
                <div class="p-2 p-sm-3 rounded-3 border bg-light text-secondary small">
                    <div class="fw-bold text-dark d-flex align-items-center gap-1 mb-1">
                        <i class="bi bi-key-fill text-primary"></i>
                        <span>Kredensial Demo Uji (Seeder):</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Email:</span>
                        <code class="fw-bold text-dark">admin@sipfk.test</code>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Password:</span>
                        <code class="fw-bold text-dark">password</code>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             PANEL 2: INFORMASI PELAPOR / MAHASISWA
             (Menjelaskan Kebijakan PRD Seksi 3 & 15)
             ========================================== --}}
        <div class="tab-pane fade" id="panel-pelapor" role="tabpanel" aria-labelledby="tab-pelapor">
            <div class="text-center py-2">
                <div class="stat-card-icon-bubble bg-primary-subtle text-primary mx-auto mb-3" style="width: 50px; height: 50px; font-size: 1.35rem; border-radius: 14px;">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <h2 class="h5 fw-bold text-dark mb-2">Pelapor Tidak Memerlukan Akun</h2>
                <p class="text-secondary small mb-3" style="line-height: 1.6;">
                    Sesuai ketetapan rancangan sistem <strong>SIPFK (PRD Seksi 3)</strong>, mahasiswa, dosen, dan seluruh sivitas akademika <strong>dapat langsung menyampaikan keluhan tanpa perlu registrasi atau login</strong>.
                </p>

                <div class="p-3 rounded-3 border bg-light text-start mb-3 small text-secondary">
                    <div class="fw-semibold text-dark mb-1 d-flex align-items-center gap-1">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        <span>Keuntungan Alur Tanpa Akun:</span>
                    </div>
                    <ul class="mb-0 ps-3">
                        <li>Pengaduan dapat dikirimkan secara instan saat kendala terjadi di kelas.</li>
                        <li>Tidak terhalang lupa kata sandi atau kendala aktivasi akun email.</li>
                        <li>Identitas tetap tercatat rapi melalui nama pelapor dan lokasi sarana.</li>
                    </ul>
                </div>

                <a href="{{ url('/#form-pengaduan') }}" class="btn btn-primary w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-semibold" style="border-radius: 12px; font-size: 0.95rem;">
                    <i class="bi bi-pencil-square"></i>
                    <span>Langsung Isi Formulir Pengaduan</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
