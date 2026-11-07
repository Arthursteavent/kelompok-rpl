@extends('layouts.app')

@section('title', 'Edit Artikel - Narasi Digital')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Edit Artikel</h1>
            <a href="{{ route('articles.index') }}" class="text-xs text-zinc-500 hover:underline">&larr; Kembali</a>
        </div>

        <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-semibold mb-1">Judul Artikel *</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $article->title) }}"
                        placeholder="Masukkan judul artikel berita..."
                        required
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-xs font-semibold mb-1">Kategori *</label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                        >
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $article->category_id) == $cat->id)>
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
                            value="{{ old('author', $article->author) }}"
                            placeholder="Nama penulis"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="image_url" class="block text-xs font-semibold mb-1">URL Gambar</label>
                        <input
                            type="url"
                            id="image_url"
                            name="image_url"
                            value="{{ old('image_url', str_starts_with((string) $article->image_url, 'http') ? $article->image_url : '') }}"
                            placeholder="https://..."
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="image_file" class="block text-xs font-semibold mb-1">Ganti Upload Gambar</label>
                        <input
                            type="file"
                            id="image_file"
                            name="image_file"
                            accept="image/*"
                            class="w-full px-3 py-1.5 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700"
                        >
                    </div>
                </div>

                @if ($article->image_url)
                    <div>
                        <span class="block text-xs text-zinc-500 mb-1.5">Gambar Saat Ini:</span>
                        <img src="{{ $article->image_url }}" alt="" class="h-32 rounded-lg object-cover border border-zinc-200 dark:border-zinc-800">
                    </div>
                @endif

                <div>
                    <label for="excerpt" class="block text-xs font-semibold mb-1">Ringkasan Singkat</label>
                    <textarea
                        id="excerpt"
                        name="excerpt"
                        rows="2"
                        placeholder="Ringkasan singkat isi berita..."
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >{{ old('excerpt', $article->excerpt) }}</textarea>
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
                    >{{ old('content', $article->content) }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" @checked(old('is_published', $article->is_published))>
                    <label for="is_published" class="text-xs">Tayangkan artikel</label>
                </div>

                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-2">
                    <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700">Batal</a>
                    <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
