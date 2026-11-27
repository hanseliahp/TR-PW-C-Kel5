@extends('layouts.admin')

@section('title', 'Kategori Fasilitas')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kategori Fasilitas</li>
@endsection

@section('page_title', 'Kategori Fasilitas Kampus')
@section('page_subtitle', 'Kelola pengelompokan jenis sarana prasarana untuk mempermudah klasifikasi pengaduan.')

@section('header_actions')
    <button type="button" class="btn-pill-dark" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Kategori</span>
    </button>
@endsection

@section('content')
<div class="card card-sipfk border-0 shadow-sm bg-white" style="border-radius: var(--radius-xl); overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <h2 class="h6 fw-bold text-dark mb-0">Daftar Kategori Fasilitas</h2>
        <span class="badge rounded-pill bg-light text-muted border px-3 py-2 small">
            Total: {{ isset($categories) ? count($categories) : 5 }} Kategori
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-zendesk align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 60px;">No</th>
                    <th scope="col" style="min-width: 180px;">Nama Kategori</th>
                    <th scope="col" style="min-width: 250px;">Deskripsi Ruang Lingkup</th>
                    <th scope="col" class="text-center" style="width: 140px;">Pengaduan</th>
                    <th scope="col" class="text-end" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $index => $category)
                        <tr>
                            <td class="text-muted small fw-medium">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $category->name }}</div>
                            </td>
                            <td class="text-secondary small">
                                {{ $category->description ?? 'Tidak ada deskripsi tambahan.' }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-1">
                                    {{ $category->complaints_count ?? (isset($category->complaints) ? $category->complaints->count() : 0) }} Laporan
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-icon-sm rounded-circle me-1"
                                        title="Ubah Kategori"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}"
                                        data-action="{{ route('admin.categories.update', $category->id) }}"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-icon-sm rounded-circle"
                                        title="Hapus Kategori"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmModal"
                                        data-action="{{ route('admin.categories.destroy', $category->id) }}"
                                        data-name="Kategori {{ $category->name }}"
                                        data-warning="Peraturan Bisnis BR-08: Kategori yang masih digunakan oleh data pengaduan tidak dapat dihapus."
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else

                    @php
                        $dummyCats = [
                            ['id' => 1, 'name' => 'Listrik & Kelistrikan', 'desc' => 'Saklar, stop kontak, penerangan lampu kelas/koridor, trafo, sekring.', 'count' => 8],
                            ['id' => 2, 'name' => 'Air & Sanitasi', 'desc' => 'Kran air, wastafel, toilet, saluran pembuangan, tandon air.', 'count' => 12],
                            ['id' => 3, 'name' => 'Perabot Ruang Kelas', 'desc' => 'Meja dosen/mahasiswa, kursi, whiteboard, pintu, jendela.', 'count' => 5],
                            ['id' => 4, 'name' => 'Teknologi Informasi & Audio', 'desc' => 'LCD Proyektor, jaringan Wi-Fi, kabel HDMI, sound system ruang kuliah.', 'count' => 14],
                            ['id' => 5, 'name' => 'Gedung & Infrastruktur Fisik', 'desc' => 'Plafon bocor, lantai keramik pecah, tangga, pegangan tangga koridor.', 'count' => 3],
                        ];
                    @endphp
                    @foreach($dummyCats as $idx => $dc)
                        <tr>
                            <td class="text-muted small fw-medium">{{ $idx + 1 }}</td>
                            <td><div class="fw-bold text-dark">{{ $dc['name'] }}</div></td>
                            <td class="text-secondary small">{{ $dc['desc'] }}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border px-3 py-1">{{ $dc['count'] }} Laporan</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary btn-icon-sm rounded-circle me-1" title="Ubah Kategori" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="{{ $dc['id'] }}" data-name="{{ $dc['name'] }}" data-description="{{ $dc['desc'] }}" data-action="#">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-icon-sm rounded-circle" title="Hapus Kategori" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-action="#" data-name="Kategori {{ $dc['name'] }}" data-warning="Peraturan Bisnis BR-08: Kategori yang masih digunakan tidak boleh dihapus.">
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

    <div class="card-footer bg-white border-top py-3 px-3 px-md-4">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i> Sesuai <strong>BR-08</strong>, pastikan seluruh laporan pengaduan pada suatu kategori telah dipindahkan atau ditangani sebelum menghapus kategori.
        </small>
    </div>
</div>

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-xl);">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-icon-box bg-primary text-white" style="width: 32px; height: 32px; font-size: 0.95rem;">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <h5 class="modal-title fs-6 fw-bold mb-0 text-dark" id="createCategoryModalLabel">Tambah Kategori Fasilitas</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label for="create_category_name" class="form-label-zd">
                            Nama Kategori <span class="required-star">*</span>
                        </label>
                        <input
                            type="text"
                            id="create_category_name"
                            name="name"
                            class="form-control form-control-zd"
                            placeholder="Contoh: Tata Udara (AC & Kipas)"
                            required
                            maxlength="100"
                        >
                        <div class="form-help-zd">Nama kategori harus unik dan merepresentasikan jenis sarana kampus.</div>
                    </div>
                    <div class="mb-0">
                        <label for="create_category_desc" class="form-label-zd">
                            Deskripsi Cakupan (Opsional)
                        </label>
                        <textarea
                            id="create_category_desc"
                            name="description"
                            rows="3"
                            class="form-control form-control-zd"
                            placeholder="Tuliskan cakupan fasilitas atau perangkat yang masuk dalam kategori ini..."
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-pill-dark" style="padding: 0.5rem 1.5rem; font-size: 0.875rem;">
                        <i class="bi bi-check-lg"></i>
                        <span>Simpan Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-xl);">
            <div class="modal-header border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-icon-box bg-secondary text-white" style="width: 32px; height: 32px; font-size: 0.95rem;">
                        <i class="bi bi-pencil"></i>
                    </div>
                    <h5 class="modal-title fs-6 fw-bold mb-0 text-dark" id="editCategoryModalLabel">Ubah Data Kategori</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="editCategoryForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label-zd">
                            Nama Kategori <span class="required-star">*</span>
                        </label>
                        <input
                            type="text"
                            id="edit_category_name"
                            name="name"
                            class="form-control form-control-zd"
                            required
                            maxlength="100"
                        >
                    </div>
                    <div class="mb-0">
                        <label for="edit_category_desc" class="form-label-zd">
                            Deskripsi Cakupan (Opsional)
                        </label>
                        <textarea
                            id="edit_category_desc"
                            name="description"
                            rows="3"
                            class="form-control form-control-zd"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-pill-dark" style="padding: 0.5rem 1.5rem; font-size: 0.875rem;">
                        <i class="bi bi-save"></i>
                        <span>Perbarui Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editCategoryModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                if (!btn) return;

                const action = btn.getAttribute('data-action');
                const name = btn.getAttribute('data-name');
                const desc = btn.getAttribute('data-description');

                const form = editModal.querySelector('#editCategoryForm');
                const nameInput = editModal.querySelector('#edit_category_name');
                const descInput = editModal.querySelector('#edit_category_desc');

                if (action) form.setAttribute('action', action);
                if (nameInput) nameInput.value = name || '';
                if (descInput) descInput.value = desc || '';
            });
        }
    });
</script>
@endpush
@endsection
