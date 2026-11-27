<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    /**
     * Menyimpan catatan tindak lanjut pengaduan fasilitas kampus (FR-07, BR-05, BR-06).
     *
     * Aturan bisnis yang diterapkan:
     * - BR-05: Catatan tindak lanjut terhubung otomatis ke complaint_id dan ID admin yang sedang login (Auth::id()).
     * - BR-06: Mendukung pencatatan multi-follow-up (lebih dari satu catatan per pengaduan).
     * - NFR-04: Validasi sisi server memastikan teks catatan wajib diisi dengan batasan panjang wajar.
     */
    public function store(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:3000'],
        ], [
            'note.required' => 'Catatan tindak lanjut wajib diisi.',
            'note.string' => 'Catatan tindak lanjut harus berupa teks.',
            'note.max' => 'Catatan tindak lanjut maksimal 3000 karakter.',
        ]);

        $complaint->followUps()->create([
            'admin_id' => Auth::id(),
            'note' => $validated['note'],
        ]);

        return redirect()->route('admin.complaints.show', $complaint->id)->with(
            'success',
            'Catatan tindak lanjut berhasil ditambahkan.'
        );
    }
}
