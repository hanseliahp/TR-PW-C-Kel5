@extends('layouts.public')

@section('title', 'SIPFK - Solusi Nyata Penanganan Fasilitas Kampus')

@section('content')
{{-- =========================================================================
     1. HERO SECTION (Terinspirasi Penuh dari Gambar Referensi 1)
     Headline beraksen miring, tombol Pill, Showcase Card Foto dengan Bubble Resolusi Melayang
     ========================================================================= --}}
<section class="bg-aura-hero text-white pt-5 pb-5 mb-5">
    <div class="container py-3 py-lg-4 text-center">
        <!-- Badge Micro Header -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-4" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
            <span class="badge rounded-pill bg-primary" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">Resmi</span>
            <span class="small text-white fw-medium">Layanan Aspirasi & Sarana Prasarana Kampus</span>
        </div>

        <!-- Main Display Headline with Italic Accent -->
        <h1 class="display-hero display-4 fw-bold mx-auto mb-3" style="max-width: 900px;">
            Bukan Sekadar <span class="italic-accent">Mencatat</span>. Hadirkan <span class="italic-accent">Solusi Nyata</span>.
        </h1>

        <!-- Subtitle -->
        <p class="lead text-light opacity-75 mx-auto mb-4" style="max-width: 720px; font-size: 1.15rem; line-height: 1.6;">
            Platform pelaporan fasilitas kampus yang menghubungkan aspirasi mahasiswa dan dosen langsung dengan tim teknisi sarana dan prasarana secara terpusat, cepat, dan transparan.
        </p>

        <!-- CTA Buttons & Verify Pill -->
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3 mb-3">
            <a href="#form-pengaduan" class="btn-pill-white">
                <span>Ajukan Pengaduan Sekarang</span>
                <i class="bi bi-arrow-right"></i>
            </a>
            <a href="#alur-penanganan" class="btn-pill-outline">
                <i class="bi bi-play-circle"></i>
                <span>Lihat Alur Kerja</span>
            </a>
        </div>

        <div class="verify-pill justify-content-center mb-5">
            <i class="bi bi-check-circle-fill"></i>
            <span>Pengaduan langsung tercatat &bull; Tanpa perlu login akun pelapor &bull; Bebas biaya</span>
        </div>

        <!-- Hero Showcase Card dengan Foto Nyata & Floating Chat Bubble Resolusi (Image 1) -->
        <div class="hero-showcase-container mx-auto" style="max-width: 1040px;">
            <!-- Foto Showcase Kampus Berkualitas Tinggi (Unsplash Architecture/Student) -->
            <img 
                src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1600&q=80" 
                alt="Civitas akademika berkolaborasi di fasilitas kampus" 
                class="hero-showcase-img"
            >

            <!-- Overlay dengan Floating Dialogue Bubbles -->
            <div class="hero-showcase-overlay">
                <!-- Bubble Atas (Pelapor Mahasiswa) -->
                <div class="d-flex justify-content-start">
                    <div class="chat-bubble-floating text-start">
                        <div class="chat-bubble-author">
                            <div class="chat-avatar bg-primary text-white">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold text-dark small">Dina &bull; Mahasiswa Lab TI</span>
                            <span class="badge bg-warning-subtle text-warning-emphasis ms-auto" style="font-size: 0.68rem;">Tiket #104</span>
                        </div>
                        <p class="chat-bubble-text text-dark">
                            "LCD Proyektor di Ruang 304 mati total dan berbau hangus saat jam kuliah berlangsung. Mohon penggantian unit segera!"
                        </p>
                    </div>
                </div>

                <!-- Bubble Bawah (Tim Fasilitas Kampus) -->
                <div class="d-flex justify-content-end">
                    <div class="chat-bubble-floating bubble-right text-start" style="border-left: 4px solid var(--zd-accent-purple);">
                        <div class="chat-bubble-author">
                            <div class="chat-avatar bg-dark text-white">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <span class="fw-bold text-dark small">Tim Fasilitas & Sarpras</span>
                            <span class="badge bg-primary-subtle text-primary ms-auto" style="font-size: 0.68rem;">Sedang Ditangani</span>
                        </div>
                        <p class="chat-bubble-text text-dark">
                            "Laporan terverifikasi. Teknisi divisi kelistrikan telah dikerahkan membawa kabel cadangan ke Ruang 304. Estimasi perbaikan 25 menit."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Row / Unit Kampus Terlayani (Image 1 Bottom) -->
        <div class="row pt-5 mt-3 align-items-center justify-content-center g-3 text-center opacity-75">
            <div class="col-12 col-md-auto text-light small fw-semibold text-uppercase tracking-wider">
                Cakupan Pemeliharaan Terpadu:
            </div>
            <div class="col-auto"><span class="partner-logo-item"><i class="bi bi-mortarboard-fill"></i> Gedung Rektorat</span></div>
            <div class="col-auto"><span class="partner-logo-item"><i class="bi bi-laptop"></i> Lab Komputer</span></div>
            <div class="col-auto"><span class="partner-logo-item"><i class="bi bi-book-half"></i> Perpustakaan</span></div>
            <div class="col-auto"><span class="partner-logo-item"><i class="bi bi-building"></i> Ruang Kuliah Teori</span></div>
            <div class="col-auto"><span class="partner-logo-item"><i class="bi bi-droplet-fill"></i> Sanitasi & Air</span></div>
        </div>
    </div>
