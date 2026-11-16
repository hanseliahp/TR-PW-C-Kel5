@extends('layouts.auth')

@section('title', 'Masuk Administrator - SIPFK')

@section('content')
<div class="card card-sipfk border-0 shadow-sm p-3 p-sm-4">
    <div class="mb-3">
        <h2 class="h5 fw-bold text-dark mb-1">Masuk ke Panel Pengelola</h2>
        <p class="text-muted small">Silakan masukkan email dan kata sandi akun admin kampus Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Input Email --}}
        <div class="mb-3">
            <label for="email" class="form-label-sipfk">
                Alamat Email <span class="required-star">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-envelope"></i>
                </span>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control form-control-sipfk border-start-0 @error('email') is-invalid @enderror" 
                    value="{{ old('email', 'admin@sipfk.test') }}" 
                    placeholder="nama@kampus.ac.id" 
                    required 
                    autocomplete="email" 
                    autofocus
                    aria-describedby="emailFeedback"
                >
            </div>
            @error('email')
                <div id="emailFeedback" class="invalid-feedback d-block small mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Input Password --}}
        <div class="mb-3">
            <label for="password" class="form-label-sipfk">
                Kata Sandi <span class="required-star">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-lock"></i>
                </span>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control form-control-sipfk border-start-0 @error('password') is-invalid @enderror" 
                    placeholder="••••••••" 
                    required 
                    autocomplete="current-password"
                    aria-describedby="passwordFeedback"
                >
            </div>
            @error('password')
                <div id="passwordFeedback" class="invalid-feedback d-block small mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label small text-secondary" for="remember">
                Ingat sesi saya pada perangkat ini
            </label>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-sipfk-primary w-100 justify-content-center">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk ke Panel Admin</span>
        </button>
    </form>

    {{-- Petunjuk Akun Demo Pengujian (Sesuai Seeder & Skenario TC-01) --}}
    <div class="mt-4 pt-3 border-top">
        <div class="alert alert-light border small text-muted mb-0 py-2 px-3">
            <div class="fw-semibold text-dark d-flex align-items-center gap-1 mb-1">
                <i class="bi bi-info-circle text-primary"></i>
                <span>Kredensial Demo Uji (DatabaseSeeder):</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <span>Email:</span>
                <code>admin@sipfk.test</code>
            </div>
            <div class="d-flex justify-content-between">
                <span>Password:</span>
                <code>password</code>
            </div>
        </div>
    </div>
</div>
@endsection
