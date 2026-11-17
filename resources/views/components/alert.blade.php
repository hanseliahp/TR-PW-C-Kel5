{{-- 
  Komponen Reusable Alert & Notifikasi Validasi
  Mendukung flash message (success, error, warning, info) dan error validasi Laravel ($errors)
--}}

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="background-color: #ECFDF5; color: #065F46; border-left: 4px solid #059669 !important; border-radius: var(--radius-md) !important;">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="flex-grow-1">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="background-color: #FEF2F2; color: #991B1B; border-left: 4px solid #DC2626 !important; border-radius: var(--radius-md) !important;">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
        <div class="flex-grow-1">
            <strong>Terjadi Kesalahan!</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="background-color: #FFFBEB; color: #92400E; border-left: 4px solid #D97706 !important; border-radius: var(--radius-md) !important;">
        <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
        <div class="flex-grow-1">
            <strong>Perhatian:</strong> {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 border-0 shadow-sm mb-4" role="alert" style="background-color: #EFF6FF; color: #1E40AF; border-left: 4px solid #2563EB !important; border-radius: var(--radius-md) !important;">
        <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
        <div class="flex-grow-1">
            {{ session('info') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
    </div>
@endif

{{-- Ringkasan Error Validasi Formulir Sisi Server (NFR-04, AC-09) --}}
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #FEF2F2; color: #991B1B; border-left: 4px solid #DC2626 !important; border-radius: var(--radius-md) !important;">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-x-octagon-fill fs-5 text-danger mt-1"></i>
            <div class="flex-grow-1">
                <strong class="d-block mb-1">Mohon periksa kembali formulir Anda:</strong>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi kesalahan"></button>
        </div>
    </div>
@endif
