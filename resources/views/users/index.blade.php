@extends('layouts.app')

@section('title', 'Kelola & Daftarkan User/Penulis (Admin) - PortalBerita')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-people-fill text-blue-600 dark:text-sky-400"></i>
                <span>Manajemen Akun & Pendaftaran Penulis/User (Khusus Admin)</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Hanya <strong>Admin</strong> yang dapat mendaftarkan akun sebagai <strong>Penulis</strong> atau <strong>User Penglihat</strong> serta mengatur role pengguna.
            </p>
        </div>

        <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
            <i class="bi bi-journal-text mr-1"></i>Ke Tabel Artikel
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Form Daftarkan Penulis / User Baru -->
        <div class="lg:col-span-4">
            <div class="rounded-3xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h5 class="text-base font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="bi bi-person-plus-fill text-blue-600 dark:text-sky-400"></i>
                    <span>Daftarkan Penulis / User</span>
                </h5>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap..."
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@portalberita.com"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Daftar Sebagai Role <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="penulis" @selected(old('role', 'penulis') === 'penulis')>
                                Penulis (Bisa Tulis & Kelola Artikel)
                            </option>
                            <option value="penglihat" @selected(old('role') === 'penglihat')>
                                User Penglihat (Pembaca Berita)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Password Akun <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 6 karakter"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-plus-lg mr-1"></i>Daftarkan Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Semua Akun Pengguna -->
        <div class="lg:col-span-8">
            <div class="rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-100/80 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-3.5 pl-6 pr-3 w-28">UUID</th>
                                <th class="py-3.5 px-3">Nama & Email</th>
                                <th class="py-3.5 px-3">Role</th>
                                <th class="py-3.5 px-3">Terdaftar</th>
                                <th class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($users as $u)
                                @php
                                    $uBadge = match($u->role) {
                                        'admin' => 'bg-rose-600 text-white',
                                        'penulis' => 'bg-blue-600 text-white',
                                        default => 'bg-emerald-600 text-white',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 pl-6 pr-3 text-xs font-mono text-slate-400" title="{{ $u->id }}">
                                        {{ substr($u->id, 0, 8) }}&hellip;
                                    </td>
                                    <td class="py-4 px-3">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $u->email }}</div>
                                    </td>
                                    <td class="py-4 px-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $uBadge }}">
                                            {{ $u->roleLabel() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-3 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $u->created_at?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="py-4 pl-3 pr-6 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                type="button"
                                                onclick="openModal('editUserModal{{ $u->id }}')"
                                                class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900/60 text-blue-600 dark:text-sky-400 transition cursor-pointer"
                                                title="Edit User / Ubah Role"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            @if (auth()->id() !== $u->id)
                                                <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 transition cursor-pointer" title="Hapus Akun">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Modal Edit User & Role -->
                                        <div id="editUserModal{{ $u->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 text-left">
                                            <div class="w-full max-w-md rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h5 class="text-base font-extrabold text-slate-900 dark:text-white">Edit Akun: {{ $u->name }}</h5>
                                                    <button type="button" onclick="closeModal('editUserModal{{ $u->id }}')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users.update', $u) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Nama Lengkap</label>
                                                        <input type="text" name="name" value="{{ $u->name }}" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ $u->email }}" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Role Pengguna</label>
                                                        <select name="role" required class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                            <option value="penulis" @selected($u->role === 'penulis')>Penulis</option>
                                                            <option value="penglihat" @selected($u->role === 'penglihat')>User Penglihat</option>
                                                            <option value="admin" @selected($u->role === 'admin')>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                                                        <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100">
                                                    </div>
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" onclick="closeModal('editUserModal{{ $u->id }}')" class="px-4 py-2 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-pointer">Batal</button>
                                                        <button type="submit" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white cursor-pointer">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400">Belum ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
