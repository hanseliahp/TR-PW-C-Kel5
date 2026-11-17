@extends('layouts.admin')

@section('title', 'Dashboard Ringkasan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('page_title', 'Dashboard Ringkasan Fasilitas')
@section('page_subtitle', 'Pantau metrik status pengaduan sarana dan prasarana kampus secara langsung.')

@section('header_actions')
    <a href="{{ route('admin.complaints.index') }}" class="btn btn-sipfk-primary btn-sm">
        <i class="bi bi-card-checklist"></i>
        <span>Kelola Pengaduan</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm bg-white border">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Unduh Laporan</span>
    </a>
@endsection

@section('content')
{{-- 4 Kartu Statistik Dashboard (FR-08 & Section 5.1 PRD) --}}
<div class="row g-3 g-md-4 mb-4">
    {{-- 1. Total Pengaduan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @include('components.stat-card', [
            'title' => 'Total Pengaduan',
            'value' => $stats['total'] ?? 0,
            'type'  => 'total',
            'icon'  => 'bi-collection-fill',
            'description' => 'Seluruh laporan fasilitas'
        ])
    </div>

    {{-- 2. Status Baru --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @include('components.stat-card', [
            'title' => 'Pengaduan Baru',
            'value' => $stats['baru'] ?? 0,
            'type'  => 'baru',
            'icon'  => 'bi-clock-history',
            'description' => 'Menunggu verifikasi admin'
        ])
    </div>

    {{-- 3. Status Diproses --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @include('components.stat-card', [
            'title' => 'Sedang Diproses',
            'value' => $stats['diproses'] ?? 0,
            'type'  => 'diproses',
            'icon'  => 'bi-tools',
            'description' => 'Penanganan / perbaikan aktif'
        ])
    </div>

    {{-- 4. Status Selesai --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @include('components.stat-card', [
            'title' => 'Tuntas / Selesai',
            'value' => $stats['selesai'] ?? 0,
            'type'  => 'selesai',
            'icon'  => 'bi-check2-circle',
            'description' => 'Fasilitas telah diperbaiki'
        ])
    </div>
</div>

{{-- Tabel Ringkasan Pengaduan Terbaru --}}
<div class="card card-sipfk border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h6 fw-bold text-dark mb-0">Laporan Pengaduan Terbaru</h2>
            <small class="text-muted">Daftar pengaduan masuk yang memerlukan perhatian atau pemantauan tindak lanjut.</small>
        </div>
        <a href="{{ route('admin.complaints.index') }}" class="small fw-semibold text-decoration-none text-primary">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-sipfk align-middle table-hover mb-0">
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
                                <span class="badge bg-light text-dark border">
                                    {{ $item->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-secondary small">{{ Str::limit($item->location, 35) }}</td>
                            <td>
                                @include('components.status-badge', ['status' => $item->status])
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.complaints.show', $item->id) }}" class="btn btn-outline-primary btn-sm btn-icon-sm" title="Buka Detail Pengaduan">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-1 text-secondary opacity-50"></i>
                            <span class="small fw-semibold text-secondary">Belum ada data pengaduan masuk</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
