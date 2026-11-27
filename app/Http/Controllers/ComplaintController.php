<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    /**
     * Menampilkan formulir publik untuk pengiriman pengaduan fasilitas kampus (FR-03).
     * Dapat diakses secara terbuka tanpa perlu login.
     */
    public function create(): View
    {
        try {
            $categories = Category::orderBy('name')->get();
        } catch (\Throwable $e) {
            $categories = collect();
        }

        return view('public.complaint-form', compact('categories'));
    }

    /**
     * Menyimpan pengaduan fasilitas baru dari formulir publik (FR-03, FR-10, NFR-04).
     * Mengimplementasikan aturan bisnis:
     * - BR-01: Setiap pengaduan harus terhubung dengan kategori yang valid.
     * - BR-02: Nama pelapor, kategori, lokasi, tanggal, dan deskripsi wajib diisi.
     * - BR-04: Pengaduan baru otomatis mendapatkan status 'baru'.
     * - BR-07: Lokasi diisi secara manual berupa teks, bukan dari GPS.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateComplaintInput($request);

        Complaint::create([
            'reporter_name' => $validated['reporter_name'],
            'category_id' => (int) $validated['category_id'],
            'location' => $validated['location'],
            'reported_at' => $validated['reported_at'],
            'description' => $validated['description'],
            'status' => Complaint::STATUS_BARU, // BR-04: Otomatis 'baru'
        ]);

        return redirect()->route('home')->with(
            'success',
            'Pengaduan fasilitas Anda berhasil dikirim dengan status "baru". Terima kasih atas kontribusi Anda!'
        );
    }

    /**
     * Menampilkan daftar seluruh pengaduan di panel admin (FR-05).
     * Mendukung pencarian kata kunci serta filter kategori dan status.
     */
    public function index(Request $request): View
    {
        $query = Complaint::with('category')->latest('reported_at');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $searchTerm = $request->string('search')->toString();
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reporter_name', 'like', "%{$searchTerm}%")
                    ->orWhere('location', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.complaints.index', compact('complaints', 'categories'));
    }

    /**
     * Menyimpan data pengaduan baru melalui panel admin (FR-05, FR-10).
     */
    public function adminStore(Request $request): RedirectResponse
    {
        $validated = $this->validateComplaintInput($request, isStatusRequired: false);

        Complaint::create([
            'reporter_name' => $validated['reporter_name'],
            'category_id' => (int) $validated['category_id'],
            'location' => $validated['location'],
            'reported_at' => $validated['reported_at'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? Complaint::STATUS_BARU,
        ]);

        return redirect()->route('admin.complaints.index')->with(
            'success',
            'Data pengaduan fasilitas berhasil ditambahkan oleh admin.'
        );
    }

    /**
     * Menampilkan detail lengkap suatu pengaduan beserta riwayat catatan tindak lanjut (FR-05, FR-06, FR-07).
     */
    public function show(int $id): View
    {
        $complaint = Complaint::with(['category', 'followUps.admin'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint', 'categories'));
    }

    /**
     * Memperbarui seluruh data pengaduan fasilitas oleh admin (FR-05, FR-10).
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $complaint = Complaint::findOrFail($id);
        $validated = $this->validateComplaintInput($request, isStatusRequired: true);

        $complaint->update([
            'reporter_name' => $validated['reporter_name'],
            'category_id' => (int) $validated['category_id'],
            'location' => $validated['location'],
            'reported_at' => $validated['reported_at'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with(
            'success',
            'Data pengaduan fasilitas berhasil diperbarui.'
        );
    }

    /**
     * Memperbarui status penanganan pengaduan secara cepat (FR-06, BR-03).
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Complaint::statuses())],
        ], [
            'status.required' => 'Status pengaduan wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid. Status hanya boleh baru, diproses, atau selesai.',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update(['status' => $validated['status']]);

        return redirect()->route('admin.complaints.show', $id)->with(
            'success',
            'Status pengaduan berhasil diperbarui menjadi "'.$validated['status'].'".'
        );
    }

    /**
     * Menghapus data pengaduan fasilitas kampus (FR-05).
     */
    public function destroy(int $id): RedirectResponse
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('admin.complaints.index')->with(
            'success',
            'Pengaduan fasilitas berhasil dihapus.'
        );
    }

    /**
     * Helper validasi input pengaduan sisi server yang seragam (FR-10, NFR-04, BR-01, BR-02, BR-07).
     *
     * @return array<string, mixed>
     */
    private function validateComplaintInput(Request $request, bool $isStatusRequired = false): array
    {
        $rules = [
            'reporter_name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:150'],
            'reported_at' => ['required', 'date'],
            'description' => ['required', 'string', 'max:3000'],
        ];

        if ($isStatusRequired) {
            $rules['status'] = ['required', Rule::in(Complaint::statuses())];
        } elseif ($request->has('status')) {
            $rules['status'] = ['nullable', Rule::in(Complaint::statuses())];
        }

        return $request->validate($rules, [
            'reporter_name.required' => 'Nama lengkap pelapor wajib diisi.',
            'reporter_name.string' => 'Nama pelapor harus berupa teks.',
            'reporter_name.max' => 'Nama pelapor maksimal 100 karakter.',
            'category_id.required' => 'Kategori fasilitas wajib dipilih.',
            'category_id.integer' => 'Kategori fasilitas tidak valid.',
            'category_id.exists' => 'Kategori fasilitas yang dipilih tidak terdaftar di sistem.',
            'location.required' => 'Lokasi fasilitas wajib dicantumkan secara manual.',
            'location.string' => 'Lokasi fasilitas harus berupa teks.',
            'location.max' => 'Lokasi fasilitas maksimal 150 karakter.',
            'reported_at.required' => 'Tanggal laporan wajib diisi.',
            'reported_at.date' => 'Format tanggal laporan tidak valid.',
            'description.required' => 'Deskripsi kerusakan fasilitas wajib diisi.',
            'description.string' => 'Deskripsi kerusakan harus berupa teks.',
            'description.max' => 'Deskripsi kerusakan maksimal 3000 karakter.',
            'status.required' => 'Status pengaduan wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid. Hanya menerima: baru, diproses, atau selesai.',
        ]);
    }
}
