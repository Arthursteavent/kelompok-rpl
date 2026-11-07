@extends('layouts.app')

@section('title', 'Tulis Artikel Baru - PortalBerita')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-plus-circle text-blue-600 dark:text-sky-400"></i>
                    <span>Tulis Artikel Berita Baru</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Publikasikan artikel berita terbaru ke PortalBerita.
                </p>
            </div>
            <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                <i class="bi bi-arrow-left mr-1"></i>Kembali
            </a>
        </div>

        @if ($categories->isEmpty())
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 flex flex-wrap items-center justify-between gap-3">
                <div class="text-xs sm:text-sm">
                    <i class="bi bi-info-circle-fill mr-1"></i>
                    <strong>Tabel Kategori masih kosong.</strong> Silakan buat minimal 1 kategori terlebih dahulu agar artikel memiliki relasi tabel.
                </div>
                <button type="button" onclick="openModal('quickAddCategoryModal')" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 transition cursor-pointer">
                    <i class="bi bi-plus-lg mr-1"></i>Buat Kategori
                </button>
            </div>
        @endif

        <div class="rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Judul Artikel -->
                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Judul Artikel <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Masukkan judul berita..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Kategori & Penulis -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="category_id" class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                Kategori Berita <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" onclick="openModal('quickAddCategoryModal')" class="text-xs font-bold text-blue-600 dark:text-sky-400 hover:underline cursor-pointer">
                                + Tambah Kategori Baru
                            </button>
                        </div>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Kategori dari Database --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Nama Penulis <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="{{ old('author', auth()->user()?->name) }}"
                            placeholder="Masukkan nama penulis..."
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <!-- Gambar via URL atau Upload File -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="image_url" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            URL Gambar Sampul (Opsional)
                        </label>
                        <input
                            type="url"
                            id="image_url"
                            name="image_url"
                            value="{{ old('image_url') }}"
                            placeholder="https://..."
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">Jika dikosongkan akan menampilkan ilustrasi default PortalBerita.</p>
                    </div>

                    <div>
                        <label for="image_file" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Atau Upload File Gambar (Opsional)
                        </label>
                        <input
                            type="file"
                            id="image_file"
                            name="image_file"
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, WebP (Maks. 2MB).</p>
                    </div>
                </div>

                <!-- Ringkasan (Excerpt) -->
                <div>
                    <label for="excerpt" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Ringkasan Singkat (Opsional)
                    </label>
                    <textarea
                        id="excerpt"
                        name="excerpt"
                        rows="2"
                        placeholder="Ringkasan singkat (otomatis diambil dari isi berita jika dikosongkan)"
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('excerpt') }}</textarea>
                </div>

                <!-- Isi Berita Lengkap -->
                <div>
                    <label for="content" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Isi Berita Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="9"
                        placeholder="Tuliskan isi berita atau artikel secara lengkap di sini..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('content') }}</textarea>
                </div>

                <!-- Status Publikasi -->
                <div class="flex items-center gap-2.5">
                    <input
                        type="checkbox"
                        id="is_published"
                        name="is_published"
                        value="1"
                        @checked(old('is_published', true))
                        class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"
                    >
                    <label for="is_published" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        Langsung tayangkan artikel di halaman utama
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('articles.index') }}" class="px-5 py-2.5 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-send-check mr-1"></i>Simpan ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Kategori Cepat -->
    <div id="quickAddCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-extrabold text-slate-900 dark:text-white">Tambah Kategori ke Database</h5>
                <button type="button" onclick="closeModal('quickAddCategoryModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="from_article_form" value="1">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Masukkan nama kategori..." class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Warna Badge</label>
                    <select name="color" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                        <option value="primary">Biru (Primary)</option>
                        <option value="success">Hijau (Success)</option>
                        <option value="danger">Merah (Danger)</option>
                        <option value="warning">Kuning (Warning)</option>
                        <option value="info">Biru Muda (Info)</option>
                        <option value="dark">Hitam (Dark)</option>
                        <option value="secondary">Abu-abu (Secondary)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi kategori (opsional)" class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('quickAddCategoryModal')" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
@endsection
