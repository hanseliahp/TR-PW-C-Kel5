@extends('layouts.admin')

@section('title', 'Detail Pengaduan #' . ($complaint->id ?? '1'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.complaints.index') }}" class="text-decoration-none text-muted">Daftar Pengaduan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $complaint->id ?? '1' }}</li>
@endsection

@section('page_title', 'Detail & Tindak Lanjut Pengaduan')
@section('page_subtitle', 'Tinjau rincian pengaduan kerusakan fasilitas dan catat histori perkembangan penanganan.')

@section('header_actions')
    <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary btn-sm bg-white border rounded-pill px-3 py-2">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Daftar</span>
    </a>
    <button 
        type="button" 
        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2"
        data-bs-toggle="modal" 
        data-bs-target="#deleteConfirmModal"
        data-action="{{ isset($complaint) ? route('admin.complaints.destroy', $complaint->id) : '#' }}"
        data-name="Pengaduan #{{ $complaint->id ?? '1' }}"
    >
        <i class="bi bi-trash3"></i>
        <span>Hapus Pengaduan</span>
    </button>
@endsection

@section('content')
<div class="row g-4">
    {{-- Kolom Kiri: Lembar Informasi Pengaduan Pelapor --}}
    <div class="col-12 col-lg-7">
        <div class="card card-sipfk border-0 shadow-sm p-4 mb-4 bg-white" style="border-radius: var(--radius-xl);">
            <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                <div>
                    <span class="badge rounded-pill bg-light text-muted border mb-1">Tiket #{{ $complaint->id ?? '1' }}</span>
                    <h2 class="h5 fw-bold text-dark mb-0">Informasi Laporan Masuk</h2>
                </div>
                <div>
                    @include('components.status-badge', ['status' => $complaint->status ?? 'diproses'])
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <span class="text-muted small d-block">Nama Pelapor</span>
                    <strong class="text-dark fs-6">{{ $complaint->reporter_name ?? 'Budi Pratama' }}</strong>
                </div>
                <div class="col-12 col-sm-6">
                    <span class="text-muted small d-block">Tanggal Dilaporkan</span>
                    <strong class="text-dark fs-6">
                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                        {{ isset($complaint->reported_at) ? \Carbon\Carbon::parse($complaint->reported_at)->translatedFormat('l, d F Y') : 'Senin, 16 November 2026' }}
                    </strong>
                </div>
                <div class="col-12 col-sm-6">
                    <span class="text-muted small d-block">Kategori Sarana</span>
                    <span class="badge rounded-pill bg-light text-dark border mt-1">
                        {{ $complaint->category->name ?? 'Teknologi Informasi & Audio' }}
                    </span>
                </div>
                <div class="col-12 col-sm-6">
                    <span class="text-muted small d-block">Lokasi Fasilitas (Manual)</span>
                    <div class="fw-semibold text-secondary mt-1">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        {{ $complaint->location ?? 'Gedung B Lantai 3, Lab Jaringan Komputer' }}
                    </div>
                </div>
            </div>

            <div class="border-top pt-3">
                <span class="text-muted small d-block mb-2 fw-semibold">Deskripsi Masalah / Kerusakan:</span>
                <div class="p-3 bg-light rounded-3 border text-dark lh-base" style="font-size: 0.95rem; border-radius: var(--radius-md) !important;">
                    {{ $complaint->description ?? 'Kabel HDMI proyektor di ruangan Lab Jaringan putus/terkelupas sehingga tampilan visual ke layar tidak menyala saat perkuliahan berlangsung. Mohon penggantian kabel atau konektor cadangan segera.' }}
                </div>
            </div>
        </div>

        {{-- Form Ubah Status Pengaduan (FR-06, BR-03) --}}
        <div class="card card-sipfk border-0 shadow-sm p-4 bg-white" style="border-radius: var(--radius-xl);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="stat-card-icon-bubble bg-light text-primary" style="width: 32px; height: 32px;">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <h3 class="h6 fw-bold text-dark mb-0">Perbarui Status Penanganan</h3>
            </div>
            <p class="text-secondary small mb-3">Ubah status penanganan sesuai kondisi riil pengecekan sarana di lapangan (BR-03).</p>

            <form method="POST" action="{{ isset($complaint) ? route('admin.complaints.update-status', $complaint->id) : '#' }}">
                @csrf
                @method('PUT')
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-sm-8">
                        <select name="status" class="form-select form-select-zd" required>
                            <option value="baru" {{ (isset($complaint) && $complaint->status === 'baru') ? 'selected' : '' }}>
                                [ Baru ] &mdash; Laporan baru diterima sistem
                            </option>
                            <option value="diproses" {{ (!isset($complaint) || $complaint->status === 'diproses') ? 'selected' : '' }}>
                                [ Diproses ] &mdash; Dalam tindakan pengecekan / perbaikan
                            </option>
                            <option value="selesai" {{ (isset($complaint) && $complaint->status === 'selesai') ? 'selected' : '' }}>
                                [ Selesai ] &mdash; Kerusakan berhasil diperbaiki tuntas
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <button type="submit" class="btn-pill-dark w-100 justify-content-center py-2" style="font-size: 0.875rem;">
                            <i class="bi bi-check-circle"></i>
                            <span>Simpan Status</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Kolom Kanan: Form Tambah Tindak Lanjut & Timeline Histori --}}
    <div class="col-12 col-lg-5">
        {{-- Form Input Catatan Tindak Lanjut (FR-07, BR-05) --}}
        <div class="card card-sipfk border-0 shadow-sm p-4 mb-4 bg-white" style="border-radius: var(--radius-xl);">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="stat-card-icon-bubble bg-primary-subtle text-primary" style="width: 32px; height: 32px;">
                    <i class="bi bi-journal-plus"></i>
                </div>
                <h3 class="h6 fw-bold text-dark mb-0">Tambah Catatan Tindak Lanjut</h3>
            </div>

            <form method="POST" action="{{ isset($complaint) ? route('admin.complaints.follow-up.store', $complaint->id) : '#' }}">
                @csrf
                <div class="mb-3">
                    <label for="follow_up_note" class="form-label-zd">
                        Catatan Perkembangan / Tindakan Teknisi <span class="required-star">*</span>
                    </label>
                    <textarea 
                        id="follow_up_note" 
                        name="note" 
                        rows="3" 
                        class="form-control form-control-zd @error('note') is-invalid @enderror" 
                        placeholder="Contoh: Teknisi divisi IT telah mengecek lokasi dan mengganti kabel HDMI dengan spare part baru..." 
                        required
                    >{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback d-block small">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-pill-dark w-100 justify-content-center py-2" style="font-size: 0.875rem;">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambahkan Catatan</span>
                </button>
            </form>
        </div>

        {{-- Timeline Riwayat Catatan Tindak Lanjut (BR-06) --}}
        <div class="card card-sipfk border-0 shadow-sm p-4 bg-white" style="border-radius: var(--radius-xl);">
            <h3 class="h6 fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
                <span>Riwayat Penanganan</span>
                <span class="badge rounded-pill bg-light text-muted border small">
                    {{ isset($complaint->followUps) ? count($complaint->followUps) : 2 }} Catatan
                </span>
            </h3>

            <div class="timeline-container pt-1">
                <ul class="timeline-list">
                    @if(isset($complaint->followUps) && count($complaint->followUps) > 0)
                        @foreach($complaint->followUps as $followUp)
                            <li class="timeline-item">
                                <div class="timeline-node" aria-hidden="true"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong class="text-dark small">{{ $followUp->admin->name ?? 'Admin SIPFK' }}</strong>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            {{ $followUp->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="mb-0 text-secondary small lh-base">
                                        {{ $followUp->note }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    @else
                        {{-- Dummy Histori untuk Preview Antarmuka --}}
                        <li class="timeline-item">
                            <div class="timeline-node" aria-hidden="true"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-dark small">Admin SIPFK</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">1 jam yang lalu</span>
                                </div>
                                <p class="mb-0 text-secondary small lh-base">
                                    Teknisi jaringan telah mengambil kabel pengganti dari gudang logistik dan menuju lokasi ruangan.
                                </p>
                            </div>
                        </li>
                        <li class="timeline-item">
                            <div class="timeline-node" aria-hidden="true"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-dark small">Admin SIPFK</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">3 jam yang lalu</span>
                                </div>
                                <p class="mb-0 text-secondary small lh-base">
                                    Laporan diterima dan diteruskan ke Divisi Sarana & Prasarana Kampus. Status diubah menjadi diproses.
                                </p>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
