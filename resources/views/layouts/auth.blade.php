<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPFK - Halaman Masuk Administrator Kampus">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk Administrator') &mdash; SIPFK</title>

    <!-- Bootstrap 5.3.3 CSS (Offline Local Vendor with CDN Fallback) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';">
    <!-- SIPFK Custom Design System -->
    <link rel="stylesheet" href="{{ asset('css/sipfk-custom.css') }}">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100 justify-content-center py-4 bg-light">
    <!-- Aksesibilitas: Skip Link -->
    <a href="#auth-card" class="skip-link">Loncat ke Formulir Masuk</a>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="navbar-brand-sipfk justify-content-center mb-2">
                        <div class="stat-card-icon-bubble bg-primary text-white" style="width: 44px; height: 44px; font-size: 1.35rem; border-radius: 8px;">
                            <i class="bi bi-buildings-fill" aria-hidden="true"></i>
                        </div>
                    </a>
                    <h1 class="h4 fw-bold text-dark mb-1">SIPFK Administrator</h1>
                    <p class="text-muted small mb-0">Sistem Informasi Pengaduan Fasilitas Kampus</p>
                </div>

                <div id="auth-card">
                    <!-- Notifikasi & Alert -->
                    @include('components.alert')

                    <!-- Card Body -->
                    @yield('content')
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none small text-secondary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Halaman Publik</span>
                    </a>
                    <div class="text-muted small mt-2">
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
