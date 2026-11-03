@extends('layouts.app')

@section('title', ($activeCategory ? 'Kategori ' . $activeCategory->name . ' - ' : '') . 'PortalBerita')

@section('content')
    <!-- Header & Search Bar -->
    <div class="row align-items-center justify-content-between mb-4 gy-3">
        <div class="col-lg-7">
            <h1 class="fw-extrabold mb-1 h2">
                @if ($activeCategory)
                    Kategori: <span class="text-{{ $activeCategory->color }}">{{ $activeCategory->name }}</span>
                @elseif ($search !== '')
                    Hasil Pencarian: "{{ $search }}"
                @else
                    Berita & Artikel Terkini
                @endif
            </h1>
            <p class="text-muted mb-0">
                @if ($activeCategory && $activeCategory->description)
                    {{ $activeCategory->description }}
                @else
                    Jelajahi informasi terbaru dari berbagai kategori pilihan redaksi.
                @endif
            </p>
        </div>

        <div class="col-lg-5">
            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                @if ($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory->slug }}">
                @endif
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text bg-white border-0 ps-3 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        class="form-control border-0 shadow-none"
                        placeholder="Cari judul, topik, atau penulis..."
                    >
                    @if ($search !== '' || $activeCategory)
                        <a href="{{ route('home') }}" class="btn btn-light border-0 text-secondary" title="Reset Filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                    <button class="btn btn-primary px-4 fw-semibold" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Pills Filter -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4 pb-2 border-bottom">
        <a href="{{ route('home', $search !== '' ? ['q' => $search] : []) }}"
           class="btn category-pill {{ !$activeCategory ? 'btn-dark shadow-sm' : 'btn-outline-secondary bg-white' }}">
            <i class="bi bi-grid-fill me-1"></i> Semua Kategori
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('home', array_filter(['kategori' => $cat->slug, 'q' => $search])) }}"
               class="btn category-pill {{ $activeCategory && $activeCategory->id === $cat->id ? 'btn-' . $cat->color . ' shadow-sm text-white' : 'btn-outline-' . $cat->color . ' bg-white' }}">
                {{ $cat->name }}
                <span class="badge rounded-pill {{ $activeCategory && $activeCategory->id === $cat->id ? 'bg-white text-dark' : 'bg-' . $cat->color . ' bg-opacity-10 text-' . $cat->color }} ms-1">
                    {{ $cat->articles_count }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- Featured Headline Article -->
    @if ($headline)
        <div class="card headline-card border-0 shadow mb-5">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-7">
                    @if ($headline->image_url)
                        <img src="{{ $headline->image_url }}" class="w-100 headline-img" alt="{{ $headline->title }}">
                    @else
                        <div class="w-100 headline-img bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center">
                            <i class="bi bi-image display-1 text-white-50"></i>
                        </div>
                    @endif
                </div>
                <div class="col-lg-5 d-flex flex-column justify-content-center p-4 p-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill">
                            <i class="bi bi-lightning-charge-fill me-1"></i>Headline Utama
                        </span>
                        <a href="{{ route('home', ['kategori' => $headline->category->slug]) }}"
                           class="badge bg-{{ $headline->category->color }} text-decoration-none px-3 py-2 rounded-pill">
                            {{ $headline->category->name }}
                        </a>
                    </div>

                    <h2 class="fw-bold mb-3 h3">
                        <a href="{{ route('articles.show', $headline->slug) }}" class="text-white text-decoration-none">
                            {{ $headline->title }}
                        </a>
                    </h2>

                    <p class="text-white-50 mb-4">
                        {{ Str::limit($headline->excerpt ?: strip_tags($headline->content), 150) }}
                    </p>

                    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-secondary border-opacity-50 small text-white-50">
                        <div>
                            <i class="bi bi-person-circle me-1 text-info"></i> {{ $headline->author }}
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-clock me-1"></i> {{ $headline->created_at->diffForHumans() }}
                        </div>
                        <a href="{{ route('articles.show', $headline->slug) }}" class="btn btn-sm btn-light rounded-pill px-3 fw-semibold">
                            Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Article List (Left Column) -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-collection-play text-primary me-2"></i>Daftar Artikel
                </h5>
                <span class="text-muted small">
                    Menampilkan {{ $articles->count() }} artikel
                </span>
            </div>

            @if ($articles->isEmpty() && !$headline)
                <div class="card p-5 text-center">
                    <div class="mb-3 text-muted">
                        <i class="bi bi-journal-x display-3"></i>
                    </div>
                    <h4 class="fw-bold">Belum Ada Artikel Ditemukan</h4>
                    <p class="text-muted mb-4">
                        Tidak ada berita yang cocok dengan filter atau pencarian Anda saat ini.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Reset Filter
                        </a>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-plus-lg me-1"></i>Tulis Artikel Baru
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($articles as $article)
                        <div class="col-md-6">
                            <div class="card card-hover h-100 d-flex flex-column">
                                <div class="position-relative">
                                    @if ($article->image_url)
                                        <img src="{{ $article->image_url }}" class="card-img-top article-thumb" alt="{{ $article->title }}">
                                    @else
                                        <div class="article-thumb d-flex align-items-center justify-content-center text-secondary">
                                            <i class="bi bi-newspaper display-4"></i>
                                        </div>
                                    @endif
                                    <a href="{{ route('home', ['kategori' => $article->category->slug]) }}"
                                       class="badge bg-{{ $article->category->color }} position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill text-decoration-none shadow-sm">
                                        {{ $article->category->name }}
                                    </a>
                                </div>

                                <div class="card-body d-flex flex-column p-4">
                                    <div class="d-flex align-items-center text-muted small mb-2 gap-2">
                                        <span><i class="bi bi-person me-1"></i>{{ $article->author }}</span>
                                        <span>&bull;</span>
                                        <span><i class="bi bi-calendar3 me-1"></i>{{ $article->created_at->translatedFormat('d M Y') }}</span>
                                    </div>

                                    <h5 class="card-title fw-bold mb-2">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="text-dark text-decoration-none stretched-link">
                                            {{ $article->title }}
                                        </a>
                                    </h5>

                                    <p class="card-text text-secondary small mb-3">
                                        {{ Str::limit($article->excerpt ?: strip_tags($article->content), 115) }}
                                    </p>

                                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
                                        <span><i class="bi bi-eye me-1"></i>{{ number_format($article->views) }}x dibaca</span>
                                        <span class="text-primary fw-semibold">
                                            Baca Detail <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar (Right Column) -->
        <div class="col-lg-4">
            <!-- Box Relasi Kategori -->
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-tags-fill text-primary me-2"></i>Kategori Berita
                    </h6>
                    <a href="{{ route('categories.index') }}" class="small text-decoration-none fw-semibold">
                        Kelola <i class="bi bi-gear"></i>
                    </a>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $cat->color }} rounded-circle p-1">&nbsp;</span>
                                <span class="{{ $activeCategory && $activeCategory->id === $cat->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                    {{ $cat->name }}
                                </span>
                            </span>
                            <span class="badge bg-light text-secondary border rounded-pill">
                                {{ $cat->articles_count }} artikel
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Berita Terpopuler -->
            <div class="card p-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-fire text-danger me-2"></i>Berita Terpopuler
                </h6>
                <div class="d-flex flex-column gap-3">
                    @foreach ($popularArticles as $index => $pop)
                        <div class="d-flex align-items-start gap-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <span class="badge bg-dark rounded-3 px-2 py-1 fs-6">{{ $index + 1 }}</span>
                            <div>
                                <span class="badge bg-{{ $pop->category->color }} bg-opacity-10 text-{{ $pop->category->color }} mb-1">
                                    {{ $pop->category->name }}
                                </span>
                                <h6 class="fw-bold mb-1 small">
                                    <a href="{{ route('articles.show', $pop->slug) }}" class="text-dark text-decoration-none">
                                        {{ $pop->title }}
                                    </a>
                                </h6>
                                <div class="text-muted" style="font-size: 0.78rem;">
                                    <i class="bi bi-eye me-1"></i>{{ number_format($pop->views) }} dibaca
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panel Shortcut Admin CRUD -->
            <div class="card bg-primary text-white p-4 border-0">
                <h6 class="fw-bold mb-2"><i class="bi bi-sliders me-2"></i>Panel Manajemen Blog</h6>
                <p class="small text-white-50 mb-3">
                    Tambah, ubah, atau hapus artikel berita dan kategori secara langsung melalui halaman CRUD.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('articles.create') }}" class="btn btn-light btn-sm fw-semibold rounded-pill">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Artikel Baru
                    </a>
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-light btn-sm fw-semibold rounded-pill">
                        <i class="bi bi-table me-1"></i>Tabel CRUD Artikel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

