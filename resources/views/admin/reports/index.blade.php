@extends('layouts.admin')

@section('title', 'Laporan Pengaduan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan Pengaduan</li>
@endsection

@section('page_title', 'Laporan Rekapitulasi Pengaduan')
@section('page_subtitle', 'Filter dan cetak laporan resmi pengaduan fasilitas kampus dalam format dokumen PDF.')

@section('header_actions')
    <button type="button" class="btn btn-outline-secondary btn-sm bg-white border rounded-pill px-3 py-2" onclick="window.print()">
        <i class="bi bi-printer-fill"></i>
        <span>Cetak Tampilan</span>
    </button>
    <a href="#" class="btn-pill-dark" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;" title="Fitur Ekspor PDF (Diintegrasikan oleh Anggota 5)">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Unduh File PDF</span>
    </a>
@endsection

@section('content')
{{-- Filter Laporan --}}
<div class="card card-sipfk border-0 shadow-sm mb-4 d-print-none bg-white" style="border-radius: var(--radius-xl);">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_start_date" class="form-label-zd">Dari Tanggal</label>
                <input type="date" id="report_start_date" name="start_date" class="form-control form-control-zd" value="{{ request('start_date', date('Y-m-01')) }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_end_date" class="form-label-zd">Sampai Tanggal</label>
                <input type="date" id="report_end_date" name="end_date" class="form-control form-control-zd" value="{{ request('end_date', date('Y-m-d')) }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_status" class="form-label-zd">Filter Status</label>
                <select id="report_status" name="status" class="form-select form-select-zd">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <button type="submit" class="btn-pill-dark w-100 justify-content-center py-2" style="font-size: 0.875rem;">
                    <i class="bi bi-filter"></i>
                    <span>Tampilkan Laporan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Preview Lembar Laporan PDF (Sesuai Section 5.2 PRD) --}}
<div class="card card-sipfk border-0 shadow-sm p-4 p-md-5 bg-white" style="border-radius: var(--radius-xl);">
    {{-- Header Kop Laporan Kampus --}}
    <div class="text-center border-bottom pb-4 mb-4">
        <div class="d-inline-flex align-items-center justify-content-center gap-3 mb-2">
            <img src="{{ asset('images/logo-sipfk-icon.svg') }}" alt="Logo SIPFK" width="44" height="51" style="width: 44px; height: 51px;">
            <div class="brand-wordmark text-start">
                <span class="brand-wordmark-title" style="font-size: 1.55rem;">SIPFK.</span>
                <span class="brand-wordmark-tagline" style="font-size: 0.62rem;">Layanan Aspirasi &amp; Sarana Prasarana Kampus</span>
            </div>
        </div>
        <h2 class="h5 fw-bold text-dark text-uppercase mb-1 mt-2">Sistem Informasi Pengaduan Fasilitas Kampus (SIPFK)</h2>
        <h3 class="h6 fw-normal text-secondary mb-1">Laporan Rekapitulasi Penanganan Sarana dan Prasarana</h3>
        <div class="small text-muted">
            Periode: <strong>{{ request('start_date', date('01 M Y')) }}</strong> s.d. <strong>{{ request('end_date', date('d M Y')) }}</strong> &bull; Dicetak: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>

    {{-- Tabel Cetak Laporan (table-responsive untuk viewport ponsel & desktop) --}}
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle small mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 140px;">Pelapor</th>
                    <th style="width: 130px;">Kategori</th>
                    <th style="width: 160px;">Lokasi</th>
                    <th>Deskripsi Kerusakan</th>
                    <th style="width: 90px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">17/11/2026</td>
                    <td>Budi Pratama</td>
                    <td>Teknologi Informasi</td>
                    <td>Gedung B, Lab Komputer</td>
                    <td>Kabel HDMI proyektor putus, tampilan tidak muncul.</td>
                    <td class="text-center">
                        <span class="badge-status badge-status-baru"><span class="badge-status-dot"></span> Baru</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">16/11/2026</td>
                    <td>Siti Nurhaliza</td>
                    <td>Air & Sanitasi</td>
                    <td>Gedung A, Toilet Lantai 1</td>
                    <td>Kran wastafel bocor dan menggenangi koridor.</td>
                    <td class="text-center">
                        <span class="badge-status badge-status-diproses"><span class="badge-status-dot"></span> Diproses</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="text-center">15/11/2026</td>
                    <td>Hendro Santoso</td>
                    <td>Listrik & Kelistrikan</td>
                    <td>Ruang Dosen Gedung C</td>
                    <td>Lampu berkedip dan stop kontak mengeluarkan bau hangus.</td>
                    <td class="text-center">
                        <span class="badge-status badge-status-selesai"><span class="badge-status-dot"></span> Selesai</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted small">
        <div>SIPFK TRAS Kelompok G5 &bull; Dicetak oleh: {{ Auth::user()->name ?? 'Admin SIPFK' }}</div>
        <div>Total Data: 3 Rekapitulasi</div>
    </div>
</div>
@endsection
