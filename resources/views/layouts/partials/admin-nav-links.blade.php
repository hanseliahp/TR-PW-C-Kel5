{{--
  Shared Navigation Links (Dipakai oleh Desktop Sidebar & Mobile Offcanvas)
  Memastikan keseragaman struktur menu navigasi dengan gaya Zendesk Platform
--}}

<div class="d-flex flex-column h-100">
    <div class="sidebar-section-label">Ringkasan &amp; Analisis</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link-zd {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard Ringkasan</span>
    </a>

    <div class="sidebar-section-label mt-3">Manajemen Data</div>
    <a href="{{ route('admin.complaints.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
        <i class="bi bi-card-checklist"></i>
        <span>Daftar Pengaduan</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i>
        <span>Kategori Fasilitas</span>
    </a>

    <div class="sidebar-section-label mt-3">Dokumentasi &amp; Laporan</div>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link-zd {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Laporan Pengaduan</span>
    </a>

    <hr class="my-3 text-secondary opacity-25">

    <div class="sidebar-section-label">Tautan Eksternal</div>
    <a href="{{ url('/') }}" target="_blank" class="sidebar-link-zd">
        <i class="bi bi-globe2"></i>
        <span>Web Pengaduan Publik</span>
        <i class="bi bi-arrow-up-right ms-auto" style="width: auto; font-size: 0.75rem; opacity: 0.65;"></i>
    </a>

    <div class="mt-auto pt-4">
        <div class="p-3 bg-light rounded-3 border small text-muted d-flex align-items-center gap-2" style="border-radius: var(--radius-md) !important;">
            <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="28" height="32" style="width: 28px; height: 32px; flex-shrink: 0;">
            <div class="lh-sm">
                <div class="fw-bold text-dark">SIPFK. <span class="fw-normal text-muted" style="font-size: 0.7rem;">v1.0</span></div>
                <div style="font-size: 0.72rem;">Layanan Aspirasi &amp; Sarana Prasarana Kampus</div>
            </div>
        </div>
    </div>
</div>