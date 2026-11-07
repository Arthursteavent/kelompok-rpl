@extends('layouts.app')

@section('title', $article->title . ' - Narasi Digital')

@section('content')
    <nav class="mb-4 text-xs text-zinc-500 flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
        <span>/</span>
        <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="hover:underline">
            {{ $article->category->name }}
        </a>
        <span>/</span>
        <span class="text-zinc-800 dark:text-zinc-200 truncate max-w-xs">{{ $article->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-6">
            <article class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <a href="{{ route('home', ['kategori' => $article->category->slug]) }}"
                       class="px-3 py-1 rounded-md text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                        {{ $article->category->name }}
                    </a>

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <div class="flex items-center gap-2 text-xs">
                                <a href="{{ route('articles.edit', $article) }}" class="px-3 py-1 rounded-md border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                    Edit
                                </a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-md border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white leading-tight mb-3">
                    {{ $article->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 text-xs text-zinc-500 pb-4 mb-6 border-b border-zinc-200 dark:border-zinc-800">
                    <span>Oleh <strong class="text-zinc-800 dark:text-zinc-200">{{ $article->author }}</strong></span>
                    <span>&bull;</span>
                    <span>{{ $article->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    <span>&bull;</span>
                    <span>{{ number_format($article->views) }}x dibaca</span>
                </div>

                @if ($article->image_url)
                    <div class="mb-6 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        <img
                            src="{{ $article->image_url }}"
                            alt=""
                            class="w-full max-h-[420px] object-cover"
                            onerror="this.parentElement.classList.add('hidden');"
                        >
                    </div>
                @endif

                @if ($article->excerpt)
                    <div class="p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border-l-2 border-zinc-400 dark:border-zinc-600 text-sm text-zinc-600 dark:text-zinc-300 mb-6">
                        {{ $article->excerpt }}
                    </div>
                @endif

                <div class="text-base leading-relaxed text-zinc-800 dark:text-zinc-200 space-y-4">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <div class="mt-8 pt-5 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-xs">
                    <a href="{{ route('home') }}" class="font-medium text-zinc-600 dark:text-zinc-400 hover:underline">
                        &larr; Kembali ke Beranda
                    </a>
                </div>
            </article>

            @if ($relatedArticles->isNotEmpty())
                <div class="rounded-2xl p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                    <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">
                        Berita Terkait
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($relatedArticles as $rel)
                            <div class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800 flex flex-col">
                                <div class="p-3.5 flex-1 flex flex-col justify-between">
                                    <h6 class="text-xs font-semibold text-zinc-900 dark:text-white leading-snug hover:underline">
                                        <a href="{{ route('articles.show', $rel->slug) }}">
                                            {{ Str::limit($rel->title, 65) }}
                                        </a>
                                    </h6>
                                    <div class="text-[11px] text-zinc-400 mt-2">
                                        {{ $rel->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="rounded-xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <h6 class="text-sm font-bold text-zinc-900 dark:text-white mb-3">Kategori Lainnya</h6>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
                            {{ $cat->name }} ({{ $cat->articles_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
