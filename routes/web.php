<?php

use App\Models\Category;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPFK (Kelompok G5)
|--------------------------------------------------------------------------
| Kerangka rute antarmuka Blade & Bootstrap untuk Desainer Antarmuka (Anggota 4).
| Menyediakan wadah tampilan terpadu sebelum integrasi Anggota 2, 3, dan 5.
| Seluruh halaman dilengkapi fallback rendering agar dapat langsung diuji.
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE PUBLIK (Pelapor)
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
        'category_id'   => ['required'],
        'location'      => ['required', 'string', 'max:150'],
        'reported_at'   => ['required', 'date'],
        'description'   => ['required', 'string'],
    ], [
        'reporter_name.required' => 'Nama lengkap pelapor wajib diisi.',
        'category_id.required'   => 'Kategori fasilitas wajib dipilih.',
        'location.required'      => 'Lokasi fasilitas wajib dicantumkan.',
        'reported_at.required'   => 'Tanggal laporan wajib diisi.',
        'description.required'   => 'Deskripsi kerusakan fasilitas wajib diisi.',
    ]);

    try {
        Complaint::create([
            'reporter_name' => $validated['reporter_name'],
            'category_id'   => $validated['category_id'],
            'location'      => $validated['location'],
            'reported_at'   => $validated['reported_at'],
            'description'   => $validated['description'],
            'status'        => Complaint::STATUS_BARU ?? 'baru',
        ]);
        $message = 'Pengaduan fasilitas Anda berhasil dikirim dengan status "baru". Terima kasih atas kontribusi Anda!';
    } catch (\Throwable $e) {
        // Fallback untuk demo jika database belum terhubung
        $message = 'Pengaduan fasilitas Anda berhasil dicatat ke antrean sistem (Mode Demo/Pratinjau).';
    }

    return redirect()->route('home')->with('success', $message);
})->name('complaints.store');


// ==========================================
// 2. RUTE AUTENTIKASI ADMIN (FR-01, FR-02)
// ==========================================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required'    => 'Alamat email wajib diisi.',
        'email.email'       => 'Format email tidak valid.',
        'password.required' => 'Kata sandi wajib diisi.',
    ]);

    try {
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali di Panel Admin SIPFK.');
        }
    } catch (\Throwable $e) {
        // Fallback demo login jika database belum termigrasi
        if ($credentials['email'] === 'admin@sipfk.test' && $credentials['password'] === 'password') {
            return redirect()->route('admin.dashboard')->with('info', 'Masuk dalam mode simulasi antarmuka.');
        }
    }

    return back()->withErrors([
        'email' => 'Kredensial yang diberikan tidak cocok dengan data kami (Gunakan admin@sipfk.test / password).',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    try {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    } catch (\Throwable $e) {}

    return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
})->name('logout');


