@props(['status' => 'baru'])

@php
    $normalized = strtolower(trim($status ?? 'baru'));
    $config = match($normalized) {
        'baru' => [
            'class' => 'badge-status-baru',
            'label' => 'Baru',
            'icon'  => 'bi-clock-history',
        ],
        'diproses' => [
            'class' => 'badge-status-diproses',
            'label' => 'Diproses',
            'icon'  => 'bi-arrow-repeat',
        ],
        'selesai' => [
            'class' => 'badge-status-selesai',
            'label' => 'Selesai',
            'icon'  => 'bi-check2-circle',
        ],
        default => [
            'class' => 'badge-status-baru',
            'label' => ucfirst($normalized),
            'icon'  => 'bi-circle',
        ],
    };
@endphp

<span class="badge-status {{ $config['class'] }}" title="Status Pengaduan: {{ $config['label'] }}">
    <span class="badge-status-dot" aria-hidden="true"></span>
    <i class="bi {{ $config['icon'] }} small" aria-hidden="true"></i>
    <span>{{ $config['label'] }}</span>
</span>
