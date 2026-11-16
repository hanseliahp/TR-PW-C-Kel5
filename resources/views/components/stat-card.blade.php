@props([
    'title' => 'Statistik',
    'value' => 0,
    'type' => 'total',
    'icon' => 'bi-folder',
    'description' => null
])

@php
    $accentClass = match($type) {
        'baru'     => 'accent-baru',
        'diproses' => 'accent-diproses',
        'selesai'  => 'accent-selesai',
        default    => 'accent-total',
    };

    $iconBubbleStyle = match($type) {
        'baru'     => 'background-color: var(--sipfk-status-baru-bg); color: var(--sipfk-status-baru-dot);',
        'diproses' => 'background-color: var(--sipfk-status-proses-bg); color: var(--sipfk-status-proses-dot);',
        'selesai'  => 'background-color: var(--sipfk-status-selesai-bg); color: var(--sipfk-status-selesai-dot);',
        default    => 'background-color: var(--sipfk-slate-100); color: var(--sipfk-navy-700);',
    };
@endphp

<div class="stat-card {{ $accentClass }}">
    <div class="stat-card-accent-bar" aria-hidden="true"></div>
    <div class="d-flex align-items-start justify-content-between mb-2">
        <span class="stat-card-label">{{ $title }}</span>
        <div class="stat-card-icon-bubble" style="{{ $iconBubbleStyle }}" aria-hidden="true">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>
    <div>
        <div class="stat-card-value">{{ number_format($value) }}</div>
        @if($description)
            <div class="small text-muted d-flex align-items-center gap-1">
                <span>{{ $description }}</span>
            </div>
        @endif
    </div>
</div>