</section>


{{-- =========================================================================
     2. METRICS SECTION (Terinspirasi Penuh dari Gambar Referensi 3)
     "Trillions of Data Points Turned Into Billions..." & 3 Bento Stat Cards
     ========================================================================= --}}
<section class="py-5 mb-5" id="data-statistik">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-title display-6 fw-bold mb-2">
                Data Transparan <span class="italic-accent">Untuk Solusi</span> Nyata.
            </h2>
            <p class="text-secondary mx-auto" style="max-width: 600px;">
                Seluruh keluhan fasilitas dicatat secara sistematis untuk memastikan akuntabilitas unit kerja pemeliharaan sarana kampus.
            </p>
        </div>

        <div class="row g-4">
            <!-- Metric 1: Light Card -->
            <div class="col-12 col-md-4">
                <div class="metric-card-light">
                    <span class="text-uppercase tracking-wider small fw-bold text-muted mb-2">Penyelesaian Tuntas</span>
                    <div>
                        <div class="metric-number text-dark">98.4%</div>
                        <div class="metric-label">Laporan Terselesaikan</div>
                        <p class="metric-desc">Tingkat keberhasilan perbaikan fasilitas rusak hingga dapat kembali digunakan untuk perkuliahan.</p>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Hero Dark Cosmic Card (Image 3 Center Card) -->
            <div class="col-12 col-md-4">
                <div class="metric-card-dark">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-uppercase tracking-wider small fw-bold text-info">Monitoring Aktif</span>
                        <span class="badge rounded-pill bg-white text-dark small px-2 py-1">24/7 Terpantau</span>
                    </div>
                    <div>
                        <div class="metric-number">830+</div>
                        <div class="metric-label">Titik Fasilitas Kampus</div>
                        <p class="metric-desc">Meliputi ruangan kelas, instalasi listrik, pendingin ruangan, toilet, dan fasilitas laboratorium.</p>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Light Card -->
            <div class="col-12 col-md-4">
                <div class="metric-card-light">
                    <span class="text-uppercase tracking-wider small fw-bold text-muted mb-2">Kecepatan Respons</span>
                    <div>
                        <div class="metric-number text-dark">&lt; 2 Jam</div>
                        <div class="metric-label">Waktu Respons Awal</div>
                        <p class="metric-desc">Rata-rata waktu verifikasi laporan dan penerbitan instruksi kerja kepada teknisi lapangan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- =========================================================================
     3. FORMULIR PENGADUAN RESMI (Tugas Utama Desainer Antarmuka - FR-03, NFR-02, NFR-06)
     Form Bersih, Aksesibel, Sentuhan Zendesk Minimalis + Side Interactive Card
     ========================================================================= --}}
