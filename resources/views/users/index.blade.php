@extends('layouts.app')

@section('title', 'Kelola User - Narasi Digital')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Manajemen Akun & Pendaftaran Penulis/User</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Khusus Admin untuk mendaftarkan Penulis atau User serta mengatur role pengguna.</p>
        </div>

        <a href="{{ route('articles.index') }}" class="text-xs text-zinc-500 hover:underline">
            &larr; Ke Daftar Artikel
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4">
            <div class="rounded-2xl p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <h5 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">Daftarkan Penulis / User</h5>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold mb-1">Nama Lengkap *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap..."
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold mb-1">Email *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-semibold mb-1">Role *</label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 focus:outline-none"
                        >
                            <option value="penulis" @selected(old('role', 'penulis') === 'penulis')>Penulis</option>
                            <option value="penglihat" @selected(old('role') === 'penglihat')>User Penglihat</option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold mb-1">Password *</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Min. 6 karakter"
                                required
                                class="w-full pl-3.5 pr-10 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                            >
                            <button
                                type="button"
                                onclick="togglePassword('password', this)"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer"
                                aria-label="Lihat password"
                            >
                                <svg class="eye-open w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg class="eye-closed w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                        Daftarkan Akun
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
                                <th class="py-3 px-3">Nama & Email</th>
                                <th class="py-3 px-3">Role</th>
                                <th class="py-3 px-3">Terdaftar</th>
                                <th class="py-3 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse ($users as $u)
                                <tr>
                                    <td class="py-3.5 pl-5 pr-3 text-xs font-mono text-zinc-400" title="{{ $u->id }}">
                                        {{ substr($u->id, 0, 8) }}&hellip;
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <div class="font-semibold text-zinc-900 dark:text-white">{{ $u->name }}</div>
                                        <div class="text-xs text-zinc-400">{{ $u->email }}</div>
                                    </td>
                                    <td class="py-3.5 px-3 text-xs">
                                        <span class="px-2.5 py-1 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium">
                                            {{ $u->roleLabel() }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 text-xs text-zinc-500">
                                        {{ $u->created_at?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="py-3.5 pl-3 pr-5 text-right text-xs">
                                        <div class="inline-flex items-center gap-2">
                                            <button
                                                type="button"
                                                onclick="openModal('editUserModal{{ $u->id }}')"
                                                class="text-zinc-700 dark:text-zinc-300 hover:underline cursor-pointer"
                                            >
                                                Edit
                                            </button>

                                            @if (auth()->id() !== $u->id)
                                                <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline cursor-pointer">Hapus</button>
                                                </form>
                                            @endif
                                        </div>

                                        <div id="editUserModal{{ $u->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 text-left">
                                            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6">
                                                <div class="flex items-center justify-between mb-4">
                                                    <h5 class="text-sm font-bold">Edit Akun</h5>
                                                    <button type="button" onclick="closeModal('editUserModal{{ $u->id }}')" class="text-xs text-zinc-400 cursor-pointer">Tutup</button>
                                                </div>
                                                <form action="{{ route('users.update', $u) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Nama Lengkap</label>
                                                        <input type="text" name="name" value="{{ $u->name }}" required class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ $u->email }}" required class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Role</label>
                                                        <select name="role" required class="w-full px-3 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700">
                                                            <option value="penulis" @selected($u->role === 'penulis')>Penulis</option>
                                                            <option value="penglihat" @selected($u->role === 'penglihat')>User Penglihat</option>
                                                            <option value="admin" @selected($u->role === 'admin')>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold mb-1">Password Baru (Opsional)</label>
                                                        <div class="relative">
                                                            <input type="password" id="passwordEdit{{ $u->id }}" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full pl-3 pr-10 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none">
                                                            <button
                                                                type="button"
                                                                onclick="togglePassword('passwordEdit{{ $u->id }}', this)"
                                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer"
                                                                aria-label="Lihat password"
                                                            >
                                                                <svg class="eye-open w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                                </svg>
                                                                <svg class="eye-closed w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end gap-2 pt-2">
                                                        <button type="button" onclick="closeModal('editUserModal{{ $u->id }}')" class="px-3.5 py-1.5 rounded-lg text-xs border border-zinc-300 dark:border-zinc-700 cursor-pointer">Batal</button>
                                                        <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-zinc-400 text-xs">Belum ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
