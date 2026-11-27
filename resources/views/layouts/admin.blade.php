<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIPFK Admin Panel - Pengelolaan Pengaduan dan Tindak Lanjut Fasilitas Kampus.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') &mdash; SIPFK</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-sipfk-icon.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
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
<body style="background-color: var(--zd-bg-canvas);">
    <!-- Aksesibilitas: Skip Link -->
    <a href="#admin-main-content" class="skip-link">Loncat ke Konten Panel Admin</a>

    <!-- Top Navigation Bar (Zendesk Console Style) -->
    <header>
        <nav class="navbar navbar-expand navbar-zendesk sticky-top py-2 px-3 px-md-4" aria-label="Navigasi Atas Admin">
            <div class="container-fluid px-0">
                <!-- Hamburger Menu Toggler (Mobile / Tablet < 992px) -->
                <button class="btn btn-outline-secondary d-lg-none me-2 btn-icon-sm rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas" aria-label="Buka navigasi panel">
                    <i class="bi bi-list fs-5" aria-hidden="true"></i>
                </button>

                <!-- Brand Admin -->
                <a class="navbar-brand-zd me-3" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="30" height="35" class="brand-logo-shield-sm">
                    <div class="brand-wordmark d-none d-sm-flex">
                        <span class="brand-wordmark-title" style="font-size: 1.15rem;">SIPFK.</span>
                        <span class="brand-wordmark-tagline" style="font-size: 0.48rem;">Layanan Aspirasi &amp; Sarana Prasarana</span>
                    </div>
                    <span class="badge rounded-pill bg-dark text-white px-2 py-1 ms-1" style="font-size: 0.68rem;">Admin</span>
                </a>

                <!-- Navigasi Kanan (User & Logout) -->
                <div class="ms-auto d-flex align-items-center gap-2 gap-md-3">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-light btn-sm text-secondary border rounded-pill d-none d-md-inline-flex align-items-center gap-1 px-3" title="Lihat Tampilan Publik Form Pengaduan">
                        <i class="bi bi-box-arrow-up-right small"></i>
                        <span>Web Publik</span>
                    </a>

                    <!-- User Identity Badge (Zendesk Pill) -->
                    <div class="d-flex align-items-center gap-2 ps-2 border-start">
                        <div class="stat-card-icon-bubble d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-circle" style="width: 34px; height: 34px; font-size: 0.95rem;">
                            <i class="bi bi-person-fill" aria-hidden="true"></i>
                        </div>
                        <div class="d-none d-sm-block text-start lh-1">
                            <span class="d-block fw-semibold text-dark small">{{ Auth::user()->name ?? 'Admin SIPFK' }}</span>
                            <span class="text-muted" style="font-size: 0.72rem;">Divisi Pemeliharaan</span>
                        </div>
                    </div>

                    <!-- Logout Button Form -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-1">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill d-inline-flex align-items-center gap-1 px-3" title="Keluar dari Panel Admin">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-md-inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid px-0">
        <div class="d-flex">
            <!-- Sidebar Desktop (Sticky & Visible at >= 992px) -->
            <aside class="admin-sidebar-zd d-none d-lg-flex p-3" aria-label="Sidebar Navigasi Desktop">
                @include('layouts.partials.admin-nav-links')
            </aside>

            <!-- Sidebar Mobile (Bootstrap Offcanvas < 992px) -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarOffcanvasLabel" style="width: 280px;">
                <div class="offcanvas-header border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="30" height="35" class="brand-logo-shield-sm">
                        <div class="brand-wordmark">
                            <h5 class="offcanvas-title brand-wordmark-title mb-0" id="adminSidebarOffcanvasLabel" style="font-size: 1.15rem;">SIPFK.</h5>
                            <span class="brand-wordmark-tagline" style="font-size: 0.5rem;">Menu Navigasi Panel Admin</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Tutup menu"></button>
                </div>
                <div class="offcanvas-body p-3">
                    @include('layouts.partials.admin-nav-links')
                </div>
            </div>

            <!-- Area Konten Utama Panel Admin -->
            <main id="admin-main-content" class="flex-grow-1 p-3 p-md-4 p-xl-5" style="min-width: 0;">
                <!-- Header Halaman & Breadcrumb -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1 small">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                        <h1 class="h3 fw-bold text-dark mb-0 display-title">@yield('page_title', 'Dashboard')</h1>
                        @hasSection('page_subtitle')
                            <p class="text-secondary small mb-0 mt-1">@yield('page_subtitle')</p>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @yield('header_actions')
                    </div>
                </div>

                <!-- Notifikasi & Alert Global -->
                @include('components.alert')

                <!-- Konten Utama Tampilan Halaman -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Reusable Delete Confirmation Modal (Universal) -->
    @include('components.delete-confirm-modal')

    <!-- Bootstrap 5.3.3 Bundle JS (with Popper) -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';"></script>

    @stack('scripts')
</body>
</html>
