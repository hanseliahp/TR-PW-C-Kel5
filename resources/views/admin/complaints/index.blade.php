@extends('layouts.admin')

@section('title', 'Daftar Pengaduan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Daftar Pengaduan</li>
@endsection

@section('page_title', 'Daftar Pengaduan Fasilitas')
@section('page_subtitle', 'Pantau, filter, dan kelola seluruh aspirasi keluhan sarana kampus dari pelapor.')

@section('header_actions')
    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary btn-sm bg-white border rounded-pill px-3 py-2">
        <i class="bi bi-plus-circle"></i>
        <span>Form Publik</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="btn-pill-dark" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Unduh Laporan</span>
    </a>
@endsection

@section('content')
{{-- Filter & Search Toolbar (Zendesk-Style Pill Inputs) --}}
<div class="card card-sipfk border-0 shadow-sm mb-4 bg-white" style="border-radius: var(--radius-xl);">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="row g-3 align-items-end">
            {{-- Search Keyword --}}
            <div class="col-12 col-md-4">
                <label for="filter_search" class="form-label-zd">Pencarian Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input 
                        type="text" 
                        id="filter_search" 
                        name="search" 
                        class="form-control form-control-zd border-start-0" 
                        placeholder="Nama pelapor, gedung, atau lokasi..." 
                        value="{{ request('search') }}"
                    >
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="col-6 col-md-3">
                <label for="filter_status" class="form-label-zd">Status Penanganan</label>
                <select id="filter_status" name="status" class="form-select form-select-zd">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            {{-- Filter Kategori --}}
            <div class="col-6 col-md-3">
                <label for="filter_category" class="form-label-zd">Kategori Fasilitas</label>
                <select id="filter_category" name="category_id" class="form-select form-select-zd">
                    <option value="">Semua Kategori</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Tombol Aksi Filter --}}
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn-pill-dark flex-grow-1 justify-content-center py-2" style="font-size: 0.875rem;">
                    <i class="bi bi-funnel"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'category_id']))
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center" title="Reset Filter" style="height: 46px; width: 46px;">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Daftar Pengaduan (NFR-02, AC-10) --}}
<div class="card card-sipfk border-0 shadow-sm bg-white" style="border-radius: var(--radius-xl); overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h6 fw-bold text-dark mb-0">Semua Data Pengaduan Fasilitas</h2>
            <small class="text-muted">Klik tombol detail untuk memeriksa laporan dan menambahkan catatan tindak lanjut.</small>
        </div>
        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
            {{ isset($complaints) && is_object($complaints) && method_exists($complaints, 'total') ? $complaints->total() . ' Data' : '5 Data Terdaftar' }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-zendesk align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 50px;">No</th>
                    <th scope="col" style="min-width: 120px;">Tanggal</th>
                    <th scope="col" style="min-width: 160px;">Pelapor</th>
                    <th scope="col" style="min-width: 150px;">Kategori</th>
                    <th scope="col" style="min-width: 180px;">Lokasi Fasilitas</th>
                    <th scope="col" style="min-width: 220px;">Deskripsi Kerusakan</th>
                    <th scope="col" class="text-center" style="width: 120px;">Status</th>
                    <th scope="col" class="text-end" style="width: 110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($complaints) && count($complaints) > 0)
                    @foreach($complaints as $index => $item)
                        <tr>
                            <td class="text-muted small fw-medium">
                                {{ isset($complaints) && method_exists($complaints, 'firstItem') ? ($complaints->firstItem() + $index) : ($index + 1) }}
                            </td>
                            <td class="text-nowrap small">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ \Carbon\Carbon::parse($item->reported_at)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->reporter_name }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $item->category->name ?? 'Fasilitas Umum' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-secondary small fw-medium">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    {{ $item->location }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted" title="{{ $item->description }}">
                                    {{ Str::limit($item->description, 55) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @include('components.status-badge', ['status' => $item->status])
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.complaints.show', $item->id) }}" class="btn btn-outline-secondary btn-icon-sm rounded-circle me-1" title="Lihat Detail & Tindak Lanjut">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-danger btn-icon-sm rounded-circle" 
                                        title="Hapus Pengaduan"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteConfirmModal"
                                        data-action="{{ route('admin.complaints.destroy', $item->id) }}"
                                        data-name="Pengaduan dari {{ $item->reporter_name }}"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- Dummy Fallback --}}
                    @php
                        $dummyRows = [
                            [
                                'id' => 1,
                                'date' => '17 Nov 2026',
                                'reporter' => 'Budi Pratama',
                                'category' => 'Teknologi Informasi & Audio',
                                'location' => 'Gedung B Lantai 3, Lab Jaringan',
                                'desc' => 'Kabel HDMI proyektor putus sehingga tampilan ke layar tidak muncul saat perkuliahan.',
                                'status' => 'baru',
                            ],
                            [
                                'id' => 2,
                                'date' => '16 Nov 2026',
                                'reporter' => 'Siti Nurhaliza',
                                'category' => 'Air & Sanitasi',
                                'location' => 'Gedung A, Toilet Putri Lantai 1',
                                'desc' => 'Kran wastafel bocor terus menerus dan genangan air membasahi lantai koridor depan toilet.',
                                'status' => 'diproses',
                            ],
                            [
                                'id' => 3,
                                'date' => '15 Nov 2026',
                                'reporter' => 'Hendro Santoso',
                                'category' => 'Listrik & Kelistrikan',
                                'location' => 'Ruang Dosen Gedung C',
                                'desc' => 'Lampu neon berkedip-kedip dan stop kontak sudut ruangan mengeluarkan bau hangus.',
                                'status' => 'selesai',
                            ],
                            [
                                'id' => 4,
                                'date' => '14 Nov 2026',
                                'reporter' => 'Dewi Lestari',
                                'category' => 'Perabot Ruang Kelas',
                                'location' => 'Ruang Kuliah Teori RK-202',
                                'desc' => 'Tiga kursi kuliah patah pada bagian sandaran tangan dan meja lipatnya goyang.',
                                'status' => 'baru',
                            ],
                            [
                                'id' => 5,
                                'date' => '13 Nov 2026',
                                'reporter' => 'Fajar Nugroho',
                                'category' => 'Gedung & Infrastruktur Fisik',
                                'location' => 'Tangga Utama Gedung Rektorat',
                                'desc' => 'Keramik pijakan tangga nomor 4 retak dan tajam, berbahaya bagi mahasiswa yang melintas.',
                                'status' => 'diproses',
                            ],
                        ];
                    @endphp
                    @foreach($dummyRows as $idx => $row)
                        <tr>
                            <td class="text-muted small fw-medium">{{ $idx + 1 }}</td>
                            <td class="text-nowrap small"><i class="bi bi-calendar3 me-1 text-muted"></i> {{ $row['date'] }}</td>
                            <td><div class="fw-semibold text-dark">{{ $row['reporter'] }}</div></td>
                            <td><span class="badge rounded-pill bg-light text-dark border">{{ $row['category'] }}</span></td>
                            <td>
                                <div class="text-secondary small fw-medium">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    {{ $row['location'] }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted" title="{{ $row['desc'] }}">
                                    {{ Str::limit($row['desc'], 55) }}
                                </div>
                            </td>
                            <td class="text-center">
                                @include('components.status-badge', ['status' => $row['status']])
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.complaints.show', $row['id']) }}" class="btn btn-outline-secondary btn-icon-sm rounded-circle me-1" title="Lihat Detail & Tindak Lanjut">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-icon-sm rounded-circle" title="Hapus Pengaduan" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-action="#" data-name="Pengaduan dari {{ $row['reporter'] }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination Component Reusable --}}
    <div class="card-footer bg-white border-top px-3 px-md-4 py-2">
        @include('components.pagination', ['paginator' => $complaints ?? null])
    </div>
</div>
@endsection
