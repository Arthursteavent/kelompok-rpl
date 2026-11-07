@extends('layouts.app')

@section('title', 'Edit Artikel - PortalBerita')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-pencil-square text-blue-600 dark:text-sky-400"></i>
                    <span>Edit Artikel Berita</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Perbarui informasi artikel atau ubah relasi kategorinya.
                </p>
            </div>
            <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                <i class="bi bi-arrow-left mr-1"></i>Kembali
            </a>
        </div>

        <div class="rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Judul Artikel -->
                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Judul Artikel <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $article->title) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Kategori & Penulis -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Kategori Berita <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $article->category_id) == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Nama Penulis / Redaksi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="{{ old('author', $article->author) }}"
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <!-- Gambar via URL atau Upload File -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="image_url" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            URL Gambar Sampul
                        </label>
                        <input
                            type="url"
                            id="image_url"
                            name="image_url"
                            value="{{ old('image_url', str_starts_with((string) $article->image_url, 'http') ? $article->image_url : '') }}"
                            placeholder="https://images.unsplash.com/..."
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="image_file" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                            Ganti dengan Upload Gambar Baru
                        </label>
                        <input
                            type="file"
                            id="image_file"
                            name="image_file"
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100"
                        >
                    </div>
                </div>

                <!-- Ringkasan (Excerpt) -->
                <div>
                    <label for="excerpt" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Ringkasan Singkat
                    </label>
                    <textarea
                        id="excerpt"
                        name="excerpt"
                        rows="2"
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('excerpt', $article->excerpt) }}</textarea>
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
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Status Publikasi -->
                <div class="flex items-center gap-2.5">
                    <input
                        type="checkbox"
                        id="is_published"
                        name="is_published"
                        value="1"
                        @checked(old('is_published', $article->is_published))
                        class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"
                    >
                    <label for="is_published" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                        Tayangkan artikel di halaman utama
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                    <a href="{{ route('articles.index') }}" class="px-5 py-2.5 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-check2-circle mr-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
