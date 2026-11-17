{{--
  Shared Navigation Links (Dipakai oleh Desktop Sidebar & Mobile Offcanvas)
  Memastikan keseragaman struktur menu navigasi dengan gaya Zendesk Platform
--}}

<div class="d-flex flex-column h-100">
    <div class="text-uppercase tracking-wider fw-bold text-muted small px-3 py-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Ringkasan & Analisis</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link-zd {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard Ringkasan</span>
    </a>

    <div class="text-uppercase tracking-wider fw-bold text-muted small px-3 pt-3 pb-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Manajemen Data</div>
    <a href="{{ route('admin.complaints.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
        <i class="bi bi-card-checklist"></i>
        <span>Daftar Pengaduan</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i>
        <span>Kategori Fasilitas</span>
    </a>

    <div class="text-uppercase tracking-wider fw-bold text-muted small px-3 pt-3 pb-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Dokumentasi & Laporan</div>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Laporan Pengaduan</span>
    </a>

    <hr class="my-3 text-secondary opacity-25">

    <div class="text-uppercase tracking-wider fw-bold text-muted small px-3 pb-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Tautan Eksternal</div>
    <a href="{{ url('/') }}" target="_blank" class="sidebar-link-zd">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Formulir Pengaduan Publik</span>
    </a>

    <div class="mt-auto pt-4">
        <div class="p-3 bg-light rounded-3 border small text-muted" style="border-radius: var(--radius-md) !important;">
            <div class="fw-bold text-dark mb-1">SIPFK v1.0</div>
            <div>Sistem Pengaduan Sarana Kampus &bull; Kelompok G5</div>
        </div>
    </div>
</div>