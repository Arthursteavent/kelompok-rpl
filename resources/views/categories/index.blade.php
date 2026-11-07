@extends('layouts.app')

@section('title', 'Kelola Kategori - PortalBerita')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-tags text-blue-600 dark:text-sky-400"></i>
                <span>Manajemen Kategori Berita</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Tambah, ubah, atau hapus kategori berita pada PortalBerita.
            </p>
        </div>

        <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
            <i class="bi bi-journal-text mr-1"></i>Ke Tabel Artikel
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Form Tambah Kategori -->
        <div class="lg:col-span-4">
            <div class="rounded-3xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h5 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="bi bi-plus-circle text-blue-600 dark:text-sky-400"></i>
                    <span>Tambah Kategori Baru</span>
                </h5>

                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Misal: Sains & Inovasi"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="color" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Warna Badge
                        </label>
                        <select
                            id="color"
                            name="color"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
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
                        <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Deskripsi Singkat
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Keterangan topik berita pada kategori ini..."
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-plus-lg mr-1"></i>Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="lg:col-span-8">
            <div class="rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100/80 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-3.5 pl-6 pr-3 w-28">UUID</th>
                                <th class="py-3.5 px-3">Kategori & Slug</th>
                                <th class="py-3.5 px-3">Deskripsi</th>
                                <th class="py-3.5 px-3 text-center">Jumlah Artikel</th>
                                <th class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($categories as $cat)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 pl-6 pr-3 text-xs font-mono text-slate-400" title="{{ $cat->id }}">
                                        {{ substr($cat->id, 0, 8) }}&hellip;
                                    </td>
                                    <td class="py-4 px-3">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-1 {{ $cat->tailwindBadgeClass() }}">
                                            {{ $cat->name }}
                                        </span>
                                        <div class="text-xs text-slate-400"><code>/{{ $cat->slug }}</code></div>
                                    </td>
                                    <td class="py-4 px-3 text-xs text-slate-600 dark:text-slate-400 max-w-xs">
                                        {{ $cat->description ?: '-' }}
                                    </td>
                                    <td class="py-4 px-3 text-center">
                                        <a href="{{ route('home', ['kategori' => $cat->slug]) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition">
                                            <i class="bi bi-file-earmark-text text-blue-600 dark:text-sky-400"></i>
                                            <span>{{ $cat->articles_count }} artikel</span>
                                        </a>
                                    </td>
                                    <td class="py-4 pl-3 pr-6 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                type="button"
                                                onclick="openModal('editCatModal{{ $cat->id }}')"
                                                class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900/60 text-blue-600 dark:text-sky-400 transition cursor-pointer"
                                                title="Edit Kategori"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Semua artikel di dalamnya juga akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 transition cursor-pointer" title="Hapus Kategori">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Edit Kategori -->
                                        <div id="editCatModal{{ $cat->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 text-left">
                                            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h5 class="text-base font-extrabold text-slate-900 dark:text-white">Edit Kategori: {{ $cat->name }}</h5>
                                                    <button type="button" onclick="closeModal('editCatModal{{ $cat->id }}')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <form action="{{ route('categories.update', $cat) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Kategori</label>
                                                        <input type="text" name="name" value="{{ $cat->name }}" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Warna Badge</label>
                                                        <select name="color" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                            @foreach (['primary' => 'Biru (Primary)', 'success' => 'Hijau (Success)', 'danger' => 'Merah (Danger)', 'warning' => 'Kuning (Warning)', 'info' => 'Biru Muda (Info)', 'dark' => 'Hitam (Dark)', 'secondary' => 'Abu-abu (Secondary)'] as $colorKey => $colorLabel)
                                                                <option value="{{ $colorKey }}" @selected($cat->color === $colorKey)>
                                                                    {{ $colorLabel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Deskripsi Singkat</label>
                                                        <textarea name="description" rows="3" class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">{{ $cat->description }}</textarea>
                                                    </div>
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" onclick="closeModal('editCatModal{{ $cat->id }}')" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-pointer">Batal</button>
                                                        <button type="submit" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
