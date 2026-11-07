<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Halaman Publik: Daftar Berita (Blog Mini) dengan Filter Kategori & Pencarian.
     */
    public function home(Request $request): View
    {
        $categories = Category::withCount(['articles' => function ($query) {
            $query->where('is_published', true);
        }])->orderBy('name')->get();

        $activeCategory = null;
        if ($request->filled('kategori')) {
            $activeCategory = Category::where('slug', $request->string('kategori'))->first();
        }

        $search = trim((string) $request->query('q', ''));

        $query = Article::with('category')
            ->where('is_published', true)
            ->when($activeCategory, fn ($q) => $q->where('category_id', $activeCategory->id))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->latest();

        $articles = $query
            ->paginate(6)
            ->withQueryString();

        $sliderArticles = Article::with('category')
            ->where('is_published', true)
            ->latest()
            ->limit(5)
            ->get();

        $popularArticles = Article::with('category')
            ->where('is_published', true)
            ->orderByDesc('views')
            ->limit(4)
            ->get();

        return view('articles.home', compact(
            'articles',
            'sliderArticles',
            'categories',
            'activeCategory',
            'search',
            'popularArticles'
        ));
    }

    /**
     * Halaman Publik: Detail Berita & Artikel Terkait berdasarkan Kategori.
     */
    public function show(string $slug): View
    {
        $article = Article::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views');

        $relatedArticles = Article::with('category')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->latest()
            ->limit(3)
            ->get();

        $categories = Category::withCount(['articles' => function ($query) {
            $query->where('is_published', true);
        }])->orderBy('name')->get();

        return view('articles.show', compact('article', 'relatedArticles', 'categories'));
    }

    /**
     * Halaman Admin CRUD: Tabel Daftar Semua Artikel.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $categoryId = $request->query('category_id');
        $search = trim((string) $request->query('q', ''));

        $articles = Article::with('category')
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('articles.index', compact('articles', 'categories', 'categoryId', 'search'));
    }

    /**
     * Halaman Admin CRUD: Form Tambah Artikel Baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('articles.create', compact('categories'));
    }

    /**
     * Simpan Artikel Baru ke Database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'author' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image_file' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = $this->generateUniqueSlug($validated['title']);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('articles', 'public');
            $imageUrl = Storage::url($path);
        }

        $excerpt = ($validated['excerpt'] ?? null) ?: Str::limit(strip_tags($validated['content']), 160);

        $article = Article::create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'author' => $validated['author'],
            'excerpt' => $excerpt,
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()
            ->route('articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil ditambahkan!");
    }

    /**
     * Halaman Admin CRUD: Form Edit Artikel.
     */
    public function edit(Article $article): View
    {
        $categories = Category::orderBy('name')->get();

        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Perbarui Artikel di Database.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'author' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image_file' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = $article->title !== $validated['title']
            ? $this->generateUniqueSlug($validated['title'], $article->id)
            : $article->slug;

        $imageUrl = $validated['image_url'] ?? $article->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('articles', 'public');
            $imageUrl = Storage::url($path);
        }

        $excerpt = ($validated['excerpt'] ?? null) ?: Str::limit(strip_tags($validated['content']), 160);

        $article->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'author' => $validated['author'],
            'excerpt' => $excerpt,
            'content' => $validated['content'],
            'image_url' => $imageUrl,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil diperbarui!");
    }

    /**
     * Hapus Artikel dari Database.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $title = $article->title;
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', "Artikel \"{$title}\" berhasil dihapus.");
    }

    /**
     * Buat slug unik dari judul artikel.
     */
    private function generateUniqueSlug(string $title, ?string $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'artikel';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