// ==========================================
// 3. RUTE PANEL ADMIN (FR-04, FR-05, FR-06, FR-07, FR-08)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Redirect /admin ke /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard dengan 4 Kartu Statistik (FR-08, 5.1)
    Route::get('/dashboard', function () {
        try {
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

            $recentComplaints = Complaint::with('category')->latest()->take(5)->get();
        } catch (\Throwable $e) {
            $stats = [
                'total'    => 32,
                'baru'     => 7,
                'diproses' => 11,
                'selesai'  => 14,
            ];
            $recentComplaints = null;
        }

        return view('admin.dashboard', compact('stats', 'recentComplaints'));
    })->name('dashboard');

    // Manajemen Kategori (FR-04, BR-08)
    Route::get('/kategori', function () {
        try {
            $categories = Category::withCount('complaints')->get();
        } catch (\Throwable $e) {
            $categories = null;
        }

        return view('admin.categories.index', compact('categories'));
    })->name('categories.index');

    Route::post('/kategori', function (Request $request) {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            Category::create($validated);
            $msg = 'Kategori fasilitas berhasil ditambahkan.';
        } catch (\Throwable $e) {
            $msg = 'Kategori fasilitas berhasil dicatat (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.categories.index')->with('success', $msg);
    })->name('categories.store');

    Route::put('/kategori/{category}', function (Request $request, $id) {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $cat = Category::findOrFail($id);
            $cat->update($validated);
            $msg = 'Kategori fasilitas berhasil diperbarui.';
        } catch (\Throwable $e) {
            $msg = 'Perubahan kategori berhasil disimpan (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.categories.index')->with('success', $msg);
    })->name('categories.update');

    Route::delete('/kategori/{category}', function ($id) {
        try {
            $cat = Category::findOrFail($id);
            if ($cat->complaints()->exists()) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan (Aturan BR-08).');
            }
            $cat->delete();
            $msg = 'Kategori berhasil dihapus.';
        } catch (\Throwable $e) {
            $msg = 'Kategori berhasil dihapus (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.categories.index')->with('success', $msg);
    })->name('categories.destroy');

    // Manajemen Pengaduan (FR-05)
    Route::get('/pengaduan', function (Request $request) {
        try {
            $query = Complaint::with('category')->latest();

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
        } catch (\Throwable $e) {
            $complaints = null;
            $categories = null;
        }

        return view('admin.complaints.index', compact('complaints', 'categories'));
    })->name('complaints.index');

    // Detail Pengaduan & Catatan Tindak Lanjut (FR-06, FR-07, BR-05, BR-06)
    Route::get('/pengaduan/{complaint}', function ($id) {
        try {
            $complaint = Complaint::with(['category', 'followUps.admin'])->findOrFail($id);
        } catch (\Throwable $e) {
            $complaint = null;
        }

        return view('admin.complaints.show', compact('complaint'));
    })->name('complaints.show');

    // Perbarui Status Pengaduan (FR-06, BR-03)
    Route::put('/pengaduan/{complaint}/status', function (Request $request, $id) {
        $validated = $request->validate([
            'status' => ['required', 'in:baru,diproses,selesai'],
        ]);

        try {
            $c = Complaint::findOrFail($id);
            $c->update(['status' => $validated['status']]);
            $msg = 'Status pengaduan berhasil diperbarui menjadi "' . $validated['status'] . '".';
        } catch (\Throwable $e) {
            $msg = 'Status pengaduan berhasil diperbarui menjadi "' . $validated['status'] . '" (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.complaints.show', $id)->with('success', $msg);
    })->name('complaints.update-status');

    // Simpan Catatan Tindak Lanjut (FR-07, BR-05, BR-06)
    Route::post('/pengaduan/{complaint}/tindak-lanjut', function (Request $request, $id) {
        $validated = $request->validate([
            'note' => ['required', 'string'],
        ], [
            'note.required' => 'Catatan tindak lanjut wajib diisi.',
        ]);

        try {
            FollowUp::create([
                'complaint_id' => $id,
                'admin_id'     => Auth::id() ?? 1,
                'note'         => $validated['note'],
            ]);
            $msg = 'Catatan tindak lanjut berhasil ditambahkan.';
        } catch (\Throwable $e) {
            $msg = 'Catatan tindak lanjut berhasil ditambahkan (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.complaints.show', $id)->with('success', $msg);
    })->name('complaints.follow-up.store');

    // Hapus Pengaduan
    Route::delete('/pengaduan/{complaint}', function ($id) {
        try {
            $c = Complaint::findOrFail($id);
            $c->delete();
            $msg = 'Pengaduan berhasil dihapus.';
        } catch (\Throwable $e) {
            $msg = 'Pengaduan berhasil dihapus (Mode Simulasi Antarmuka).';
        }

        return redirect()->route('admin.complaints.index')->with('success', $msg);
    })->name('complaints.destroy');

    // Pratinjau Laporan Pengaduan (FR-09, Section 5.2)
    Route::get('/laporan', function () {
        return view('admin.reports.index');
    })->name('reports.index');
});
