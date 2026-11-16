@extends('layouts.admin')

@section('title', 'Laporan Pengaduan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Laporan Pengaduan</li>
@endsection

@section('page_title', 'Laporan Rekapitulasi Pengaduan')
@section('page_subtitle', 'Filter, pratinjau data aktual dari database, dan cetak laporan resmi fasilitas kampus dalam format dokumen PDF.')

@section('header_actions')
    <a href="{{ route('admin.reports.pdf', array_merge(request()->query(), ['stream' => 1])) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Pratinjau PDF di Tab Baru">
        <i class="bi bi-eye-fill"></i>
        <span>Pratinjau PDF</span>
    </a>
    <button type="button" class="btn btn-sipfk-primary btn-sm" onclick="window.print()" title="Cetak langsung tampilan layar">
        <i class="bi bi-printer-fill"></i>
        <span>Cetak Layar</span>
    </button>
    <a href="{{ route('admin.reports.pdf', request()->query()) }}" class="btn btn-danger btn-sm" title="Unduh Berkas PDF Resmi SIPFK">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>Unduh File PDF</span>
    </a>
@endsection

@section('content')
{{-- Filter Laporan --}}
<div class="card card-sipfk border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_start_date" class="form-label-sipfk">Dari Tanggal</label>
                <input type="date" id="report_start_date" name="start_date" class="form-control form-control-sipfk" value="{{ request('start_date') }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_end_date" class="form-label-sipfk">Sampai Tanggal</label>
                <input type="date" id="report_end_date" name="end_date" class="form-control form-control-sipfk" value="{{ request('end_date') }}">
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="report_status" class="form-label-sipfk">Filter Status</label>
                <select id="report_status" name="status" class="form-select form-select-sipfk">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sipfk-primary flex-grow-1 justify-content-center">
                    <i class="bi bi-filter"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['start_date', 'end_date', 'status']))
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Preview Lembar Laporan PDF (Sesuai Section 5.2 & FR-09 PRD) --}}
<div class="card card-sipfk border-0 shadow-sm p-4 p-md-5 bg-white">
    {{-- Header Kop Laporan Kampus --}}
    <div class="text-center border-bottom pb-4 mb-4">
        <h2 class="h5 fw-bold text-dark text-uppercase mb-1">Sistem Informasi Pengaduan Fasilitas Kampus (SIPFK)</h2>
        <h3 class="h6 fw-normal text-secondary mb-1">Laporan Rekapitulasi Penanganan Sarana dan Prasarana</h3>
        <div class="small text-muted">
            Periode: <strong>{{ request('start_date') ? date('d M Y', strtotime(request('start_date'))) : 'Semua Data' }}</strong> s.d. <strong>{{ request('end_date') ? date('d M Y', strtotime(request('end_date'))) : 'Hari Ini' }}</strong> &bull; Dicetak: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>

    {{-- Tabel Cetak Laporan (Data Aktual Database) --}}
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle small mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 140px;">Pelapor</th>
                    <th style="width: 130px;">Kategori</th>
                    <th style="width: 160px;">Lokasi</th>
                    <th>Deskripsi Masalah &amp; Tindak Lanjut</th>
                    <th style="width: 90px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $index => $c)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($c->reported_at)) }}</td>
                        <td><span class="fw-semibold text-dark">{{ $c->reporter_name }}</span></td>
                        <td>{{ $c->category->name ?? '-' }}</td>
                        <td>{{ $c->location }}</td>
                        <td>
                            <div>{{ $c->description }}</div>
                            @if($c->followUps->isNotEmpty())
                                <div class="mt-1 p-2 bg-light rounded border-start border-3 border-info small">
                                    <strong class="text-secondary d-block" style="font-size: 0.75rem;">
                                        <i class="bi bi-chat-left-dots-fill text-info me-1"></i>Catatan Tindak Lanjut:
                                    </strong>
                                    @foreach($c->followUps as $fu)
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            &bull; <span class="fw-medium text-dark">{{ $fu->admin->name ?? 'Admin' }}</span>: {{ $fu->note }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($c->status === 'baru')
                                <span class="badge bg-warning-subtle text-warning-emphasis">baru</span>
                            @elseif($c->status === 'diproses')
                                <span class="badge bg-primary-subtle text-primary-emphasis">diproses</span>
                            @else
                                <span class="badge bg-success-subtle text-success-emphasis">selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                            <em>Tidak ada data pengaduan yang sesuai dengan kriteria filter.</em>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-muted small">
        <div>SIPFK TRAS Kelompok G5 &bull; Dicetak oleh: <strong>{{ Auth::user()->name ?? 'Admin SIPFK' }}</strong></div>
        <div>Total Data: <strong>{{ $complaints->count() }} Pengaduan</strong></div>
    </div>
</div>
@endsection
