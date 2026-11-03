@extends('layouts.app')

@section('title', 'Tulis Artikel Baru - PortalBerita')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">
                        <i class="bi bi-plus-circle text-primary me-2"></i>Tulis Artikel Berita Baru
                    </h1>
                    <p class="text-muted small mb-0">
                        Data artikel akan disimpan langsung ke tabel <code>articles</code> di database dan berelasi dengan tabel <code>categories</code>.
                    </p>
                </div>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            @if ($categories->isEmpty())
                <div class="alert alert-warning rounded-4 border-0 shadow-sm d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Tabel Kategori masih kosong.</strong> Silakan buat minimal 1 kategori terlebih dahulu agar artikel memiliki relasi tabel.
                    </div>
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#quickAddCategoryModal">
                        <i class="bi bi-plus-lg me-1"></i>Buat Kategori
                    </button>
                </div>
            @endif

            <div class="card p-4 p-md-5">
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Judul Artikel -->
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                class="form-control form-control-lg @error('title') is-invalid @enderror"
                                placeholder="Masukkan judul berita..."
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori (Relasi Foreign Key) -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="category_id" class="form-label fw-semibold">Kategori Berita <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" data-bs-toggle="modal" data-bs-target="#quickAddCategoryModal">
                                    + Tambah Kategori Baru
                                </button>
                            </div>
                            <select
                                id="category_id"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Pilih Kategori dari Database --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Penulis -->
                        <div class="col-md-6">
                            <label for="author" class="form-label fw-semibold">Nama Penulis <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                value="{{ old('author') }}"
                                class="form-control @error('author') is-invalid @enderror"
                                placeholder="Masukkan nama penulis..."
                                required
                            >
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar via URL atau Upload File -->
                        <div class="col-md-6">
                            <label for="image_url" class="form-label fw-semibold">URL Gambar Sampul (Opsional)</label>
                            <input
                                type="url"
                                id="image_url"
                                name="image_url"
                                value="{{ old('image_url') }}"
                                class="form-control @error('image_url') is-invalid @enderror"
                                placeholder="https://..."
                            >
                            <div class="form-text">Masukkan link gambar atau unggah file di sebelah kanan.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="image_file" class="form-label fw-semibold">Atau Upload File Gambar (Opsional)</label>
                            <input
                                type="file"
                                id="image_file"
                                name="image_file"
                                accept="image/*"
                                class="form-control @error('image_file') is-invalid @enderror"
                            >
                            <div class="form-text">Format JPG, PNG, WebP (Maks. 2MB).</div>
                        </div>

                        <!-- Ringkasan (Excerpt) -->
                        <div class="col-12">
                            <label for="excerpt" class="form-label fw-semibold">Ringkasan Singkat (Opsional)</label>
                            <textarea
                                id="excerpt"
                                name="excerpt"
                                rows="2"
                                class="form-control @error('excerpt') is-invalid @enderror"
                                placeholder="Ringkasan singkat (otomatis diambil dari isi berita jika dikosongkan)"
                            >{{ old('excerpt') }}</textarea>
                        </div>

                        <!-- Isi Berita Lengkap -->
                        <div class="col-12">
                            <label for="content" class="form-label fw-semibold">Isi Berita Lengkap <span class="text-danger">*</span></label>
                            <textarea
                                id="content"
                                name="content"
                                rows="9"
                                class="form-control @error('content') is-invalid @enderror"
                                placeholder="Tuliskan isi berita atau artikel secara lengkap di sini..."
                                required
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                    @checked(old('is_published', true))
                                >
                                <label class="form-check-label fw-medium" for="is_published">
                                    Langsung tayangkan artikel di halaman utama
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('articles.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">
                                <i class="bi bi-send-check me-1"></i>Simpan ke Database
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori Cepat -->
    <div class="modal fade" id="quickAddCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from_article_form" value="1">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Tambah Kategori ke Database</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama kategori..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Warna Badge</label>
                            <select name="color" class="form-select" required>
                                <option value="primary">Biru (Primary)</option>
                                <option value="success">Hijau (Success)</option>
                                <option value="danger">Merah (Danger)</option>
                                <option value="warning">Kuning (Warning)</option>
                                <option value="info">Biru Muda (Info)</option>
                                <option value="dark">Hitam (Dark)</option>
                                <option value="secondary">Abu-abu (Secondary)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="Deskripsi kategori (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
