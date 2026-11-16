{{--
  Shared Navigation Links (Dipakai oleh Desktop Sidebar & Mobile Offcanvas)
  Memastikan keseragaman struktur menu navigasi (AC-10, NFR-02)
--}}

<div class="d-flex flex-column h-100">
    <div class="sidebar-section-title">Ringkasan & Analisis</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>

    <div class="sidebar-section-title">Manajemen Data</div>
    <a href="{{ route('admin.complaints.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
        <i class="bi bi-card-checklist"></i>
        <span>Daftar Pengaduan</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i>
        <span>Kategori Fasilitas</span>
    </a>

    <div class="sidebar-section-title">Dokumentasi & Laporan</div>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Laporan Pengaduan</span>
    </a>

    <hr class="my-3 text-secondary opacity-25">

    <div class="sidebar-section-title">Tautan Publik</div>
    <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-link">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Formulir Pengaduan</span>
    </a>

    <div class="mt-auto pt-3">
        <div class="p-3 bg-light rounded border small text-muted">
            <div class="fw-semibold text-dark mb-1">SIPFK v1.0</div>
            <div>Sistem Pengaduan Fasilitas Kampus &bull; Kelompok G5</div>
        </div>
    </div>
</div>
