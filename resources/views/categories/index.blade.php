@extends('layouts.app')

@section('title', 'Kelola Kategori (Relasi Tabel) - PortalBerita')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-tags text-primary me-2"></i>Manajemen Kategori (Relasi One-to-Many)
            </h1>
            <p class="text-muted mb-0 small">
                Satu kategori dapat memiliki banyak artikel berita (<code>Category::hasMany(Article::class)</code>).
            </p>
        </div>

        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-journal-text me-1"></i>Ke Tabel Artikel
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Tambah Kategori -->
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Kategori Baru
                </h5>

                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control"
                            placeholder="Misal: Sains & Inovasi"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="color" class="form-label fw-semibold">Warna Badge Bootstrap</label>
                        <select id="color" name="color" class="form-select" required>
                            <option value="primary">Biru (Primary)</option>
                            <option value="success">Hijau (Success)</option>
                            <option value="danger">Merah (Danger)</option>
                            <option value="warning">Kuning (Warning)</option>
                            <option value="info">Biru Muda (Info)</option>
                            <option value="dark">Hitam (Dark)</option>
                            <option value="secondary">Abu-abu (Secondary)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="form-control"
                            placeholder="Keterangan topik berita pada kategori ini..."
                        >{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i>Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="col-lg-8">
            <div class="card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">ID</th>
                                <th>Kategori & Slug</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Jumlah Artikel</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $cat)
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold">#{{ $cat->id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $cat->color }} rounded-pill px-3 py-2 mb-1">
                                            {{ $cat->name }}
                                        </span>
                                        <div class="small text-muted"><code>/{{ $cat->slug }}</code></div>
                                    </td>
                                    <td class="small text-secondary" style="max-width: 240px;">
                                        {{ $cat->description ?: '-' }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}" class="badge bg-light text-dark border rounded-pill px-3 py-2 text-decoration-none">
                                            <i class="bi bi-file-earmark-text me-1 text-primary"></i>{{ $cat->articles_count }} artikel
                                        </a>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Tombol Modal Edit -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCatModal{{ $cat->id }}"
                                                title="Edit Kategori"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Semua artikel di dalamnya juga akan ikut terhapus (Cascade Delete).')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Kategori">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit Kategori -->
                                        <div class="modal fade text-start" id="editCatModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <form action="{{ route('categories.update', $cat) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Kategori: {{ $cat->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Nama Kategori</label>
                                                                <input type="text" name="name" value="{{ $cat->name }}" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Warna Badge</label>
                                                                <select name="color" class="form-select" required>
                                                                    @foreach (['primary' => 'Biru (Primary)', 'success' => 'Hijau (Success)', 'danger' => 'Merah (Danger)', 'warning' => 'Kuning (Warning)', 'info' => 'Biru Muda (Info)', 'dark' => 'Hitam (Dark)', 'secondary' => 'Abu-abu (Secondary)'] as $colorKey => $colorLabel)
                                                                        <option value="{{ $colorKey }}" @selected($cat->color === $colorKey)>
                                                                            {{ $colorLabel }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label fw-semibold">Deskripsi Singkat</label>
                                                                <textarea name="description" rows="3" class="form-control">{{ $cat->description }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

