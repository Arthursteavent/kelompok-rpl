@extends('layouts.app')

@section('title', 'Tulis Artikel - Narasi Digital')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Tulis Artikel Baru</h1>
            </div>
            <a href="{{ route('articles.index') }}" class="text-xs text-zinc-500 hover:underline">&larr; Kembali</a>
        </div>

        <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="block text-xs font-semibold mb-1">Judul Artikel *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Masukkan judul artikel berita..."
                        required
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="category_id" class="text-xs font-semibold">Kategori *</label>
                            <button type="button" onclick="openModal('quickAddCategoryModal')" class="text-xs text-zinc-500 hover:underline cursor-pointer">
                                + Kategori Baru
                            </button>
                        </div>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                        >
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author" class="block text-xs font-semibold mb-1">Penulis *</label>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="{{ old('author', auth()->user()?->name) }}"
                            placeholder="Nama penulis"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="image_url" class="block text-xs font-semibold mb-1">URL Gambar (Opsional)</label>
                        <input
                            type="url"
                            id="image_url"
                            name="image_url"
                            value="{{ old('image_url') }}"
                            placeholder="https://..."
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="image_file" class="block text-xs font-semibold mb-1">Upload Gambar (Opsional)</label>
                        <input
                            type="file"
                            id="image_file"
                            name="image_file"
                            accept="image/*"
                            class="w-full px-3 py-1.5 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700"
                        >
                    </div>
                </div>

                <div>
                    <label for="excerpt" class="block text-xs font-semibold mb-1">Ringkasan Singkat (Opsional)</label>
                    <textarea
                        id="excerpt"
                        name="excerpt"
                        rows="2"
                        placeholder="Ringkasan singkat isi berita..."
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >{{ old('excerpt') }}</textarea>
                </div>

                <div>
                    <label for="content" class="block text-xs font-semibold mb-1">Isi Artikel *</label>
                    <textarea
                        id="content"
                        name="content"
                        rows="9"
                        placeholder="Tulis isi artikel berita selengkapnya..."
                        required
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >{{ old('content') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', true))>
                    <label for="is_published" class="text-xs">Tayangkan langsung</label>
                </div>

                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-2">
                    <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700">Batal</a>
                    <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                        Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="quickAddCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-bold">Tambah Kategori</h5>
                <button type="button" onclick="closeModal('quickAddCategoryModal')" class="text-xs text-zinc-400 cursor-pointer">Tutup</button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="from_article_form" value="1">
                <input type="hidden" name="color" value="secondary">
                <div>
                    <label class="block text-xs font-semibold mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('quickAddCategoryModal')" class="px-3.5 py-1.5 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
