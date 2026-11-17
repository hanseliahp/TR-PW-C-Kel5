@extends('layouts.admin')

@section('title', 'Daftar Pengaduan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Daftar Pengaduan</li>
@endsection

@section('page_title', 'Daftar Pengaduan Fasilitas')
@section('page_subtitle', 'Pantau, filter, tambah, ubah, dan kelola seluruh aspirasi keluhan sarana kampus dari pelapor.')

@section('header_actions')
    <button type="button" class="btn btn-sipfk-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createComplaintModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Pengaduan</span>
    </button>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm bg-white border">
        <i class="bi bi-file-earmark-pdf"></i>
        <span>Unduh Laporan</span>
    </a>
    <a href="{{ url('/') }}" target="_blank" class="btn btn-light btn-sm border" title="Buka Form Publik">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Form Publik</span>
    </a>
@endsection

@section('content')
{{-- Filter & Search Toolbar (FR-05, AC-10 Responsif) --}}
<div class="card card-sipfk border-0 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="row g-3 align-items-end">
            {{-- Search Keyword --}}
            <div class="col-12 col-md-4">
                <label for="filter_search" class="form-label-sipfk">Pencarian Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input 
                        type="text" 
                        id="filter_search" 
                        name="search" 
                        class="form-control form-control-sipfk border-start-0" 
                        placeholder="Nama pelapor, gedung, atau lokasi..." 
                        value="{{ request('search') }}"
                    >
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="col-6 col-md-3">
                <label for="filter_status" class="form-label-sipfk">Status Penanganan</label>
                <select id="filter_status" name="status" class="form-select form-select-sipfk">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            {{-- Filter Kategori --}}
            <div class="col-6 col-md-3">
                <label for="filter_category" class="form-label-sipfk">Kategori Fasilitas</label>
                <select id="filter_category" name="category_id" class="form-select form-select-sipfk">
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
                <button type="submit" class="btn btn-sipfk-primary flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'category_id']))
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-light border btn-icon-sm" title="Reset Filter" style="height: 44px; width: 44px;">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Daftar Pengaduan (FR-05, NFR-02, AC-10) --}}
<div class="card card-sipfk border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h6 fw-bold text-dark mb-0">Semua Data Pengaduan Fasilitas</h2>
            <small class="text-muted">Kelola seluruh data laporan fasilitas kampus, detail tindak lanjut, ubah dan hapus aduan.</small>
        </div>
        <span class="badge bg-light text-dark border">
            {{ isset($complaints) && is_object($complaints) && method_exists($complaints, 'total') ? $complaints->total() . ' Data' : '0 Data' }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-sipfk align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 50px;">No</th>
                    <th scope="col" style="min-width: 120px;">Tanggal</th>
                    <th scope="col" style="min-width: 160px;">Pelapor</th>
                    <th scope="col" style="min-width: 150px;">Kategori</th>
                    <th scope="col" style="min-width: 180px;">Lokasi Fasilitas</th>
                    <th scope="col" style="min-width: 220px;">Deskripsi Kerusakan</th>
                    <th scope="col" class="text-center" style="width: 120px;">Status</th>
                    <th scope="col" class="text-end" style="width: 130px;">Aksi</th>
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
                                <span class="badge bg-light text-dark border">
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
                                    {{-- Lihat Detail (FR-05, FR-06, FR-07) --}}
                                    <a href="{{ route('admin.complaints.show', $item->id) }}" class="btn btn-outline-primary btn-icon-sm" title="Lihat Detail & Tindak Lanjut">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Ubah Pengaduan (FR-05) --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-secondary btn-icon-sm" 
                                        title="Ubah Data Pengaduan"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editComplaintModal"
                                        data-id="{{ $item->id }}"
                                        data-reporter="{{ $item->reporter_name }}"
                                        data-category="{{ $item->category_id }}"
                                        data-location="{{ $item->location }}"
                                        data-date="{{ \Carbon\Carbon::parse($item->reported_at)->format('Y-m-d') }}"
                                        data-description="{{ $item->description }}"
                                        data-status="{{ $item->status }}"
                                        data-action="{{ route('admin.complaints.update', $item->id) }}"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Hapus Pengaduan (FR-05) --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-danger btn-icon-sm" 
                                        title="Hapus Pengaduan"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteConfirmModal"
                                        data-action="{{ route('admin.complaints.destroy', $item->id) }}"
                                        data-name="Pengaduan dari {{ $item->reporter_name }} ({{ $item->location }})"
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            Tidak ada data pengaduan yang ditemukan sesuai kriteria filter.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination Component Reusable --}}
    <div class="card-footer bg-white border-top px-3 px-md-4 py-2">
        @include('components.pagination', ['paginator' => $complaints ?? null])
    </div>
