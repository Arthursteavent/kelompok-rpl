@extends('layouts.app')

@section('title', ($activeCategory ? 'Kategori ' . $activeCategory->name . ' - ' : '') . 'PortalBerita')

@section('content')
    <!-- Top Banner Slider: Berita-Berita Terbaru (Auto-Scroll Kanan-Kiri, Support Tema Terang & Gelap) -->
    @if ($sliderArticles->isNotEmpty())
        <div class="mb-8 rounded-3xl p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
            <!-- Header Slider -->
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-600 text-white uppercase tracking-wide">
                        <i class="bi bi-broadcast"></i> Berita Terbaru
                    </span>
                    <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 hidden sm:inline">
                        Sorotan informasi terkini pilihan redaksi PortalBerita
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="moveNewsSlide(-1)"
                        class="w-8 h-8 rounded-full inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition cursor-pointer"
                        title="Sebelumnya"
                    >
                        <i class="bi bi-chevron-left text-xs"></i>
                    </button>
                    <button
                        type="button"
                        onclick="moveNewsSlide(1)"
                        class="w-8 h-8 rounded-full inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition cursor-pointer"
                        title="Selanjutnya"
                    >
                        <i class="bi bi-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Slides Container -->
            <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/70">
                @foreach ($sliderArticles as $idx => $slide)
                    <div
                        class="news-slide-item {{ $idx === 0 ? 'grid' : 'hidden' }} grid-cols-1 md:grid-cols-12 items-stretch min-h-[260px] transition-opacity duration-300"
                        data-slide-index="{{ $idx }}"
                    >
                        <!-- Gambar / Placeholder Slide -->
                        <div class="md:col-span-5 relative min-h-[220px] md:min-h-[260px] bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            @if ($slide->image_url)
                                <img
                                    src="{{ $slide->image_url }}"
                                    alt=""
                                    class="w-full h-full object-cover"
                                    onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                >
                            @endif
                            <div class="{{ $slide->image_url ? 'hidden' : 'flex' }} w-full h-full flex-col items-center justify-center text-center p-6 bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200 dark:from-slate-800 dark:via-slate-900 dark:to-slate-950">
                                <div class="w-14 h-14 rounded-2xl bg-blue-600/10 dark:bg-sky-400/10 border border-blue-500/20 dark:border-sky-400/20 flex items-center justify-center text-blue-600 dark:text-sky-400 text-2xl mb-2">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                                <div class="text-sm font-bold text-slate-800 dark:text-white">PortalBerita.com</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Ilustrasi {{ $slide->category->name }}</div>
                            </div>

                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold bg-slate-900/80 text-sky-300 backdrop-blur border border-sky-400/30">
                                #{{ $idx + 1 }} Terkini
                            </span>
                        </div>

                        <!-- Konten Slide -->
                        <div class="md:col-span-7 p-5 sm:p-6 flex flex-col justify-center">
                            <div class="flex flex-wrap items-center gap-2.5 mb-2.5">
                                <a href="{{ route('home', ['kategori' => $slide->category->slug]) }}"
                                   class="px-3 py-1 rounded-full text-xs font-bold {{ $slide->category->tailwindBadgeClass() }}">
                                    {{ $slide->category->name }}
                                </a>
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    <i class="bi bi-clock mr-1"></i>{{ $slide->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white mb-2 leading-snug hover:text-blue-600 dark:hover:text-sky-400 transition">
                                <a href="{{ route('articles.show', $slide->slug) }}">
                                    {{ $slide->title }}
                                </a>
                            </h2>

                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">
                                {{ Str::limit($slide->excerpt ?: strip_tags($slide->content), 150) }}
                            </p>

                            <div class="mt-auto pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <div>
                                    <i class="bi bi-person-circle text-blue-600 dark:text-sky-400 mr-1"></i>{{ $slide->author }}
                                    <span class="mx-1.5">&bull;</span>
                                    <i class="bi bi-eye mr-1"></i>{{ number_format($slide->views) }}x dibaca
                                </div>
                                <a href="{{ route('articles.show', $slide->slug) }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition">
                                    Baca Berita <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dots & Footer Slider -->
            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 px-1">
                <div class="flex items-center gap-1.5">
                    @foreach ($sliderArticles as $idx => $slide)
                        <button
                            type="button"
                            onclick="goToNewsSlide({{ $idx }})"
                            class="news-slide-dot h-2 rounded-full transition-all cursor-pointer {{ $idx === 0 ? 'w-7 bg-blue-600 dark:bg-sky-400' : 'w-2 bg-slate-300 dark:bg-slate-700' }}"
                            aria-label="Slide {{ $idx + 1 }}"
                        ></button>
                    @endforeach
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Geser otomatis setiap beberapa detik &bull;
                    <a href="{{ route('home') }}" class="font-semibold text-blue-600 dark:text-sky-400 hover:underline">Lihat Semua Indeks Berita</a>
                </div>
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
                        if (i === index) {
                            el.classList.remove('hidden');
                            el.classList.add('grid');
                        } else {
                            el.classList.add('hidden');
                            el.classList.remove('grid');
                        }
                    });
                    dots.forEach((dot, i) => {
                        if (i === index) {
                            dot.className = 'news-slide-dot h-2 rounded-full transition-all cursor-pointer w-7 bg-blue-600 dark:bg-sky-400';
                        } else {
                            dot.className = 'news-slide-dot h-2 rounded-full transition-all cursor-pointer w-2 bg-slate-300 dark:bg-slate-700';
                        }
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

    <!-- Status Filter Aktif (Jika sedang memfilter kategori atau mencari) -->
    @if ($activeCategory || $search !== '')
        <div class="mb-6 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 shadow-sm">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 dark:text-white">
                    @if ($activeCategory)
                        Kategori: <span class="text-blue-600 dark:text-sky-400">{{ $activeCategory->name }}</span>
                    @elseif ($search !== '')
                        Hasil Pencarian: "{{ $search }}"
                    @endif
                </h1>
                @if ($activeCategory && $activeCategory->description)
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $activeCategory->description }}</p>
                @endif
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                <i class="bi bi-x-lg"></i> Reset Filter
            </a>
        </div>
    @endif

    <!-- Main Grid: Daftar Artikel (Kiri) & Sidebar (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Article List -->
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-collection-play text-blue-600 dark:text-sky-400"></i>
                    <span>Daftar Artikel</span>
                </h3>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $articles->count() }} artikel
                </span>
            </div>

            @if ($articles->isEmpty())
                <div class="rounded-3xl p-10 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <div class="text-5xl text-slate-400 mb-3">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Belum Ada Artikel Ditemukan</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                        Tidak ada berita yang cocok dengan filter atau pencarian Anda saat ini.
                    </p>
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                            Reset Filter
                        </a>
                        @auth
                            @if (auth()->user()->canManageArticles())
                                <a href="{{ route('articles.create') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition">
                                    <i class="bi bi-plus-lg mr-1"></i>Tulis Artikel Baru
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($articles as $article)
                        <article class="group rounded-2xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col">
                            <div class="relative h-52 bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                @if ($article->image_url)
                                    <img
                                        src="{{ $article->image_url }}"
                                        alt=""
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                @endif
                                <div class="{{ $article->image_url ? 'hidden' : 'flex' }} w-full h-full flex-col items-center justify-center text-center p-4 bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200 dark:from-slate-800 dark:via-slate-900 dark:to-slate-950">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-600/10 dark:bg-sky-400/10 border border-blue-500/20 dark:border-sky-400/20 flex items-center justify-center text-blue-600 dark:text-sky-400 text-xl mb-1.5">
                                        <i class="bi bi-newspaper"></i>
                                    </div>
                                    <div class="text-xs font-bold text-slate-800 dark:text-white">PortalBerita.com</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">Ilustrasi {{ $article->category->name }}</div>
                                </div>

                                <a href="{{ route('home', ['kategori' => $article->category->slug]) }}"
                                   class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $article->category->tailwindBadgeClass() }}">
                                    {{ $article->category->name }}
                                </a>
                            </div>

                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2">
                                    <span><i class="bi bi-person mr-1"></i>{{ $article->author }}</span>
                                    <span>&bull;</span>
                                    <span><i class="bi bi-calendar3 mr-1"></i>{{ $article->created_at->translatedFormat('d M Y') }}</span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2 leading-snug group-hover:text-blue-600 dark:group-hover:text-sky-400 transition">
                                    <a href="{{ route('articles.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h4>

                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 leading-relaxed line-clamp-3">
                                    {{ Str::limit($article->excerpt ?: strip_tags($article->content), 115) }}
                                </p>

                                <div class="mt-auto pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                    <span><i class="bi bi-eye mr-1"></i>{{ number_format($article->views) }}x dibaca</span>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-blue-600 dark:text-sky-400 inline-flex items-center gap-1">
                                        Baca Detail <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Box Relasi Kategori -->
            <div class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-tags-fill text-blue-600 dark:text-sky-400"></i>
                        <span>Kategori Berita</span>
                    </h6>
                    @auth
                        @if (auth()->user()->canManageCategories())
                            <a href="{{ route('categories.index') }}" class="text-xs font-bold text-blue-600 dark:text-sky-400 hover:underline">
                                Kelola <i class="bi bi-gear"></i>
                            </a>
                        @endif
                    @endauth
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="py-2.5 flex items-center justify-between text-sm hover:text-blue-600 dark:hover:text-sky-400 transition {{ $activeCategory && $activeCategory->id === $cat->id ? 'font-bold text-blue-600 dark:text-sky-400' : 'text-slate-700 dark:text-slate-300' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                {{ $cat->articles_count }} artikel
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Berita Terpopuler -->
            <div class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h6 class="text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
                    <i class="bi bi-fire text-rose-600"></i>
                    <span>Berita Terpopuler</span>
                </h6>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($popularArticles as $index => $pop)
                        <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                            <span class="w-7 h-7 rounded-lg bg-slate-900 dark:bg-slate-800 text-white text-xs font-extrabold inline-flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mb-1 {{ $pop->category->tailwindBadgeClass() }}">
                                    {{ $pop->category->name }}
                                </span>
                                <h6 class="text-xs font-bold text-slate-900 dark:text-white leading-snug hover:text-blue-600 dark:hover:text-sky-400 transition">
                                    <a href="{{ route('articles.show', $pop->slug) }}">
                                        {{ $pop->title }}
                                    </a>
                                </h6>
                                <div class="text-[11px] text-slate-400 mt-1">
                                    <i class="bi bi-eye mr-1"></i>{{ number_format($pop->views) }} dibaca
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panel Role & Shortcut -->
            @auth
                @if (auth()->user()->canManageArticles())
                    <div class="rounded-2xl p-5 bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h6 class="text-sm font-extrabold flex items-center gap-2">
                                <i class="bi bi-sliders"></i> Panel {{ auth()->user()->roleLabel() }}
                            </h6>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white text-blue-700">
                                {{ auth()->user()->roleLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-blue-100 mb-4">
                            Tambah, ubah, atau hapus artikel berita secara langsung melalui halaman manajemen.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('articles.create') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold bg-white text-blue-700 hover:bg-blue-50 transition">
                                <i class="bi bi-plus-circle mr-1"></i>Tambah Artikel Baru
                            </a>
                            <a href="{{ route('articles.index') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold border border-white/40 text-white hover:bg-white/10 transition">
                                <i class="bi bi-table mr-1"></i>Tabel CRUD Artikel
                            </a>
                            @if (auth()->user()->canManageCategories())
                                <a href="{{ route('categories.index') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold border border-white/40 text-white hover:bg-white/10 transition">
                                    <i class="bi bi-tags mr-1"></i>Kelola Kategori (Admin)
                                </a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('users.index') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold bg-amber-400 text-slate-950 hover:bg-amber-300 transition">
                                    <i class="bi bi-person-plus-fill mr-1"></i>Daftarkan Penulis / User (Admin)
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl p-5 bg-emerald-600 text-white shadow-sm">
                        <h6 class="text-sm font-extrabold mb-1.5 flex items-center gap-2">
                            <i class="bi bi-person-check"></i> Mode User Penglihat
                        </h6>
                        <p class="text-xs text-emerald-100">
                            Anda login sebagai <strong>{{ auth()->user()->name }}</strong> (Role: <strong>User Penglihat</strong>). Selamat menikmati seluruh sajian berita terkini!
                        </p>
                    </div>
                @endif
            @else
                <div class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h6 class="text-sm font-extrabold text-slate-900 dark:text-white mb-1.5 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600 dark:text-sky-400"></i> Akses Multi-Role
                    </h6>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                        Sebagai <strong>User Penglihat</strong>, Anda bebas membaca seluruh berita <strong>tanpa perlu login</strong>, atau silakan login jika memiliki akun:
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition">
                            <i class="bi bi-box-arrow-in-right mr-1"></i>Login (Admin / Penulis / Penglihat)
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 transition">
                            <i class="bi bi-person-plus mr-1"></i>Daftar Sebagai User
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
@endsection
