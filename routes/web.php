<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\User;
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
// 1. RUTE PUBLIK (Pelapor) - Terbuka tanpa login
// ==========================================
Route::get('/', function () {
    try {
        $categories = Category::all();
    } catch (\Throwable $e) {
        $categories = collect();
    }

    return view('public.complaint-form', compact('categories'));
})->name('home');

Route::get('/pengaduan/buat', function () {
    return redirect()->route('home');
})->name('complaints.create');

Route::post('/pengaduan', function (Request $request) {
    // Validasi formulir pengaduan (FR-10, BR-02)
    $validated = $request->validate([
        'reporter_name' => ['required', 'string', 'max:100'],
        'category_id'   => ['required', 'exists:categories,id'],
        'location'      => ['required', 'string', 'max:150'],
        'reported_at'   => ['required', 'date'],
        'description'   => ['required', 'string'],
    ], [
        'reporter_name.required' => 'Nama lengkap pelapor wajib diisi.',
        'category_id.required'   => 'Kategori fasilitas wajib dipilih.',
        'category_id.exists'     => 'Kategori fasilitas tidak valid.',
        'location.required'      => 'Lokasi fasilitas wajib dicantumkan.',
        'reported_at.required'   => 'Tanggal laporan wajib diisi.',
        'description.required'   => 'Deskripsi kerusakan fasilitas wajib diisi.',
    ]);

    Complaint::create([
        'reporter_name' => $validated['reporter_name'],
        'category_id'   => $validated['category_id'],
        'location'      => $validated['location'],
        'reported_at'   => $validated['reported_at'],
        'description'   => $validated['description'],
        'status'        => 'baru',
    ]);

    return redirect()->route('home')->with('success', 'Pengaduan fasilitas Anda berhasil dikirim dengan status "baru". Terima kasih atas kontribusi Anda!');
})->name('complaints.store');


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
        $baru = Complaint::where('status', 'baru')->count();
        $diproses = Complaint::where('status', 'diproses')->count();
        $selesai = Complaint::where('status', 'selesai')->count();

        $stats = [
            'total'    => $total,
            'baru'     => $baru,
            'diproses' => $diproses,
            'selesai'  => $selesai,
        ];

        $recentComplaints = Complaint::with('category')->latest('reported_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentComplaints'));
    })->name('dashboard');

    // Manajemen Kategori (FR-04, BR-08)
    Route::get('/kategori', function () {
        $categories = Category::withCount('complaints')->get();
        return view('admin.categories.index', compact('categories'));
    })->name('categories.index');

    Route::post('/kategori', function (Request $request) {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori fasilitas berhasil ditambahkan.');
    })->name('categories.store');

    Route::put('/kategori/{category}', function (Request $request, $id) {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $cat = Category::findOrFail($id);
        $cat->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori fasilitas berhasil diperbarui.');
    })->name('categories.update');

    Route::delete('/kategori/{category}', function ($id) {
        $cat = Category::findOrFail($id);
        if ($cat->complaints()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan (Aturan BR-08).');
        }
        $cat->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    })->name('categories.destroy');

    // Manajemen Pengaduan (FR-05)
    Route::get('/pengaduan', function (Request $request) {
        $query = Complaint::with('category')->latest('reported_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reporter_name', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.complaints.index', compact('complaints', 'categories'));
    })->name('complaints.index');

    // Detail Pengaduan & Catatan Tindak Lanjut (FR-06, FR-07, BR-05, BR-06)
    Route::get('/pengaduan/{complaint}', function ($id) {
        $complaint = Complaint::with(['category', 'followUps.admin'])->findOrFail($id);
        return view('admin.complaints.show', compact('complaint'));
    })->name('complaints.show');

    // Perbarui Status Pengaduan (FR-06, BR-03)
    Route::put('/pengaduan/{complaint}/status', function (Request $request, $id) {
        $validated = $request->validate([
            'status' => ['required', 'in:baru,diproses,selesai'],
        ]);

        $c = Complaint::findOrFail($id);
        $c->update(['status' => $validated['status']]);

        return redirect()->route('admin.complaints.show', $id)
            ->with('success', 'Status pengaduan berhasil diperbarui menjadi "' . $validated['status'] . '".');
    })->name('complaints.update-status');

    // Simpan Catatan Tindak Lanjut (FR-07, BR-05, BR-06)
    Route::post('/pengaduan/{complaint}/tindak-lanjut', function (Request $request, $id) {
        $validated = $request->validate([
            'note' => ['required', 'string'],
        ], [
            'note.required' => 'Catatan tindak lanjut wajib diisi.',
        ]);

        FollowUp::create([
            'complaint_id' => $id,
            'admin_id'     => Auth::id() ?? 1,
            'note'         => $validated['note'],
        ]);

        return redirect()->route('admin.complaints.show', $id)
            ->with('success', 'Catatan tindak lanjut berhasil ditambahkan.');
    })->name('complaints.follow-up.store');

    // Hapus Pengaduan
    Route::delete('/pengaduan/{complaint}', function ($id) {
        $c = Complaint::findOrFail($id);
        $c->delete();

        return redirect()->route('admin.complaints.index')->with('success', 'Pengaduan berhasil dihapus.');
    })->name('complaints.destroy');

    // Laporan Rekapitulasi & Export PDF (FR-09, Section 5.2)
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});
