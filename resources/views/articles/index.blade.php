@extends('layouts.app')

@section('title', 'Kelola Artikel (CRUD) - PortalBerita')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-journal-text text-blue-600 dark:text-sky-400"></i>
                <span>Manajemen CRUD Artikel</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Kelola daftar berita, tambah artikel baru, ubah konten, atau hapus artikel beserta relasi kategorinya.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if (auth()->user()?->canManageCategories())
                <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                    <i class="bi bi-tags mr-1"></i>Kelola Kategori
                </a>
            @endif
            <a href="{{ route('articles.create') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                <i class="bi bi-plus-lg mr-1"></i>Tambah Artikel
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-2xl p-4 mb-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('articles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Cari judul artikel atau penulis..."
                    class="w-full px-4 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div class="md:col-span-4">
                <select
                    name="category_id"
                    class="w-full px-4 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $categoryId === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white dark:bg-blue-600 dark:hover:bg-blue-500 transition cursor-pointer">
                    <i class="bi bi-funnel mr-1"></i>Filter
                </button>
                @if ($search !== '' || $categoryId)
                    <a href="{{ route('articles.index') }}" class="py-2 px-3 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 inline-flex items-center justify-center" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-100/80 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="py-3.5 pl-6 pr-3 w-28">UUID</th>
                        <th class="py-3.5 px-3">Artikel Berita</th>
                        <th class="py-3.5 px-3">Kategori</th>
                        <th class="py-3.5 px-3">Penulis</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-3">Dibaca</th>
                        <th class="py-3.5 px-3">Tanggal</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Aksi CRUD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-4 pl-6 pr-3 text-xs font-mono text-slate-400" title="{{ $article->id }}">
                                {{ substr($article->id, 0, 8) }}&hellip;
                            </td>
                            <td class="py-4 px-3 max-w-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-12 rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-800 shrink-0">
                                        @if ($article->image_url)
                                            <img
                                                src="{{ $article->image_url }}"
                                                alt=""
                                                class="w-full h-full object-cover"
                                                onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                            >
                                        @endif
                                        <div class="{{ $article->image_url ? 'hidden' : 'flex' }} w-full h-full items-center justify-center bg-slate-200 dark:bg-slate-800 text-blue-600 dark:text-sky-400">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-sky-400 block truncate">
                                            {{ $article->title }}
                                        </a>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 block truncate">
                                            {{ $article->excerpt }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $article->category->tailwindBadgeClass() }}">
                                    {{ $article->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-700 dark:text-slate-300">{{ $article->author }}</td>
                            <td class="py-4 px-3">
                                @if ($article->is_published)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                                        <i class="bi bi-check-circle"></i> Tayang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-500 dark:text-slate-400">
                                <i class="bi bi-eye mr-1"></i>{{ number_format($article->views) }}
                            </td>
                            <td class="py-4 px-3 text-xs text-slate-500 dark:text-slate-400">
                                {{ $article->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('articles.edit', $article) }}" class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900/60 text-blue-600 dark:text-sky-400 transition" title="Edit Artikel">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 transition cursor-pointer" title="Hapus Artikel">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="bi bi-inbox text-4xl block mb-2"></i>
                                Belum ada artikel berita. Silakan tambahkan artikel baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection
