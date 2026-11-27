<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPFK - Sistem Informasi Pengaduan Fasilitas Kampus. Solusi nyata, pemantauan transparan, dan penanganan sarana prasarana kampus terpadu.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIPFK - Solusi Nyata Penanganan Fasilitas Kampus')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-sipfk-icon.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans (Human-crafted Modern Civic Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS (Offline Local Vendor with CDN Fallback) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';">
    <!-- SIPFK Design System -->
    <link rel="stylesheet" href="{{ asset('css/sipfk-custom.css') }}?v={{ filemtime(public_path('css/sipfk-custom.css')) }}">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Aksesibilitas: Skip Link -->
    <a href="#main-content" class="skip-link">Loncat ke Konten Utama</a>

    <!-- Top Navigation Bar (Zendesk-Style Clean Navbar) -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-zendesk" aria-label="Navigasi Utama Publik">
            <div class="container">
                <!-- Brand Logo -->
                <a class="navbar-brand-zd" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="34" height="39" class="brand-logo-shield">
                    <div class="brand-wordmark">
                        <span class="brand-wordmark-title">SIPFK.</span>
                        <span class="brand-wordmark-tagline">Layanan Aspirasi &amp;<br>Sarana Prasarana Kampus</span>
                    </div>
                </a>

                <!-- Mobile Hamburger Toggler -->
                <button class="navbar-toggler border-0 shadow-none px-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublicContent" aria-controls="navbarPublicContent" aria-expanded="false" aria-label="Buka navigasi menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Nav Links -->
                <div class="collapse navbar-collapse" id="navbarPublicContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center gap-1 gap-lg-3">
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium {{ request()->is('/') ? 'active fw-semibold text-dark' : 'text-secondary' }}" href="{{ url('/') }}">
                                Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-secondary" href="#form-pengaduan">
                                Ajukan Pengaduan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-secondary" href="#data-statistik">
                                Statistik Layanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-medium text-secondary" href="#panduan-layanan">
                                Panduan & FAQ
                            </a>
                        </li>
                    </ul>

                    <!-- Right Action Button -->
                    <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                        <a href="#form-pengaduan" class="btn btn-link text-decoration-none text-dark fw-semibold d-none d-xl-inline-block small">
                            Lapor Cepat
                        </a>
                        <a class="btn-pill-dark" href="{{ route('login') }}" style="padding: 0.55rem 1.35rem; font-size: 0.875rem;">
                            <i class="bi bi-shield-lock"></i>
                            <span>Portal Admin</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Konten Utama Halaman -->
    <main id="main-content" class="flex-grow-1">
        <!-- Yield Content -->
        @yield('content')
    </main>

    <!-- Footer Publik (Zendesk-Inspired Multi-Column Minimalist) -->
    <footer class="footer-sipfk bg-white border-top mt-auto pt-5 pb-4">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- Brand & Mission -->
                <div class="col-12 col-lg-4">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
                        <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="34" height="39" class="brand-logo-shield">
                        <div class="brand-wordmark">
                            <span class="brand-wordmark-title">SIPFK.</span>
                            <span class="brand-wordmark-tagline">Layanan Aspirasi &amp;<br>Sarana Prasarana Kampus</span>
                        </div>
                    </a>
                    <p class="text-secondary small mb-4 pe-lg-4" style="line-height: 1.7;">
                        Sistem Informasi Pengaduan Fasilitas Kampus adalah platform terpadu pencatatan kerusakan sarana, verifikasi divisi pemeliharaan, serta pemantauan tindak lanjut secara transparan dan akuntabel.
                    </p>
                    <div class="d-flex align-items-center gap-3 text-secondary">
                        <a href="https://www.instagram.com/ftisarpras/" target="_blank" rel="noopener noreferrer" class="text-secondary fs-5" aria-label="Instagram Sarpras FTI UKSW" title="Instagram Sarpras FTI UKSW (@ftisarpras)"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/@LaboranFTIUKSW" target="_blank" rel="noopener noreferrer" class="text-secondary fs-5" aria-label="YouTube Laboran FTI UKSW" title="YouTube Laboran FTI UKSW"><i class="bi bi-youtube"></i></a>
                        <a href="https://fti.uksw.edu/" target="_blank" rel="noopener noreferrer" class="text-secondary fs-5" aria-label="Website Resmi FTI UKSW" title="Website Resmi FTI UKSW"><i class="bi bi-globe2"></i></a>
                        <a href="mailto:fti.laboran@adm.uksw.edu" class="text-secondary fs-5" aria-label="Email Sarpras FTI UKSW" title="Email Sarpras FTI UKSW (fti.laboran@adm.uksw.edu)"><i class="bi bi-envelope-fill"></i></a>
                    </div>
                </div>

                <!-- Kolom 2: Layanan Fasilitas -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h4 class="fs-6 fw-bold text-dark mb-3">Layanan</h4>
                    <ul class="list-unstyled d-flex flex-column gap-2 small text-secondary mb-0">
                        <li><a href="#form-pengaduan" class="text-decoration-none text-secondary">Pengaduan Baru</a></li>
                        <li><a href="#data-statistik" class="text-decoration-none text-secondary">Statistik Layanan</a></li>
                        <li><a href="#alur-penanganan" class="text-decoration-none text-secondary">Alur Penanganan</a></li>
                        <li><a href="#panduan-layanan" class="text-decoration-none text-secondary">Panduan Pengguna</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Cakupan Unit -->
                <div class="col-6 col-md-3 col-lg-3">
                    <h4 class="fs-6 fw-bold text-dark mb-3">Cakupan Fasilitas</h4>
                    <ul class="list-unstyled d-flex flex-column gap-2 small text-secondary mb-0">
                        <li><span class="text-secondary">Listrik & Kelistrikan</span></li>
                        <li><span class="text-secondary">Air, Sanitasi & Toilet</span></li>
                        <li><span class="text-secondary">Teknologi Informasi & Audio</span></li>
                        <li><span class="text-secondary">Perabot Ruang Kelas & Teori</span></li>
                        <li><span class="text-secondary">Gedung & Infrastruktur Fisik</span></li>
                    </ul>
                </div>

                <!-- Kolom 4: Kredensial & Bantuan -->
                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fs-6 fw-bold text-dark mb-3">Bantuan Cepat</h4>
                    <p class="text-secondary small mb-3">
                        Ada kondisi darurat fasilitas yang membahayakan keselamatan kampus?
                    </p>
                    <a href="#form-pengaduan" class="btn-pill-dark w-100 justify-content-center py-2" style="font-size: 0.875rem;">
                        <i class="bi bi-telephone-outbound me-1"></i> Kontak Tim Sarpras
                    </a>
                </div>
            </div>

            <div class="pt-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 small text-muted">
                <div>
                    &copy; 2026 SIPFK. Tugas Rancang Akhir Semester (TRAS) &bull; Kelas G &bull; Kelompok G5.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span>Bahasa Indonesia (ID)</span>
                    <span>&bull;</span>
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted">Akses Administrator</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 Bundle JS (with Popper) -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';"></script>

    @stack('scripts')
</body>
</html>
