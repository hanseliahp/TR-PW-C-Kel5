<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ReportController;
use App\Models\Complaint;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPFK (Kelompok G5)
|--------------------------------------------------------------------------
| Aplikasi Sistem Informasi Pengaduan Fasilitas Kampus.
| Dilindungi autentikasi dan middleware untuk panel pengelola admin.
| Terintegrasi dengan fitur autentikasi, manajemen CRUD, dan laporan PDF.
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE PUBLIK (Pelapor) - Terbuka tanpa login (FR-03, BR-04, BR-07)
// ==========================================
Route::get('/', [ComplaintController::class, 'create'])->name('home');
Route::get('/pengaduan/buat', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('complaints.store');

// ==========================================
// 2. RUTE AUTENTIKASI ADMIN (FR-01, FR-02)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================================
// 3. RUTE PANEL ADMIN (FR-02, FR-04, FR-05, FR-06, FR-07, FR-08, FR-09)
// DILINDUNGI MIDDLEWARE 'auth' (Anggota 5)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Redirect /admin ke /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard dengan 4 Kartu Statistik (FR-08, 5.1)
    Route::get('/dashboard', function () {
        $total = Complaint::count();
        $baru = Complaint::where('status', Complaint::STATUS_BARU)->count();
        $diproses = Complaint::where('status', Complaint::STATUS_DIPROSES)->count();
        $selesai = Complaint::where('status', Complaint::STATUS_SELESAI)->count();

        $stats = [
            'total' => $total,
            'baru' => $baru,
            'diproses' => $diproses,
            'selesai' => $selesai,
        ];

        $recentComplaints = Complaint::with('category')->latest('reported_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentComplaints'));
    })->name('dashboard');

    // Manajemen Kategori (FR-04, BR-08)
    Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Manajemen Pengaduan (FR-05)
    Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/pengaduan', [ComplaintController::class, 'adminStore'])->name('complaints.store');
    Route::get('/pengaduan/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/pengaduan/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::put('/pengaduan/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::delete('/pengaduan/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Simpan Catatan Tindak Lanjut (FR-07, BR-05, BR-06)
    Route::post('/pengaduan/{complaint}/tindak-lanjut', function (Request $request, $id) {
        $validated = $request->validate([
            'note' => ['required', 'string'],
        ], [
            'note.required' => 'Catatan tindak lanjut wajib diisi.',
        ]);

        FollowUp::create([
            'complaint_id' => $id,
            'admin_id' => Auth::id() ?? 1,
            'note' => $validated['note'],
        ]);

        return redirect()->route('admin.complaints.show', $id)
            ->with('success', 'Catatan tindak lanjut berhasil ditambahkan.');
    })->name('complaints.follow-up.store');

    // Laporan Rekapitulasi & Export PDF (FR-09, Section 5.2)
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});
