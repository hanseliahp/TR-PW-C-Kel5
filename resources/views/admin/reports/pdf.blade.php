<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Fasilitas Kampus - SIPFK</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 15mm 15mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a365d;
            margin: 0 0 4px 0;
        }
        .subtitle {
            font-size: 10pt;
            color: #4a5568;
            margin: 0 0 6px 0;
        }
        .meta {
            font-size: 8.5pt;
            color: #718096;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8.5pt;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: top;
        }
        th {
            background-color: #2b6cb0;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
        }
        th.text-center, td.text-center {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-baru {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-diproses {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .badge-selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        .follow-up-box {
            margin-top: 4px;
            padding: 4px 6px;
            background-color: #f1f5f9;
            border-left: 2px solid #0284c7;
            font-size: 7.5pt;
            color: #334155;
        }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 8pt;
            color: #64748b;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    @php
        $logoSvgPath = public_path('images/logo-sipfk-icon.svg');
        $logoDataUri = file_exists($logoSvgPath)
            ? 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoSvgPath))
            : null;
    @endphp

    <div class="header">
        @if($logoDataUri)
            <div style="margin-bottom: 6px;">
                <img src="{{ $logoDataUri }}" alt="Logo SIPFK" style="width: 38px; height: 44px; vertical-align: middle;">
            </div>
        @endif
        <h1 class="title">SIPFK. &mdash; Layanan Aspirasi &amp; Sarana Prasarana Kampus</h1>
        <p class="subtitle">Laporan Rekapitulasi Penanganan Sarana dan Prasarana Kampus</p>
        <div class="meta">
            Periode: <strong>{{ $startDateFormatted }}</strong> s/d <strong>{{ $endDateFormatted }}</strong>
            &bull; Dicetak: {{ date('d F Y, H:i') }} WIB
            &bull; Dicetak oleh: {{ Auth::user()->name ?? 'Admin SIPFK' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th class="text-center" style="width: 75px;">Tanggal</th>
                <th style="width: 120px;">Pelapor</th>
                <th style="width: 110px;">Kategori</th>
                <th style="width: 130px;">Lokasi</th>
                <th>Deskripsi Masalah &amp; Tindak Lanjut</th>
                <th class="text-center" style="width: 75px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($c->reported_at)) }}</td>
                    <td><strong>{{ $c->reporter_name }}</strong></td>
                    <td>{{ $c->category->name ?? '-' }}</td>
                    <td>{{ $c->location }}</td>
                    <td>
                        <div>{{ $c->description }}</div>
                        @if($c->followUps->isNotEmpty())
                            <div class="follow-up-box">
                                <strong>Riwayat Tindak Lanjut:</strong>
                                @foreach($c->followUps as $fu)
                                    <div>&bull; <em>{{ $fu->admin->name ?? 'Admin' }} ({{ date('d/m/Y', strtotime($fu->created_at)) }}):</em> {{ $fu->note }}</div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $c->status }}">{{ $c->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        <em>Tidak ada data pengaduan untuk kriteria periode/status yang dipilih.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>SIPFK TRAS Kelompok G5 &bull; Dicetak dari Sistem Informasi Pengaduan Fasilitas Kampus</span>
        <span style="float: right;">Total Data: {{ $complaints->count() }} Pengaduan</span>
    </div>

</body>
</html>