<section class="py-5 bg-white border-top border-bottom mb-5" id="form-pengaduan">
    <div class="container">
        <!-- Notifikasi & Alert -->
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                @include('components.alert')
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Kolom Kiri: Formulir Pengaduan Publik -->
            <div class="col-12 col-lg-7 col-xl-7">
                <div class="form-card-zendesk">
                    <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-semibold small" style="font-size: 0.8125rem;">
                        <i class="bi bi-shield-check"></i>
                        <span>Layanan Resmi Sarana &amp; Prasarana Kampus</span>
                    </div>

                    <h2 class="h3 fw-bold text-dark mb-2" style="letter-spacing: -0.025em;">Formulir Pengaduan Fasilitas</h2>
                    <p class="text-secondary small mb-4" style="line-height: 1.6;">
                        Lengkapi rincian kendala sarana kampus di bawah ini agar teknisi dapat membawa peralatan yang sesuai. Kolom bertanda (<span class="text-danger">*</span>) wajib diisi.
                    </p>

                    <form method="POST" action="{{ route('complaints.store') }}" novalidate>
                        @csrf

                        <!-- 1. Nama Pelapor (reporter_name) -->
                        <div class="mb-3">
                            <label for="reporter_name" class="form-label-zd">
                                Nama Lengkap Pelapor <span class="required-star">*</span>
                            </label>
                            <div class="input-clean-group">
                                <i class="bi bi-person input-icon"></i>
                                <input 
                                    type="text" 
                                    id="reporter_name" 
                                    name="reporter_name" 
                                    class="form-control form-control-zd @error('reporter_name') is-invalid @enderror" 
                                    value="{{ old('reporter_name') }}" 
                                    placeholder="Contoh: Budi Santoso / Siti Rahmawati" 
                                    required 
                                    maxlength="100"
                                    autocomplete="name"
                                >
                            </div>
                            <div class="form-help-zd">Identitas pelapor digunakan untuk konfirmasi lokasi dan transparansi.</div>
                            @error('reporter_name')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. Kategori Fasilitas (category_id) -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label-zd">
                                Kategori Fasilitas <span class="required-star">*</span>
                            </label>
                            <div class="input-clean-group">
                                <i class="bi bi-tag input-icon"></i>
                                <select 
                                    id="category_id" 
                                    name="category_id" 
                                    class="form-select form-select-zd @error('category_id') is-invalid @enderror" 
                                    required
                                >
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Pilih Kategori Kerusakan Fasilitas --</option>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Listrik & Kelistrikan</option>
                                        <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>Air & Sanitasi</option>
                                        <option value="3" {{ old('category_id') == 3 ? 'selected' : '' }}>Perabot Ruang Kelas (Meja/Kursi)</option>
                                        <option value="4" {{ old('category_id') == 4 ? 'selected' : '' }}>Teknologi Informasi & LCD Proyektor</option>
                                        <option value="5" {{ old('category_id') == 5 ? 'selected' : '' }}>Gedung & Infrastruktur Fisik</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-help-zd">Pilih bidang fasilitas yang paling tepat untuk mempercepat penugasan divisi teknisi.</div>
                            @error('category_id')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 3. Lokasi Fasilitas (location) -->
                        <div class="mb-3">
                            <label for="location" class="form-label-zd">
                                Lokasi Spesifik Fasilitas <span class="required-star">*</span>
                            </label>
                            <div class="input-clean-group">
                                <i class="bi bi-geo-alt input-icon"></i>
                                <input 
                                    type="text" 
                                    id="location" 
                                    name="location" 
                                    class="form-control form-control-zd @error('location') is-invalid @enderror" 
                                    value="{{ old('location') }}" 
                                    placeholder="Contoh: Gedung B Lantai 2, Ruang Kuliah 204" 
                                    required 
                                    maxlength="150"
                                >
                            </div>
                            <div class="form-help-zd">Tuliskan nama gedung, nomor lantai, atau patokan terdekat (Input manual tanpa GPS sesuai BR-07).</div>
                            @error('location')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 4. Tanggal Kejadian / Laporan (reported_at) -->
                        <div class="mb-3">
                            <label for="reported_at" class="form-label-zd">
                                Tanggal Temuan / Laporan <span class="required-star">*</span>
                            </label>
                            <div class="input-clean-group">
                                <i class="bi bi-calendar3 input-icon"></i>
                                <input 
                                    type="date" 
                                    id="reported_at" 
                                    name="reported_at" 
                                    class="form-control form-control-zd @error('reported_at') is-invalid @enderror" 
                                    value="{{ old('reported_at', date('Y-m-d')) }}" 
                                    required 
                                    max="{{ date('Y-m-d') }}"
                                >
                            </div>
                            <div class="form-help-zd">Tanggal saat masalah fasilitas pertama kali ditemukan.</div>
                            @error('reported_at')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 5. Deskripsi Masalah (description) -->
                        <div class="mb-4">
                            <label for="description" class="form-label-zd">
                                Deskripsi Rinci Kerusakan <span class="required-star">*</span>
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="form-control form-control-zd @error('description') is-invalid @enderror" 
                                placeholder="Jelaskan secara objektif kondisi sarana yang bermasalah, dampak terhadap perkuliahan, atau ciri-ciri kerusakan yang nampak..." 
                                required
                                style="border-radius: 10px; padding: 0.85rem 1rem;"
                            >{{ old('description') }}</textarea>
                            <div class="form-help-zd">Semakin spesifik penjelasan Anda, semakin cepat penanganan dapat diselesaikan.</div>
                            @error('description')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2">
                            <button type="submit" class="btn btn-dark w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-semibold" style="border-radius: 12px; background: #0F172A; border-color: #0F172A; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);">
                                <i class="bi bi-send-fill"></i>
                                <span>Kirimkan Pengaduan Fasilitas</span>
                            </button>
                            <p class="text-center text-muted small mt-2 mb-0">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> Laporan dikirim dengan status awal <strong class="text-dark fw-semibold">Baru</strong> dan langsung terkirim ke panel admin.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Panduan Pelaporan & Tahapan Status Penanganan (Anti-AI Stepper Pipeline) -->
            <div class="col-12 col-lg-5 col-xl-4" id="alur-penanganan">
                <!-- Tahapan Status Penanganan (Sleek Stepper Pipeline) -->
                <div class="card card-sipfk border-0 shadow-sm p-4 mb-4 bg-white" style="border-radius: var(--radius-xl);">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                        <div>
                            <h3 class="fs-6 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-diagram-3-fill text-primary"></i>
                                <span>Alur Penanganan Laporan</span>
                            </h3>
                            <small class="text-muted">Proses verifikasi hingga tuntas</small>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark border small px-2 py-1">SOP Sarpras</span>
                    </div>

                    <div class="stepper-pipeline">
                        <!-- Step 1: Baru -->
                        <div class="stepper-step step-baru">
                            <div class="stepper-marker">1</div>
                            <div class="stepper-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status badge-status-baru">
                                        <span class="badge-status-dot"></span> Status Baru
                                    </span>
                                    <span class="text-muted" style="font-size: 0.75rem; font-weight: 600;">SLA &lt; 2 Jam</span>
                                </div>
                                <p class="mb-0 text-secondary small" style="line-height: 1.5;">
                                    Pengaduan diterima sistem. Administrator memvalidasi lokasi, kategori sarana, dan tingkat urgensi.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: Diproses -->
                        <div class="stepper-step step-proses">
                            <div class="stepper-marker">2</div>
                            <div class="stepper-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status badge-status-diproses">
                                        <span class="badge-status-dot"></span> Status Diproses
                                    </span>
                                    <span class="text-primary" style="font-size: 0.75rem; font-weight: 600;">Penugasan Aktif</span>
                                </div>
                                <p class="mb-0 text-secondary small" style="line-height: 1.5;">
                                    Surat tugas diterbitkan. Teknisi membawa suku cadang ke lokasi dan mencatat progres pekerjaan.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3: Selesai -->
                        <div class="stepper-step step-selesai">
                            <div class="stepper-marker"><i class="bi bi-check2"></i></div>
                            <div class="stepper-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status badge-status-selesai">
                                        <span class="badge-status-dot"></span> Status Selesai
                                    </span>
                                    <span class="text-success" style="font-size: 0.75rem; font-weight: 600;">Tuntas Teruji</span>
                                </div>
                                <p class="mb-0 text-secondary small" style="line-height: 1.5;">
                                    Perbaikan fisik telah rampung diuji coba dan sarana siap dipakai kembali untuk perkuliahan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Pelaporan Efektif & Posko Darurat -->
                <div class="card card-sipfk border-0 shadow-sm p-4 bg-white" style="border-radius: var(--radius-xl);">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon-box" style="width: 28px; height: 28px; font-size: 0.85rem; border-radius: 8px; background: #0F172A;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h3 class="fs-6 fw-bold text-dark mb-0">Panduan Pengisian Laporan</h3>
                    </div>
                    <ul class="list-unstyled mb-3 d-flex flex-column gap-3 small text-secondary">
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check2-circle text-primary fs-6 mt-0"></i>
                            <div><strong class="text-dark">Lokasi &amp; Patokan Jelas:</strong> Cantumkan nama gedung, nomor lantai, dan ruang (misal: Gedung C Lt 2, R.201).</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check2-circle text-primary fs-6 mt-0"></i>
                            <div><strong class="text-dark">Kategori Tepat:</strong> Pemilihan kategori yang sesuai mempercepat disposisi divisi teknisi sarana.</div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="bi bi-check2-circle text-primary fs-6 mt-0"></i>
                            <div><strong class="text-dark">Deskripsi Rinci:</strong> Sertakan ciri kerusakan fisik atau kendala yang dirasakan saat perkuliahan.</div>
                        </li>
                    </ul>
                    <div class="p-3 rounded-3 border bg-light text-secondary small">
                        <div class="fw-bold text-dark d-flex align-items-center gap-1 mb-1">
                            <i class="bi bi-exclamation-octagon-fill text-danger"></i>
                            <span>Kondisi Darurat Gedung?</span>
                        </div>
                        <p class="mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                            Untuk kebakaran, korsleting besar, atau kebocoran pipa utama, hubungi langsung Posko Darurat Sarpras: <strong>(021) 555-0199 / Ext. 104</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- =========================================================================
     4. ADDITIONAL RESOURCES (Terinspirasi Penuh dari Gambar Referensi 6)
     4 Card Editorial dengan Foto Nyata & Tag Kategori
     ========================================================================= --}}
