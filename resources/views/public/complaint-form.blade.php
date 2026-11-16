@extends('layouts.public')

@section('title', 'Formulir Pengaduan Fasilitas Kampus - SIPFK')

@section('content')
<div class="row gy-4 justify-content-center">
    {{-- Header Banner Publik --}}
    <div class="col-12 text-center mb-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold small mb-2">
            <i class="bi bi-megaphone-fill me-1"></i> Layanan Pengaduan Resmi Kampus
        </span>
        <h1 class="h2 fw-bold text-dark mt-2 mb-2">Formulir Pengaduan Fasilitas Kampus</h1>
        <p class="text-muted mx-auto" style="max-width: 650px;">
            Sampaikan laporan mengenai kendala atau kerusakan sarana dan prasarana di lingkungan kampus agar dapat segera diverifikasi dan ditindaklanjuti oleh tim pengelola.
        </p>
    </div>

    {{-- Kolom Kiri: Formulir Pengaduan --}}
    <div class="col-12 col-lg-7">
        <div class="card card-sipfk border-0 shadow-sm p-4 p-md-5">
            <div class="border-bottom pb-3 mb-4">
                <h2 class="h5 fw-bold text-dark mb-1">Data Laporan Pengaduan</h2>
                <p class="text-muted small mb-0">Semua kolom bertanda bintang (<span class="text-danger">*</span>) wajib diisi dengan informasi yang akurat.</p>
            </div>

            <form method="POST" action="{{ route('complaints.store') }}" novalidate>
                @csrf

                {{-- 1. Nama Pelapor (reporter_name) --}}
                <div class="mb-3">
                    <label for="reporter_name" class="form-label-sipfk">
                        Nama Lengkap Pelapor <span class="required-star">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-person"></i>
                        </span>
                        <input 
                            type="text" 
                            id="reporter_name" 
                            name="reporter_name" 
                            class="form-control form-control-sipfk border-start-0 @error('reporter_name') is-invalid @enderror" 
                            value="{{ old('reporter_name') }}" 
                            placeholder="Contoh: Budi Santoso / Siti Rahmawati" 
                            required 
                            maxlength="100"
                            autocomplete="name"
                            aria-describedby="reporterNameHelp reporterNameFeedback"
                        >
                    </div>
                    <div id="reporterNameHelp" class="form-help-text">Nama pelapor digunakan untuk keperluan identifikasi dan verifikasi laporan.</div>
                    @error('reporter_name')
                        <div id="reporterNameFeedback" class="invalid-feedback d-block small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 2. Kategori Fasilitas (category_id) --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label-sipfk">
                        Kategori Fasilitas <span class="required-star">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-tag"></i>
                        </span>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            class="form-select form-select-sipfk border-start-0 @error('category_id') is-invalid @enderror" 
                            required
                            aria-describedby="categoryHelp categoryFeedback"
                        >
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Pilih Kategori Kerusakan / Fasilitas --</option>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @else
                                {{-- Placeholder Kategori Standar (Berdasarkan CategorySeeder) --}}
                                <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Listrik & Kelistrikan</option>
                                <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>Air & Sanitasi</option>
                                <option value="3" {{ old('category_id') == 3 ? 'selected' : '' }}>Perabot Ruang Kelas (Meja/Kursi)</option>
                                <option value="4" {{ old('category_id') == 4 ? 'selected' : '' }}>Teknologi Informasi & LCD Proyektor</option>
                                <option value="5" {{ old('category_id') == 5 ? 'selected' : '' }}>Gedung & Infrastruktur Fisik</option>
                            @endif
                        </select>
                    </div>
                    <div id="categoryHelp" class="form-help-text">Pilih kategori yang paling sesuai dengan jenis sarana yang dilaporkan.</div>
                    @error('category_id')
                        <div id="categoryFeedback" class="invalid-feedback d-block small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 3. Lokasi Fasilitas (location) --}}
                <div class="mb-3">
                    <label for="location" class="form-label-sipfk">
                        Lokasi Fasilitas <span class="required-star">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <input 
                            type="text" 
                            id="location" 
                            name="location" 
                            class="form-control form-control-sipfk border-start-0 @error('location') is-invalid @enderror" 
                            value="{{ old('location') }}" 
                            placeholder="Contoh: Gedung A Lantai 2, Ruang Kelas 204" 
                            required 
                            maxlength="150"
                            aria-describedby="locationHelp locationFeedback"
                        >
                    </div>
                    <div id="locationHelp" class="form-help-text">Tuliskan nama gedung, lantai, nomor ruangan, atau penanda spesifik (BR-07: input manual tanpa GPS).</div>
                    @error('location')
                        <div id="locationFeedback" class="invalid-feedback d-block small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 4. Tanggal Kejadian / Laporan (reported_at) --}}
                <div class="mb-3">
                    <label for="reported_at" class="form-label-sipfk">
                        Tanggal Kejadian / Laporan <span class="required-star">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <input 
                            type="date" 
                            id="reported_at" 
                            name="reported_at" 
                            class="form-control form-control-sipfk border-start-0 @error('reported_at') is-invalid @enderror" 
                            value="{{ old('reported_at', date('Y-m-d')) }}" 
                            required 
                            max="{{ date('Y-m-d') }}"
                            aria-describedby="reportedAtHelp reportedAtFeedback"
                        >
                    </div>
                    <div id="reportedAtHelp" class="form-help-text">Tanggal saat kendala ditemukan atau dilaporkan.</div>
                    @error('reported_at')
                        <div id="reportedAtFeedback" class="invalid-feedback d-block small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 5. Deskripsi Masalah (description) --}}
                <div class="mb-4">
                    <label for="description" class="form-label-sipfk">
                        Deskripsi Kerusakan / Masalah <span class="required-star">*</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        class="form-control form-control-sipfk @error('description') is-invalid @enderror" 
                        placeholder="Jelaskan secara rinci kondisi fasilitas yang bermasalah, dampak, atau rincian kerusakan yang terlihat..." 
                        required
                        aria-describedby="descriptionHelp descriptionFeedback"
                    >{{ old('description') }}</textarea>
                    <div id="descriptionHelp" class="form-help-text">Berikan detail yang jelas agar tim fasilitas dapat membawa peralatan yang sesuai.</div>
                    @error('description')
                        <div id="descriptionFeedback" class="invalid-feedback d-block small">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Action Button --}}
                <div class="pt-2">
                    <button type="submit" class="btn btn-sipfk-primary btn-lg w-100 justify-content-center shadow-sm">
                        <i class="bi bi-send-fill"></i>
                        <span>Kirim Pengaduan Sekarang</span>
                    </button>
                    <p class="text-center text-muted small mt-2 mb-0">
                        Pengaduan akan otomatis berstatus <span class="badge bg-warning-subtle text-warning-emphasis">baru</span> dan masuk ke antrean admin.
                    </p>
                </div>
            </form>
        </div>
    </div>

    {{-- Kolom Kanan: Panduan Pelaporan & Komitmen Layanan --}}
    <div class="col-12 col-lg-5" id="panduan-layanan">
        <div class="card card-sipfk border-0 shadow-sm p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="stat-card-icon-bubble bg-primary-subtle text-primary" style="width: 36px; height: 36px; font-size: 1.1rem;">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="h6 fw-bold text-dark mb-0">Panduan Pelaporan</h3>
            </div>
            <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small text-secondary">
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong class="text-dark">Lokasi yang Akurat:</strong> Cantumkan nama gedung, nomor ruang, atau patokan terdekat agar teknisi tidak kesulitan menemukan lokasi.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong class="text-dark">Kategori yang Tepat:</strong> Memilih kategori yang sesuai mempercepat delegasi ke divisi teknisi spesifik.
                    </div>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <div>
                        <strong class="text-dark">Penjelasan Detail:</strong> Ceritakan kronologi atau ciri kerusakan secara objektif dan jelas.
                    </div>
                </li>
            </ul>
        </div>

        {{-- Status Penanganan Tracker Info --}}
        <div class="card card-sipfk border-0 shadow-sm p-4 bg-white">
            <h3 class="h6 fw-bold text-dark mb-3">Tahapan Penanganan</h3>
            <div class="d-flex flex-column gap-3 small">
                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-status badge-status-baru">
                            <span class="badge-status-dot"></span> Baru
                        </span>
                    </div>
                    <span class="text-muted">Laporan diterima sistem</span>
                </div>
                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-status badge-status-diproses">
                            <span class="badge-status-dot"></span> Diproses
                        </span>
                    </div>
                    <span class="text-muted">Teknisi menindaklanjuti</span>
                </div>
                <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-status badge-status-selesai">
                            <span class="badge-status-dot"></span> Selesai
                        </span>
                    </div>
                    <span class="text-muted">Perbaikan telah tuntas</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
