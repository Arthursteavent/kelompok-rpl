@extends('layouts.app')

@section('title', $article->title . ' - PortalBerita')

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="text-decoration-none">
                    {{ $article->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 320px;" aria-current="page">
                {{ $article->title }}
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Detail Column -->
        <div class="col-lg-8">
            <article class="card p-4 p-md-5 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <a href="{{ route('home', ['kategori' => $article->category->slug]) }}"
                       class="badge bg-{{ $article->category->color }} px-3 py-2 rounded-pill text-decoration-none">
                        <i class="bi bi-tag-fill me-1"></i>{{ $article->category->name }}
                    </a>

                    <div class="d-flex gap-2">
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-pencil-square me-1"></i>Edit Artikel
                        </a>
                        <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <h1 class="fw-extrabold mb-3 h2">{{ $article->title }}</h1>

                <!-- Article Meta -->
                <div class="d-flex flex-wrap align-items-center text-muted small gap-3 pb-3 mb-4 border-bottom">
                    <span>
                        <i class="bi bi-person-circle text-primary me-1"></i>
                        Ditulis oleh <strong class="text-dark">{{ $article->author }}</strong>
                    </span>
                    <span>
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $article->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </span>
                    <span>
                        <i class="bi bi-eye me-1"></i>
                        {{ number_format($article->views) }} kali dibaca
                    </span>
                </div>

                <!-- Cover Image -->
                @if ($article->image_url)
                    <div class="mb-4 rounded-4 overflow-hidden shadow-sm">
                        <img src="{{ $article->image_url }}" class="w-100" style="max-height: 440px; object-fit: cover;" alt="{{ $article->title }}">
                    </div>
                @endif

                <!-- Excerpt / Lead -->
                @if ($article->excerpt)
                    <div class="p-3 bg-light border-start border-4 border-{{ $article->category->color }} rounded-end mb-4 fw-medium text-secondary">
                        {{ $article->excerpt }}
                    </div>
                @endif

                <!-- Full Content -->
                <div class="article-body">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <div class="mt-5 pt-4 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Berita
                    </a>
                    <span class="text-muted small">
                        Kategori:
                        <a href="{{ route('home', ['kategori' => $article->category->slug]) }}" class="fw-semibold text-decoration-none">
                            {{ $article->category->name }}
                        </a>
                    </span>
                </div>
            </article>

            <!-- Related Articles in the Same Category -->
            @if ($relatedArticles->isNotEmpty())
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-intersect text-primary me-2"></i>Berita Terkait di Kategori {{ $article->category->name }}
                    </h5>
                    <div class="row g-3">
                        @foreach ($relatedArticles as $rel)
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-none">
                                    @if ($rel->image_url)
                                        <img src="{{ $rel->image_url }}" class="card-img-top" style="height: 130px; object-fit: cover;" alt="{{ $rel->title }}">
                                    @endif
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold mb-1 small">
                                            <a href="{{ route('articles.show', $rel->slug) }}" class="text-dark text-decoration-none stretched-link">
                                                {{ Str::limit($rel->title, 60) }}
                                            </a>
                                        </h6>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $rel->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card p-4 mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-diagram-3 text-primary me-2"></i>Informasi Relasi Tabel
                </h6>
                <div class="small text-secondary mb-3">
                    Artikel ini berelasi <strong>Many-to-One</strong> (<code>belongsTo</code>) ke tabel <code>categories</code>:
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">ID Artikel:</span>
                        <code>#{{ $article->id }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Foreign Key (category_id):</span>
                        <code>#{{ $article->category_id }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Nama Kategori:</span>
                        <span class="badge bg-{{ $article->category->color }}">{{ $article->category->name }}</span>
                    </li>
                </ul>
            </div>

            <div class="card p-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-tags-fill text-primary me-2"></i>Jelajahi Kategori Lain
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}"
                           class="btn btn-sm btn-outline-{{ $cat->color }} rounded-pill">
                            {{ $cat->name }} ({{ $cat->articles_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

