@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('page_title', 'Dashboard Ringkasan Fasilitas')
@section('page_subtitle', 'Pantau metrik status pengaduan sarana dan prasarana kampus secara langsung.')

@section('header_actions')
    <a href="{{ route('admin.complaints.index') }}" class="btn-pill-dark" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
        <i class="bi bi-card-checklist"></i>
        <span>Kelola Pengaduan</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm bg-white border rounded-pill px-3 py-2">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Unduh Laporan</span>
    </a>
@endsection

@section('content')
{{-- 4 Kartu Statistik Dashboard (Terinspirasi Bento Metric Card Gambar 3) --}}
<div class="row g-3 g-md-4 mb-4">
    {{-- 1. Total Pengaduan (Hero Dark Accent Card) --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card-dark p-4" style="border-radius: var(--radius-lg);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase tracking-wider small fw-bold text-info" style="font-size: 0.75rem;">Total Laporan</span>
                <span class="badge rounded-pill bg-white text-dark small px-2 py-1"><i class="bi bi-collection-fill"></i></span>
            </div>
            <div>
                <div class="metric-number mb-1" style="font-size: 2.75rem;">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="metric-label small">Seluruh Pengaduan</div>
                <p class="metric-desc" style="font-size: 0.8rem;">Akumulasi keluhan sarana masuk.</p>
            </div>
        </div>
    </div>

    {{-- 2. Status Baru --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card-light p-4" style="border-radius: var(--radius-lg); border-top: 4px solid var(--zd-status-baru-dot);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase tracking-wider small fw-bold text-muted" style="font-size: 0.75rem;">Pengaduan Baru</span>
                <span class="badge-status badge-status-baru"><span class="badge-status-dot"></span> Baru</span>
            </div>
            <div>
                <div class="metric-number text-dark mb-1" style="font-size: 2.75rem;">{{ number_format($stats['baru'] ?? 0) }}</div>
                <div class="metric-label small">Menunggu Verifikasi</div>
                <p class="metric-desc" style="font-size: 0.8rem;">Laporan baru perlu ditinjau admin.</p>
            </div>
        </div>
    </div>

    {{-- 3. Status Diproses --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card-light p-4" style="border-radius: var(--radius-lg); border-top: 4px solid var(--zd-status-proses-dot);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase tracking-wider small fw-bold text-muted" style="font-size: 0.75rem;">Sedang Diproses</span>
                <span class="badge-status badge-status-diproses"><span class="badge-status-dot"></span> Diproses</span>
            </div>
            <div>
                <div class="metric-number text-dark mb-1" style="font-size: 2.75rem;">{{ number_format($stats['diproses'] ?? 0) }}</div>
                <div class="metric-label small">Tindakan Lapangan</div>
                <p class="metric-desc" style="font-size: 0.8rem;">Teknisi sedang memperbaiki sarana.</p>
            </div>
        </div>
    </div>

    {{-- 4. Status Selesai --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="metric-card-light p-4" style="border-radius: var(--radius-lg); border-top: 4px solid var(--zd-status-selesai-dot);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase tracking-wider small fw-bold text-muted" style="font-size: 0.75rem;">Tuntas Selesai</span>
                <span class="badge-status badge-status-selesai"><span class="badge-status-dot"></span> Selesai</span>
            </div>
            <div>
                <div class="metric-number text-dark mb-1" style="font-size: 2.75rem;">{{ number_format($stats['selesai'] ?? 0) }}</div>
                <div class="metric-label small">Fasilitas Normal</div>
                <p class="metric-desc" style="font-size: 0.8rem;">Perbaikan tuntas dan teruji.</p>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Ringkasan Pengaduan Terbaru --}}
<div class="card card-sipfk border-0 shadow-sm mb-4" style="border-radius: var(--radius-xl); overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h6 fw-bold text-dark mb-0">Laporan Pengaduan Terbaru</h2>
            <small class="text-muted">Daftar aspirasi masuk yang memerlukan perhatian atau pemantauan tindak lanjut.</small>
        </div>
        <a href="{{ route('admin.complaints.index') }}" class="small fw-semibold text-decoration-none text-dark d-inline-flex align-items-center gap-1">
            <span>Lihat Semua</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-zendesk align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 60px;">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Pelapor</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Lokasi Fasilitas</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-end" style="width: 110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($recentComplaints) && count($recentComplaints) > 0)
                    @foreach($recentComplaints as $index => $item)
                        <tr>
                            <td class="text-muted small fw-medium">{{ $index + 1 }}</td>
                            <td class="text-nowrap small">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($item->reported_at)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $item->reporter_name }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $item->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-secondary small">{{ Str::limit($item->location, 35) }}</td>
                            <td>
                                @include('components.status-badge', ['status' => $item->status])
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.complaints.show', $item->id) }}" class="btn btn-outline-secondary btn-sm btn-icon-sm rounded-circle" title="Buka Detail Pengaduan">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- Preview Dummy Data jika database belum diisi --}}
                    <tr>
                        <td class="text-muted small">1</td>
                        <td class="text-nowrap small"><i class="bi bi-calendar3 me-1 text-muted"></i> 17 Nov 2026</td>
                        <td><span class="fw-semibold text-dark">Ahmad Faiz</span></td>
                        <td><span class="badge rounded-pill bg-light text-dark border">Teknologi Informasi</span></td>
                        <td class="text-secondary small">Gedung B, Lab Komputer 3</td>
                        <td>
                            @include('components.status-badge', ['status' => 'baru'])
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.complaints.show', 1) }}" class="btn btn-outline-secondary btn-sm btn-icon-sm rounded-circle" title="Buka Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted small">2</td>
                        <td class="text-nowrap small"><i class="bi bi-calendar3 me-1 text-muted"></i> 16 Nov 2026</td>
                        <td><span class="fw-semibold text-dark">Rina Kusumawati</span></td>
                        <td><span class="badge rounded-pill bg-light text-dark border">Air & Sanitasi</span></td>
                        <td class="text-secondary small">Toilet Lantai 1 Gedung Utama</td>
                        <td>
                            @include('components.status-badge', ['status' => 'diproses'])
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.complaints.show', 2) }}" class="btn btn-outline-secondary btn-sm btn-icon-sm rounded-circle" title="Buka Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted small">3</td>
                        <td class="text-nowrap small"><i class="bi bi-calendar3 me-1 text-muted"></i> 15 Nov 2026</td>
                        <td><span class="fw-semibold text-dark">Dimas Prasetyo</span></td>
                        <td><span class="badge rounded-pill bg-light text-dark border">Listrik & Kelistrikan</span></td>
                        <td class="text-secondary small">Ruang Kuliah 301</td>
                        <td>
                            @include('components.status-badge', ['status' => 'selesai'])
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.complaints.show', 3) }}" class="btn btn-outline-secondary btn-sm btn-icon-sm rounded-circle" title="Buka Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
