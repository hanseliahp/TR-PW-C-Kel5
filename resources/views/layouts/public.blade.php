<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPFK - Sistem Informasi Pengaduan Fasilitas Kampus. Layanan terpadu aspirasi dan tindak lanjut sarana dan prasarana kampus.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIPFK - Layanan Pengaduan Fasilitas Kampus')</title>

    <!-- Bootstrap 5.3.3 CSS (Offline Local Vendor with CDN Fallback) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';">
    <!-- SIPFK Custom Design System (Accessible, Anti-AI-Slop, Clean Civic UI) -->
    <link rel="stylesheet" href="{{ asset('css/sipfk-custom.css') }}">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Aksesibilitas: Skip Link untuk pembaca layar & navigasi keyboard (WCAG 2.1) -->
    <a href="#main-content" class="skip-link">Loncat ke Konten Utama</a>

    <!-- Navbar Publik -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-sipfk sticky-top" aria-label="Navigasi Utama Publik">
            <div class="container">
                <a class="navbar-brand-sipfk" href="{{ url('/') }}">
                    <div class="stat-card-icon-bubble bg-primary text-white" style="width: 36px; height: 36px; font-size: 1.1rem; border-radius: 6px;">
                        <i class="bi bi-buildings-fill" aria-hidden="true"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-5 fw-bold lh-1 text-dark">SIPFK</span>
                        <span class="text-muted" style="font-size: 0.72rem; letter-spacing: 0.02em;">Pengaduan Fasilitas Kampus</span>
                    </div>
                </a>

                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublicContent" aria-controls="navbarPublicContent" aria-expanded="false" aria-label="Buka navigasi menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarPublicContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-1 gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium {{ request()->is('/') ? 'active fw-bold text-primary' : 'text-secondary' }}" href="{{ url('/') }}">
                                <i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Form Pengaduan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-secondary" href="#panduan-layanan">
                                <i class="bi bi-info-circle me-1" aria-hidden="true"></i> Panduan
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2 pt-2 pt-lg-0">
                            <a class="btn btn-outline-dark btn-sm px-3 d-inline-flex align-items-center gap-2" href="{{ route('login') }}" style="border-radius: 6px; font-weight: 600;">
                                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                                <span>Portal Admin</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Konten Utama Halaman -->
    <main id="main-content" class="flex-grow-1 py-4 py-md-5">
        <div class="container">
            <!-- Flash Notifikasi & Validasi -->
            @include('components.alert')

            <!-- Yield Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer Publik -->
    <footer class="footer-sipfk mt-auto">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-1 fw-bold text-dark">
                        SIPFK &mdash; Sistem Informasi Pengaduan Fasilitas Kampus
                    </p>
                    <p class="mb-0 text-muted small">
                        Tugas Rancang Akhir Semester (TRAS) &bull; Kelas G &bull; Kelompok G5
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="badge bg-light text-secondary border px-3 py-2 small">
                        <i class="bi bi-check-circle me-1 text-success" aria-hidden="true"></i> Lingkungan Kampus Terpadu
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 Bundle JS (with Popper) -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';"></script>

    @stack('scripts')
</body>
</html>
