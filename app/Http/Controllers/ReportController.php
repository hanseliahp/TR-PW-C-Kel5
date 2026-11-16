<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Services\Pdf\SimplePdfReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman rekapitulasi laporan dengan filter periode dan status.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['category', 'followUps.admin'])->latest('reported_at');

        if ($request->filled('start_date')) {
            $query->whereDate('reported_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('reported_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->get();
        $categories = Category::all();

        return view('admin.reports.index', compact('complaints', 'categories'));
    }

    /**
     * Menghasilkan dokumen laporan PDF berdasarkan filter aktif dan data riil database.
     */
    public function exportPdf(Request $request)
    {
        $query = Complaint::with(['category', 'followUps.admin'])->latest('reported_at');

        if ($request->filled('start_date')) {
            $query->whereDate('reported_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('reported_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->get();

        $startDateFormatted = $request->filled('start_date')
            ? date('d/m/Y', strtotime($request->start_date))
            : ($complaints->isNotEmpty() ? date('d/m/Y', strtotime($complaints->min('reported_at'))) : date('01/m/Y'));

        $endDateFormatted = $request->filled('end_date')
            ? date('d/m/Y', strtotime($request->end_date))
            : ($complaints->isNotEmpty() ? date('d/m/Y', strtotime($complaints->max('reported_at'))) : date('d/m/Y'));

        $filename = 'Laporan_Pengaduan_SIPFK_' . date('Ymd_His') . '.pdf';

        // Jika library DomPDF tersedia dalam environment, dapat digunakan; jika tidak, gunakan SimplePdfReport murni
        if (class_exists(Pdf::class)) {
            try {
                $pdf = Pdf::loadView('admin.reports.pdf', compact('complaints', 'startDateFormatted', 'endDateFormatted'))
                    ->setPaper('a4', 'landscape');

                if ($request->has('stream')) {
                    return $pdf->stream($filename);
                }
                return $pdf->download($filename);
            } catch (\Throwable $e) {
                // Fallback otomatis ke SimplePdfReport murni jika terjadi kendala driver
            }
        }

        // SimplePdfReport Generator (Standar PDF 1.4 Native & Cepat)
        $report = SimplePdfReport::create(
            'SIPFK - SISTEM INFORMASI PENGADUAN FASILITAS KAMPUS',
            'Laporan Rekapitulasi Data Pengaduan dan Tindak Lanjut Kerusakan Fasilitas'
        );

        $report->setMeta([
            'Periode'    => $startDateFormatted . ' s/d ' . $endDateFormatted,
            'Status'     => $request->filled('status') ? ucfirst($request->status) : 'Semua Status',
            'Total Data' => $complaints->count() . ' Pengaduan',
            'Tanggal Cetak' => date('d/m/Y H:i') . ' WIB',
        ]);

        $report->setColumns([
            ['key' => 'no',            'label' => 'No',                  'width' => 0.4, 'align' => 'center'],
            ['key' => 'reported_at',   'label' => 'Tanggal',             'width' => 0.9, 'align' => 'center'],
            ['key' => 'reporter_name', 'label' => 'Nama Pelapor',        'width' => 1.4, 'align' => 'left'],
            ['key' => 'category',      'label' => 'Kategori Fasilitas',  'width' => 1.4, 'align' => 'left'],
            ['key' => 'location',      'label' => 'Lokasi Fasilitas',    'width' => 1.6, 'align' => 'left'],
            ['key' => 'description',   'label' => 'Deskripsi Kerusakan', 'width' => 3.2, 'align' => 'left'],
            ['key' => 'status',        'label' => 'Status',              'width' => 0.9, 'align' => 'center'],
        ]);

        foreach ($complaints as $idx => $c) {
            $followUpNotes = $c->followUps->map(function ($fu) {
                return ($fu->admin->name ?? 'Admin') . ': ' . $fu->note;
            })->implode(' | ');

            $report->addRow([
                'no'              => $idx + 1,
                'reported_at'     => date('d/m/Y', strtotime($c->reported_at)),
                'reporter_name'   => $c->reporter_name,
                'category'        => $c->category->name ?? 'Tanpa Kategori',
                'location'        => $c->location,
                'description'     => $c->description,
                'status'          => $c->status,
                'follow_ups_text' => $followUpNotes,
            ]);
        }

        $adminName = Auth::user()->name ?? 'Admin SIPFK';
        $report->setFooter(
            "SIPFK TRAS Kelompok G5  |  Dicetak oleh: {$adminName}",
            "Dokumen Resmi Sistem Informasi Pengaduan Fasilitas Kampus"
        );

        $pdfBinary = $report->render();
        $disposition = $request->has('stream') ? 'inline' : 'attachment';

        return response($pdfBinary, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
            'Content-Length'      => strlen($pdfBinary),
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }
}
