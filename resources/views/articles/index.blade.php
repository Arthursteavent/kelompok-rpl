@extends('layouts.app')

@section('title', 'Kelola Artikel - Narasi Digital')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Kelola Artikel</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Manajemen daftar artikel berita.</p>
        </div>

        <div class="flex items-center gap-2 text-xs">
            @if (auth()->user()?->canManageCategories())
                <a href="{{ route('categories.index') }}" class="px-3.5 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    Kelola Kategori
                </a>
            @endif
            <a href="{{ route('articles.create') }}" class="px-3.5 py-2 rounded-lg font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                Tambah Artikel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="rounded-xl p-4 mb-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
        <form action="{{ route('articles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Cari judul atau penulis..."
                    class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                >
            </div>
            <div class="md:col-span-4">
                <select
                    name="category_id"
                    class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                >
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $categoryId === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                    Filter
                </button>
                @if ($search !== '' || $categoryId)
                    <a href="{{ route('articles.index') }}" class="py-2 px-3 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700 inline-flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 text-xs border-b border-zinc-200 dark:border-zinc-800">
                        <th class="py-3 pl-5 pr-3 w-28">UUID</th>
                        <th class="py-3 px-3">Judul Artikel</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Penulis</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Dibaca</th>
                        <th class="py-3 px-3">Tanggal</th>
                        <th class="py-3 pl-3 pr-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="py-3.5 pl-5 pr-3 text-xs font-mono text-zinc-400" title="{{ $article->id }}">
                                {{ substr($article->id, 0, 8) }}&hellip;
                            </td>
                            <td class="py-3.5 px-3 max-w-xs">
                                <a href="{{ route('articles.show', $article->slug) }}" class="font-semibold text-zinc-900 dark:text-white hover:underline block truncate">
                                    {{ $article->title }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3 text-xs text-zinc-600 dark:text-zinc-400">
                                {{ $article->category->name }}
                            </td>
                            <td class="py-3.5 px-3 text-xs text-zinc-600 dark:text-zinc-400">{{ $article->author }}</td>
                            <td class="py-3.5 px-3 text-xs">
                                {{ $article->is_published ? 'Tayang' : 'Draft' }}
                            </td>
                            <td class="py-3.5 px-3 text-xs text-zinc-500">{{ number_format($article->views) }}</td>
                            <td class="py-3.5 px-3 text-xs text-zinc-500">{{ $article->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 pl-3 pr-5 text-right text-xs">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('articles.edit', $article) }}" class="text-zinc-700 dark:text-zinc-300 hover:underline">Edit</a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-zinc-400 text-xs">
                                Belum ada artikel berita.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection
