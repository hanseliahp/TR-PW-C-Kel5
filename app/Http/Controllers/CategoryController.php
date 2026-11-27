<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('complaints')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

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
