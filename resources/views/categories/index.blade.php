@extends('layouts.app')

@section('title', 'Kelola Kategori - Narasi Digital')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Kelola Kategori</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Tambah, ubah, atau hapus kategori berita.</p>
        </div>

        <a href="{{ route('articles.index') }}" class="text-xs text-zinc-500 hover:underline">
            &larr; Ke Daftar Artikel
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4">
            <div class="rounded-2xl p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">Tambah Kategori</h5>

                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="color" value="secondary">

                    <div>
                        <label for="name" class="block text-xs font-semibold mb-1">Nama Kategori *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: Ekonomi Digital"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold mb-1">Deskripsi Singkat</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Deskripsi singkat kategori..."
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="rounded-xl overflow-hidden bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 text-xs border-b border-zinc-200 dark:border-zinc-800">
                                <th class="py-3 pl-5 pr-3 w-28">UUID</th>
                                <th class="py-3 px-3">Kategori</th>
                                <th class="py-3 px-3">Deskripsi</th>
                                <th class="py-3 px-3 text-center">Artikel</th>
                                <th class="py-3 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse ($categories as $cat)
                                <tr>
                                    <td class="py-3.5 pl-5 pr-3 text-xs font-mono text-zinc-400" title="{{ $cat->id }}">
                                        {{ substr($cat->id, 0, 8) }}&hellip;
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $cat->name }}</div>
                                        <div class="text-xs text-zinc-400">/{{ $cat->slug }}</div>
                                    </td>
                                    <td class="py-3.5 px-3 text-xs text-zinc-500 max-w-xs">
                                        {{ $cat->description ?: '-' }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center text-xs text-zinc-600 dark:text-zinc-400">
                                        {{ $cat->articles_count }}
                                    </td>
                                    <td class="py-3.5 pl-3 pr-5 text-right text-xs">
                                        <div class="inline-flex items-center gap-2">
                                            <button
                                                type="button"
                                                onclick="openModal('editCatModal{{ $cat->id }}')"
                                                class="text-zinc-700 dark:text-zinc-300 hover:underline cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline cursor-pointer">Hapus</button>
                                            </form>
                                        </div>

                                        <div id="editCatModal{{ $cat->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 text-left">
                                            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h5 class="text-sm font-bold">Edit Kategori</h5>
                                                    <button type="button" onclick="closeModal('editCatModal{{ $cat->id }}')" class="text-xs text-zinc-400 cursor-pointer">Tutup</button>
                                                </div>
                                                <form action="{{ route('categories.update', $cat) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="color" value="{{ $cat->color ?: 'secondary' }}">
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Nama Kategori</label>
                                                        <input type="text" name="name" value="{{ $cat->name }}" required class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Deskripsi</label>
                                                        <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">{{ $cat->description }}</textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" onclick="closeModal('editCatModal{{ $cat->id }}')" class="px-3.5 py-1.5 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700 cursor-pointer">Batal</button>
                                                        <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-zinc-400 text-xs">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
