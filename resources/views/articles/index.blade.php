@extends('layouts.app')

@section('title', 'Kelola Artikel (CRUD) - PortalBerita')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-journal-text text-primary me-2"></i>Manajemen CRUD Artikel
            </h1>
            <p class="text-muted mb-0 small">
                Kelola daftar berita, tambah artikel baru, ubah konten, atau hapus artikel beserta relasi kategorinya.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-tags me-1"></i>Kelola Kategori
            </a>
            <a href="{{ route('articles.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>Tambah Artikel
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-3 mb-4">
        <form action="{{ route('articles.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari judul artikel atau penulis...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $categoryId === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if ($search !== '' || $categoryId)
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">#</th>
                        <th>Artikel Berita</th>
                        <th>Kategori (Relasi)</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Dibaca</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-4">Aksi CRUD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td class="ps-4 text-muted fw-semibold">{{ $article->id }}</td>
                            <td style="max-width: 340px;">
                                <div class="d-flex align-items-center gap-3">
                                    @if ($article->image_url)
                                        <img src="{{ $article->image_url }}" class="rounded-3 flex-shrink-0" style="width: 64px; height: 48px; object-fit: cover;" alt="">
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center flex-shrink-0 text-secondary" style="width: 64px; height: 48px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('articles.show', $article->slug) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 250px;">
                                            {{ $article->title }}
                                        </a>
                                        <span class="text-muted small d-block text-truncate" style="max-width: 250px;">
                                            {{ $article->excerpt }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $article->category->color }} rounded-pill px-3 py-2">
                                    {{ $article->category->name }}
                                </span>
                            </td>
                            <td class="small">{{ $article->author }}</td>
                            <td>
                                @if ($article->is_published)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="bi bi-check-circle me-1"></i>Tayang
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                <i class="bi bi-eye me-1"></i>{{ number_format($article->views) }}
                            </td>
                            <td class="small text-muted">
                                {{ $article->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-primary" title="Edit Artikel">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Artikel">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                Belum ada artikel berita. Silakan tambahkan artikel baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection

