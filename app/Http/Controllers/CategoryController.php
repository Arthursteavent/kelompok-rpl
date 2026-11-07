<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Halaman CRUD Kategori beserta jumlah artikel pada tiap kategori.
     */
    public function index(): View
    {
        $categories = Category::withCount('articles')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Simpan Kategori baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'in:primary,success,danger,warning,info,dark,secondary'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = $this->generateUniqueSlug($validated['name']);

        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->boolean('from_article_form')) {
            return redirect()
                ->route('articles.create')
                ->with('success', "Kategori \"{$validated['name']}\" berhasil disimpan ke database!");
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan!");
    }

    /**
     * Perbarui Kategori.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'color' => ['required', 'string', 'in:primary,success,danger,warning,info,dark,secondary'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = $category->name !== $validated['name']
            ? $this->generateUniqueSlug($validated['name'], $category->id)
            : $category->slug;

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui!");
    }

    /**
     * Hapus Kategori (beserta artikel terkait via cascade atau validasi).
     */
    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    private function generateUniqueSlug(string $name, ?string $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'kategori';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

