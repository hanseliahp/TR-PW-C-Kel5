@props(['paginator' => null])

@if ($paginator && $paginator->hasPages())
    <nav class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3" aria-label="Navigasi Halaman Tabel">
        <div class="small text-muted">
            Menampilkan
            <span class="fw-semibold text-dark">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="fw-semibold text-dark">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="fw-semibold text-dark">{{ $paginator->total() }}</span> entri
        </div>

        <ul class="pagination pagination-sm mb-0">

            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link" style="background-color: var(--sipfk-brand); border-color: var(--sipfk-brand);">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Selanjutnya">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@elseif(!$paginator)

    <nav class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3" aria-label="Navigasi Halaman Tabel Demo">
        <div class="small text-muted">
            Menampilkan data halaman aktif
        </div>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i></span></li>
            <li class="page-item active"><span class="page-link" style="background-color: var(--sipfk-brand); border-color: var(--sipfk-brand);">1</span></li>
            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right"></i></span></li>
        </ul>
    </nav>
@endif
