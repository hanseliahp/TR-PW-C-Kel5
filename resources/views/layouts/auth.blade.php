<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPFK - Halaman Masuk Administrator Kampus">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk Administrator') &mdash; SIPFK</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS (Offline Local Vendor with CDN Fallback) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';">
    <!-- SIPFK Custom Design System -->
    <link rel="stylesheet" href="{{ asset('css/sipfk-custom.css') }}">

    @stack('styles')
</head>
<body class="bg-aura-hero py-4 py-md-5" style="min-height: 100vh; overflow-y: auto; overflow-x: hidden;">
    <!-- Aksesibilitas: Skip Link -->
    <a href="#auth-card" class="skip-link">Loncat ke Formulir Masuk</a>

    <main class="container py-2 py-md-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <!-- Brand Header Atas -->
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                        <div class="brand-icon-box bg-white text-dark shadow-sm" style="width: 44px; height: 44px; font-size: 1.35rem; border-radius: 12px;">
                            <i class="bi bi-buildings-fill" aria-hidden="true"></i>
                        </div>
                        <span class="navbar-brand-zd text-white fs-3">SIPFK<span style="color: var(--zd-accent-purple-lt);">.</span></span>
                    </a>
                    <p class="text-light opacity-75 small mb-0">Sistem Informasi Pengaduan Fasilitas Kampus</p>
                </div>

                <div id="auth-card">
                    <!-- Notifikasi & Alert -->
                    @include('components.alert')

                    <!-- Card Body -->
                    @yield('content')
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none small text-light opacity-75 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Halaman Beranda Publik</span>
                    </a>
                    <div class="text-light opacity-50 small mt-2">
                        &copy; 2026 SIPFK Kelompok G5 &bull; TRAS Pemrograman Web
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';"></script>

    @stack('scripts')
</body>
</html>