<section class="py-5 mb-5" id="panduan-layanan">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-title display-6 fw-bold mb-2">
                Pusat Informasi <span class="italic-accent">& Panduan</span> Fasilitas.
            </h2>
            <p class="text-secondary mx-auto" style="max-width: 600px;">
                Pelajari standar operasional dan pemeliharaan sarana kampus demi kelancaran kegiatan akademik bersama.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Resource 1 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="resource-card">
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80" alt="Instalasi Listrik" class="resource-card-img">
                    <div class="resource-card-body">
                        <div class="resource-tag">PANDUAN SARANA</div>
                        <h3 class="fs-6 fw-bold text-dark mb-2">Standar Pemeliharaan Kelistrikan</h3>
                        <p class="small text-secondary mb-3 flex-grow-1">Protokol keselamatan operasional stop kontak, trafo daya, dan panel di gedung perkuliahan.</p>
                        <a href="#form-pengaduan" class="fw-semibold small text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            <span>Baca panduan</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resource 2 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="resource-card">
                    <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80" alt="Layanan Pengaduan" class="resource-card-img">
                    <div class="resource-card-body">
                        <div class="resource-tag">SOP PELAYANAN</div>
                        <h3 class="fs-6 fw-bold text-dark mb-2">Alur Verifikasi Laporan</h3>
                        <p class="small text-secondary mb-3 flex-grow-1">Bagaimana administrator memverifikasi tiket pengaduan hingga diteruskan ke teknisi.</p>
                        <a href="#form-pengaduan" class="fw-semibold small text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            <span>Pelajari alur</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resource 3 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="resource-card">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80" alt="Lab Komputer" class="resource-card-img">
                    <div class="resource-card-body">
                        <div class="resource-tag">TEKNOLOGI & LAB</div>
                        <h3 class="fs-6 fw-bold text-dark mb-2">Perawatan LCD & Jaringan</h3>
                        <p class="small text-secondary mb-3 flex-grow-1">Pencegahan gangguan kabel HDMI, proyektor, dan titik akses nirkabel ruang kelas.</p>
                        <a href="#form-pengaduan" class="fw-semibold small text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            <span>Info teknis</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resource 4 -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="resource-card">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80" alt="Layanan Kampus" class="resource-card-img">
                    <div class="resource-card-body">
                        <div class="resource-tag">AKUNTABILITAS</div>
                        <h3 class="fs-6 fw-bold text-dark mb-2">Laporan Rekapitulasi PDF</h3>
                        <p class="small text-secondary mb-3 flex-grow-1">Dokumentasi hasil tindak lanjut fasilitas berkala yang dapat diunduh oleh unit kerja.</p>
                        <a href="{{ route('login') }}" class="fw-semibold small text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            <span>Akses dokumen</span> <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion (Image 6 Right/Bottom Reference) -->
        <div class="row justify-content-center pt-3">
            <div class="col-12 col-lg-9">
                <div class="text-center mb-4">
                    <h3 class="display-title h4 fw-bold">Pertanyaan yang <span class="italic-accent">Sering Diajukan</span></h3>
                    <p class="text-secondary small">Kumpulan jawaban atas hal umum seputar pelaporan fasilitas kampus.</p>
                </div>

                <div class="accordion accordion-zendesk" id="faqAccordion">
                    <div class="accordion-item">
                        <h4 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                Apakah pelapor wajib membuat akun untuk mengirim pengaduan?
                            </button>
                        </h4>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <strong>Tidak perlu.</strong> Sesuai keputusan rancangan PRD SIPFK (Seksi 3), formulir pengaduan dapat diakses langsung oleh seluruh sivitas akademika tanpa akun login agar pengaduan dapat dikirim dengan cepat dan mudah saat kondisi genting.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h4 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                Berapa lama estimasi waktu penanganan setelah laporan dikirim?
                            </button>
                        </h4>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Laporan baru akan diverifikasi oleh administrator maksimal dalam 1&ndash;2 jam kerja. Begitu status diubah menjadi <strong>Diproses</strong>, teknisi terkait segera ditugaskan ke lokasi untuk tindakan perbaikan.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h4 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                Bagaimana cara mengetahui perkembangan perbaikan sarana?
                            </button>
                        </h4>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Administrator kampus menambahkan catatan tindak lanjut secara bertahap pada sistem. Perkembangan status dapat dipantau langsung atau dikonfirmasikan kepada unit sarana dan prasarana kampus.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h4 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                Siapa yang berhak masuk ke dalam portal administrator?
                            </button>
                        </h4>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Portal admin dilindungi oleh autentikasi ketat dan hanya dapat diakses oleh petugas pengelola fasilitas kampus resmi untuk memperbarui status, menulis catatan teknisi, dan menghasilkan laporan rekapitulasi PDF.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- =========================================================================
     5. FINAL CALL TO ACTION BANNER (Terinspirasi Penuh dari Gambar Referensi 7)
     "Launch Your First AI Agent Today" Style Dark Cosmic Nebula Banner
     ========================================================================= --}}
<section class="container pb-5 mb-4">
    <div class="cta-banner-card text-white">
        <h2 class="display-hero display-5 fw-bold mb-3">
            Temukan Fasilitas Rusak? <span class="italic-accent">Laporkan Sekarang</span>.
        </h2>
        <p class="lead text-light opacity-75 mx-auto mb-4" style="max-width: 650px; font-size: 1.1rem;">
            Bantu wujudkan sarana kampus yang aman, nyaman, dan mendukung kegiatan akademik berkualitas tinggi. Laporan Anda membuat perbedaan.
        </p>
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3">
            <a href="#form-pengaduan" class="btn-pill-white">
                <i class="bi bi-pencil-square"></i>
                <span>Isi Formulir Pengaduan</span>
            </a>
            <a href="{{ route('login') }}" class="btn-pill-outline">
                <i class="bi bi-shield-lock"></i>
                <span>Akses Panel Admin</span>
            </a>
        </div>
    </div>
</section>
@endsection
