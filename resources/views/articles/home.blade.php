@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' - ' : '') . 'Narasi Digital')

@section('content')
    <!-- Slider Berita Terbaru -->
    @if ($sliderArticles->isNotEmpty())
        <div class="mb-8 rounded-2xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center justify-between mb-4">
                <div class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                    Berita Terbaru
                </div>
                <div class="flex items-center gap-1.5">
                    <button
                        type="button"
                        onclick="moveNewsSlide(-1)"
                        class="px-2.5 py-1 rounded-lg text-xs bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Prev
                    </button>
                    <button
                        type="button"
                        onclick="moveNewsSlide(1)"
                        class="px-2.5 py-1 rounded-lg text-xs bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 cursor-pointer"
                    >
                        Next
                    </button>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950">
                @foreach ($sliderArticles as $idx => $slide)
                    <div
                        class="news-slide-item {{ $idx === 0 ? 'grid' : 'hidden' }} grid-cols-1 md:grid-cols-12 items-stretch min-h-[240px]"
                        data-slide-index="{{ $idx }}"
                    >
                        <div class="md:col-span-5 bg-zinc-200 dark:bg-zinc-800 min-h-[200px] md:min-h-[240px] overflow-hidden">
                            @if ($slide->image_url)
                                <img
                                    src="{{ $slide->image_url }}"
                                    alt=""
                                    class="w-full h-full object-cover"
                                    onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                >
                            @endif
                            <div class="{{ $slide->image_url ? 'hidden' : 'flex' }} w-full h-full items-center justify-center p-6 bg-zinc-100 dark:bg-zinc-800/60 text-zinc-400 text-xs">
                                {{ $slide->category->name }}
                            </div>
                        </div>

                        <div class="md:col-span-7 p-5 sm:p-6 flex flex-col justify-center">
                            <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400 mb-2">
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $slide->category->name }}</span>
                                <span>&bull;</span>
                                <span>{{ $slide->created_at->diffForHumans() }}</span>
                            </div>

                            <h2 class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white mb-2 leading-snug hover:underline">
                                <a href="{{ route('articles.show', $slide->slug) }}">
                                    {{ $slide->title }}
                                </a>
                            </h2>

                            <p class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400 mb-4 line-clamp-2">
                                {{ Str::limit($slide->excerpt ?: strip_tags($slide->content), 150) }}
                            </p>

                            <div class="mt-auto pt-3 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500">
                                <span>{{ $slide->author }} &bull; {{ number_format($slide->views) }}x dibaca</span>
                                <a href="{{ route('articles.show', $slide->slug) }}" class="font-semibold text-zinc-900 dark:text-white hover:underline">
                                    Baca selengkapnya &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 flex items-center gap-1.5">
                @foreach ($sliderArticles as $idx => $slide)
                    <button
                        type="button"
                        onclick="goToNewsSlide({{ $idx }})"
                        class="news-slide-dot h-1.5 rounded-full transition-all cursor-pointer {{ $idx === 0 ? 'w-6 bg-zinc-800 dark:bg-zinc-200' : 'w-2 bg-zinc-300 dark:bg-zinc-700' }}"
                    ></button>
                @endforeach
            </div>
        </div>

        <script>
            (function () {
                let currentSlide = 0;
                const slides = document.querySelectorAll('.news-slide-item');
                const dots = document.querySelectorAll('.news-slide-dot');
                const totalSlides = slides.length;
                if (totalSlides <= 1) return;

                function renderSlide(index) {
                    slides.forEach((el, i) => {
                        el.classList.toggle('hidden', i !== index);
                        el.classList.toggle('grid', i === index);
                    });
                    dots.forEach((dot, i) => {
                        dot.className = i === index
                            ? 'news-slide-dot h-1.5 rounded-full transition-all cursor-pointer w-6 bg-zinc-800 dark:bg-zinc-200'
                            : 'news-slide-dot h-1.5 rounded-full transition-all cursor-pointer w-2 bg-zinc-300 dark:bg-zinc-700';
                    });
                }

                window.moveNewsSlide = function (step) {
                    currentSlide = (currentSlide + step + totalSlides) % totalSlides;
                    renderSlide(currentSlide);
                };

                window.goToNewsSlide = function (index) {
                    currentSlide = index;
                    renderSlide(currentSlide);
                };

                setInterval(function () {
                    window.moveNewsSlide(1);
                }, 4000);
            })();
        </script>
    @endif

    <!-- Info Filter -->
    @if ($activeCategory || $search !== '')
        <div class="mb-6 px-4 py-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
            <div class="text-sm font-semibold">
                @if ($activeCategory)
                    Kategori: {{ $activeCategory->name }}
                @elseif ($search !== '')
                    Pencarian: "{{ $search }}"
                @endif
            </div>
            <a href="{{ route('home') }}" class="text-xs text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                Reset
            </a>
        </div>
    @endif

    <!-- Grid Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Daftar Artikel -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white">Daftar Artikel</h3>
                <span class="text-xs text-zinc-500">{{ $articles->count() }} artikel</span>
            </div>

            @if ($articles->isEmpty())
                <div class="rounded-2xl p-10 text-center bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                    <p class="text-sm text-zinc-500 mb-3">Belum ada artikel ditemukan.</p>
                    <a href="{{ route('home') }}" class="text-xs font-semibold underline">Reset Filter</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($articles as $article)
                        <article class="rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex flex-col">
                            <div class="h-48 bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                                @if ($article->image_url)
                                    <img
                                        src="{{ $article->image_url }}"
                                        alt=""
                                        class="w-full h-full object-cover"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                @endif
                                <div class="{{ $article->image_url ? 'hidden' : 'flex' }} w-full h-full items-center justify-center text-xs text-zinc-400">
                                    {{ $article->category->name }}
                                </div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400 mb-1.5">
                                    <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="font-medium text-zinc-700 dark:text-zinc-300 hover:underline">
                                        {{ $article->category->name }}
                                    </a>
                                    <span>&bull;</span>
                                    <span>{{ $article->created_at->translatedFormat('d M Y') }}</span>
                                </div>

                                <h4 class="text-base font-bold text-zinc-900 dark:text-white mb-2 leading-snug hover:underline">
                                    <a href="{{ route('articles.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h4>

                                <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed line-clamp-3">
                                    {{ Str::limit($article->excerpt ?: strip_tags($article->content), 115) }}
                                </p>

                                <div class="mt-auto pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs text-zinc-500">
                                    <span>{{ $article->author }}</span>
                                    <span>{{ number_format($article->views) }}x dibaca</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Kategori -->
            <div class="rounded-xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between mb-3">
                    <h6 class="text-sm font-bold text-zinc-900 dark:text-white">Kategori</h6>
                    @auth
                        @if (auth()->user()->canManageCategories())
                            <a href="{{ route('categories.index') }}" class="text-xs text-zinc-500 hover:underline">Kelola</a>
                        @endif
                    @endauth
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800 text-xs">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="py-2.5 flex items-center justify-between hover:underline {{ $activeCategory && $activeCategory->id === $cat->id ? 'font-bold text-zinc-900 dark:text-white' : 'text-zinc-600 dark:text-zinc-400' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-zinc-400">{{ $cat->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Terpopuler -->
            <div class="rounded-xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <h6 class="text-sm font-bold text-zinc-900 dark:text-white mb-3">Berita Terpopuler</h6>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($popularArticles as $index => $pop)
                        <div class="py-2.5 first:pt-0 last:pb-0 flex items-start gap-3">
                            <span class="text-xs font-bold text-zinc-400 w-4 pt-0.5">{{ $index + 1 }}.</span>
                            <div>
                                <a href="{{ route('articles.show', $pop->slug) }}" class="text-xs font-semibold text-zinc-900 dark:text-white hover:underline leading-snug block">
                                    {{ $pop->title }}
                                </a>
                                <div class="text-[11px] text-zinc-400 mt-0.5">
                                    {{ $pop->category->name }} &bull; {{ number_format($pop->views) }}x dibaca
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Menu Role -->
            @auth
                @if (auth()->user()->canManageArticles())
                    <div class="rounded-xl p-5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 space-y-2">
                        <div class="text-sm font-bold text-zinc-900 dark:text-white mb-2">
                            Menu {{ auth()->user()->roleLabel() }}
                        </div>
                        <a href="{{ route('articles.create') }}" class="block w-full text-center py-2 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                            Tulis Artikel Baru
                        </a>
                        <a href="{{ route('articles.index') }}" class="block w-full text-center py-2 rounded-lg text-xs font-medium border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300">
                            Kelola Artikel
                        </a>
                        @if (auth()->user()->canManageCategories())
                            <a href="{{ route('categories.index') }}" class="block w-full text-center py-2 rounded-lg text-xs font-medium border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300">
                                Kelola Kategori
                            </a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}" class="block w-full text-center py-2 rounded-lg text-xs font-medium border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300">
                                Daftar Penulis / User
                            </a>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>
@endsection
