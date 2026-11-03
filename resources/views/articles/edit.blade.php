@extends('layouts.app')

@section('title', 'Edit Artikel - PortalBerita')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Artikel Berita
                    </h1>
                    <p class="text-muted small mb-0">
                        Perbarui informasi artikel atau ubah relasi kategorinya.
                    </p>
                </div>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4 p-md-5">
                <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Judul Artikel -->
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title', $article->title) }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                required
                            >
                        </div>

                        <!-- Kategori (Relasi Foreign Key) -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">Kategori Berita <span class="text-danger">*</span></label>
                            <select
                                id="category_id"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $article->category_id) == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Penulis -->
                        <div class="col-md-6">
                            <label for="author" class="form-label fw-semibold">Nama Penulis / Redaksi <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                value="{{ old('author', $article->author) }}"
                                class="form-control @error('author') is-invalid @enderror"
                                required
                            >
                        </div>

                        <!-- Gambar via URL atau Upload File -->
                        <div class="col-md-6">
                            <label for="image_url" class="form-label fw-semibold">URL Gambar Sampul</label>
                            <input
                                type="url"
                                id="image_url"
                                name="image_url"
                                value="{{ old('image_url', str_starts_with((string) $article->image_url, 'http') ? $article->image_url : '') }}"
                                class="form-control"
                                placeholder="https://images.unsplash.com/..."
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="image_file" class="form-label fw-semibold">Ganti dengan Upload Gambar Baru</label>
                            <input
                                type="file"
                                id="image_file"
                                name="image_file"
                                accept="image/*"
                                class="form-control"
                            >
                        </div>

                        <!-- Ringkasan (Excerpt) -->
                        <div class="col-12">
                            <label for="excerpt" class="form-label fw-semibold">Ringkasan Singkat</label>
                            <textarea
                                id="excerpt"
                                name="excerpt"
                                rows="2"
                                class="form-control"
                            >{{ old('excerpt', $article->excerpt) }}</textarea>
                        </div>

                        <!-- Isi Berita Lengkap -->
                        <div class="col-12">
                            <label for="content" class="form-label fw-semibold">Isi Berita Lengkap <span class="text-danger">*</span></label>
                            <textarea
                                id="content"
                                name="content"
                                rows="9"
                                class="form-control"
                                required
                            >{{ old('content', $article->content) }}</textarea>
                        </div>

                        <!-- Status Publikasi -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="is_published"
                                    name="is_published"
                                    value="1"
                                    @checked(old('is_published', $article->is_published))
                                >
                                <label class="form-check-label fw-medium" for="is_published">
                                    Tayangkan artikel di halaman utama
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('articles.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">
                                <i class="bi bi-check2-circle me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

