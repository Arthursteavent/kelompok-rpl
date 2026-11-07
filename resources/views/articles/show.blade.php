@extends('layouts.app')

@section('title', $article->title . ' - PortalBerita')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-4 text-xs text-slate-500 dark:text-slate-400 flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400">Beranda</a>
        <span>/</span>
        <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="hover:text-blue-600 dark:hover:text-sky-400">
            {{ $article->category->name }}
        </a>
        <span>/</span>
        <span class="text-slate-800 dark:text-slate-200 font-medium truncate max-w-xs">{{ $article->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Detail Column -->
        <div class="lg:col-span-8 space-y-6">
            <article class="rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <a href="{{ route('home', ['kategori' => $article->category->slug]) }}"
                       class="px-3.5 py-1 rounded-full text-xs font-bold {{ $article->category->tailwindBadgeClass() }}">
                        <i class="bi bi-tag-fill mr-1"></i>{{ $article->category->name }}
                    </a>

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('articles.edit', $article) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/60 dark:hover:bg-blue-900/60 dark:text-sky-400 border border-blue-200 dark:border-blue-800 transition">
                                    <i class="bi bi-pencil-square mr-1"></i>Edit Artikel
                                </a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800 transition cursor-pointer">
                                        <i class="bi bi-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-tight mb-4">
                    {{ $article->title }}
                </h1>

                <!-- Article Meta -->
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400 pb-4 mb-6 border-b border-slate-200 dark:border-slate-800">
                    <span>
                        <i class="bi bi-person-circle text-blue-600 dark:text-sky-400 mr-1"></i>
                        Ditulis oleh <strong class="text-slate-800 dark:text-slate-200">{{ $article->author }}</strong>
                    </span>
                    <span>
                        <i class="bi bi-calendar-event mr-1"></i>
                        {{ $article->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </span>
                    <span>
                        <i class="bi bi-eye mr-1"></i>
                        {{ number_format($article->views) }} kali dibaca
                    </span>
                </div>

                <!-- Cover Image / Default Placeholder -->
                <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
                    @if ($article->image_url)
                        <img
                            src="{{ $article->image_url }}"
                            alt=""
                            class="w-full max-h-[440px] object-cover"
                            onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                        >
                    @endif
                    <div class="{{ $article->image_url ? 'hidden' : 'flex' }} min-h-[260px] flex-col items-center justify-center text-center p-8 bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200 dark:from-slate-800 dark:via-slate-900 dark:to-slate-950">
                        <div class="w-16 h-16 rounded-2xl bg-blue-600/10 dark:bg-sky-400/10 border border-blue-500/20 dark:border-sky-400/20 flex items-center justify-center text-blue-600 dark:text-sky-400 text-3xl mb-3">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div class="font-bold text-slate-800 dark:text-white">PortalBerita.com — {{ $article->category->name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Ilustrasi Sampul Artikel Berita</div>
                    </div>
                </div>

                <!-- Excerpt / Lead -->
                @if ($article->excerpt)
                    <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-800/70 border-l-4 border-blue-600 dark:border-sky-400 text-sm font-medium text-slate-700 dark:text-slate-300 mb-6">
                        {{ $article->excerpt }}
                    </div>
                @endif

                <!-- Full Content -->
                <div class="text-base leading-relaxed text-slate-700 dark:text-slate-300 space-y-4">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                    </a>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Kategori:
                        <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="font-bold text-blue-600 dark:text-sky-400 hover:underline">
                            {{ $article->category->name }}
                        </a>
                    </span>
                </div>
            </article>

            <!-- Related Articles in the Same Category -->
            @if ($relatedArticles->isNotEmpty())
                <div class="rounded-3xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h5 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="bi bi-intersect text-blue-600 dark:text-sky-400"></i>
                        <span>Berita Terkait di Kategori {{ $article->category->name }}</span>
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($relatedArticles as $rel)
                            <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 flex flex-col">
                                <div class="h-32 bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                    @if ($rel->image_url)
                                        <img
                                            src="{{ $rel->image_url }}"
                                            alt=""
                                            class="w-full h-full object-cover"
                                            onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                        >
                                    @endif
                                    <div class="{{ $rel->image_url ? 'hidden' : 'flex' }} w-full h-full flex-col items-center justify-center text-center bg-gradient-to-br from-slate-200 to-slate-100 dark:from-slate-800 dark:to-slate-950">
                                        <i class="bi bi-newspaper text-xl text-blue-600 dark:text-sky-400"></i>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">PortalBerita.com</div>
                                    </div>
                                </div>
                                <div class="p-3.5 flex-1 flex flex-col justify-between">
                                    <h6 class="text-xs font-bold text-slate-900 dark:text-white leading-snug hover:text-blue-600 dark:hover:text-sky-400 transition">
                                        <a href="{{ route('articles.show', $rel->slug) }}">
                                            {{ Str::limit($rel->title, 65) }}
                                        </a>
                                    </h6>
                                    <div class="text-[11px] text-slate-400 mt-2">
                                        {{ $rel->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <div class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h6 class="text-sm font-extrabold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="bi bi-tags-fill text-blue-600 dark:text-sky-400"></i>
                    <span>Jelajahi Kategori Lain</span>
                </h6>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-blue-600 hover:text-white dark:bg-slate-800 dark:hover:bg-sky-500 dark:hover:text-slate-950 text-slate-700 dark:text-slate-300 transition">
                            {{ $cat->name }} ({{ $cat->articles_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
