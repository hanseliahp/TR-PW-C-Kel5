<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh kategori fasilitas di panel admin (FR-04).
     */
    public function index(): View
    {
        $categories = Category::withCount('complaints')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori fasilitas baru (FR-04, FR-10).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'description.max' => 'Deskripsi kategori maksimal 1000 karakter.',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with(
            'success',
            'Kategori fasilitas berhasil ditambahkan.'
        );
    }

    /**
     * Memperbarui kategori fasilitas yang sudah ada (FR-04, FR-10).
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'description.max' => 'Deskripsi kategori maksimal 1000 karakter.',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with(
            'success',
            'Kategori fasilitas berhasil diperbarui.'
        );
    }

    /**
     * Menghapus kategori fasilitas (FR-04, BR-08).
     *
     * BR-08: Kategori yang masih digunakan oleh pengaduan tidak boleh dihapus
     * sebelum relasinya ditangani.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->complaints()->exists()) {
            return redirect()->route('admin.categories.index')->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan (Aturan BR-08).'
            );
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with(
            'success',
            'Kategori berhasil dihapus.'
        );
    }
}