</div>

{{-- Modal Tambah Pengaduan Baru (FR-05) --}}
<div class="modal fade" id="createComplaintModal" tabindex="-1" aria-labelledby="createComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content card-sipfk border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-card-icon-bubble bg-primary-subtle text-primary" style="width: 32px; height: 32px; font-size: 0.95rem;">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <h5 class="modal-title fs-6 fw-bold mb-0 text-dark" id="createComplaintModalLabel">Tambah Pengaduan Fasilitas (Admin)</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST" action="{{ route('admin.complaints.store') }}">
                @csrf
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="create_reporter_name" class="form-label-sipfk">Nama Lengkap Pelapor <span class="required-star">*</span></label>
                            <input type="text" id="create_reporter_name" name="reporter_name" class="form-control form-control-sipfk" required maxlength="100" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_category_id" class="form-label-sipfk">Kategori Fasilitas <span class="required-star">*</span></label>
                            <select id="create_category_id" name="category_id" class="form-select form-select-sipfk" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_location" class="form-label-sipfk">Lokasi Fasilitas (Manual) <span class="required-star">*</span></label>
                            <input type="text" id="create_location" name="location" class="form-control form-control-sipfk" required maxlength="150" placeholder="Contoh: Gedung B Lantai 2, Ruang 204">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_reported_at" class="form-label-sipfk">Tanggal Laporan <span class="required-star">*</span></label>
                            <input type="date" id="create_reported_at" name="reported_at" class="form-control form-control-sipfk" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_status" class="form-label-sipfk">Status Awal <span class="required-star">*</span></label>
                            <select id="create_status" name="status" class="form-select form-select-sipfk" required>
                                <option value="baru" selected>Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="create_description" class="form-label-sipfk">Deskripsi Masalah / Kerusakan <span class="required-star">*</span></label>
                            <textarea id="create_description" name="description" rows="4" class="form-control form-control-sipfk" required placeholder="Jelaskan secara rinci rincian kerusakan atau masalah sarana..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sipfk-primary px-3">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Pengaduan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ubah Pengaduan (Edit Admin - FR-05) --}}
<div class="modal fade" id="editComplaintModal" tabindex="-1" aria-labelledby="editComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content card-sipfk border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-card-icon-bubble bg-secondary-subtle text-secondary" style="width: 32px; height: 32px; font-size: 0.95rem;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h5 class="modal-title fs-6 fw-bold mb-0 text-dark" id="editComplaintModalLabel">Ubah Data Pengaduan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="editComplaintForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_reporter_name" class="form-label-sipfk">Nama Lengkap Pelapor <span class="required-star">*</span></label>
                            <input type="text" id="edit_reporter_name" name="reporter_name" class="form-control form-control-sipfk" required maxlength="100">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_category_id" class="form-label-sipfk">Kategori Fasilitas <span class="required-star">*</span></label>
                            <select id="edit_category_id" name="category_id" class="form-select form-select-sipfk" required>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_location" class="form-label-sipfk">Lokasi Fasilitas (Manual) <span class="required-star">*</span></label>
                            <input type="text" id="edit_location" name="location" class="form-control form-control-sipfk" required maxlength="150">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_reported_at" class="form-label-sipfk">Tanggal Laporan <span class="required-star">*</span></label>
                            <input type="date" id="edit_reported_at" name="reported_at" class="form-control form-control-sipfk" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_status" class="form-label-sipfk">Status Penanganan <span class="required-star">*</span></label>
                            <select id="edit_status" name="status" class="form-select form-select-sipfk" required>
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_description" class="form-label-sipfk">Deskripsi Masalah / Kerusakan <span class="required-star">*</span></label>
                            <textarea id="edit_description" name="description" rows="4" class="form-control form-control-sipfk" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sipfk-primary px-3">
                        <i class="bi bi-save"></i>
                        <span>Perbarui Pengaduan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editComplaintModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                if (!btn) return;

                const form = editModal.querySelector('#editComplaintForm');
                form.setAttribute('action', btn.getAttribute('data-action') || '');

                editModal.querySelector('#edit_reporter_name').value = btn.getAttribute('data-reporter') || '';
                editModal.querySelector('#edit_category_id').value = btn.getAttribute('data-category') || '';
                editModal.querySelector('#edit_location').value = btn.getAttribute('data-location') || '';
                editModal.querySelector('#edit_reported_at').value = btn.getAttribute('data-date') || '';
                editModal.querySelector('#edit_status').value = btn.getAttribute('data-status') || 'baru';
                editModal.querySelector('#edit_description').value = btn.getAttribute('data-description') || '';
            });
        }
    });
</script>
@endpush
@endsection
