<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard ringkasan fasilitas kampus dengan 4 kartu metrik statistik (FR-08, AC-07).
     *
     * Menghitung empat metrik langsung dari database:
     * 1. Total seluruh pengaduan
     * 2. Jumlah pengaduan berstatus 'baru'
     * 3. Jumlah pengaduan berstatus 'diproses'
     * 4. Jumlah pengaduan berstatus 'selesai'
     */
    public function index(): View
    {
        $stats = [
            'total' => Complaint::count(),
            'baru' => Complaint::where('status', Complaint::STATUS_BARU)->count(),
            'diproses' => Complaint::where('status', Complaint::STATUS_DIPROSES)->count(),
            'selesai' => Complaint::where('status', Complaint::STATUS_SELESAI)->count(),
        ];

        // Mengambil 5 pengaduan terbaru untuk pratinjau tabel aktivitas
        $recentComplaints = Complaint::with('category')
            ->latest('reported_at')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentComplaints'));
    }
}
