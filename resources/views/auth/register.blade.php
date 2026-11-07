@extends('layouts.app')

@section('title', 'Daftar Akun User - PortalBerita')

@section('content')
    <div class="max-w-lg mx-auto py-4">
        <div class="rounded-3xl p-6 sm:p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-lg">
            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 mb-2">
                    <i class="bi bi-person-plus"></i> Registrasi User Penglihat
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Daftar Sebagai User</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Halaman ini khusus untuk mendaftar sebagai <strong>User Penglihat (Pembaca Berita)</strong>.
                    <br>
                    <span class="text-slate-400">(Pendaftaran akun Penulis hanya dilakukan oleh Admin di halaman Admin).</span>
                </p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
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
                        placeholder="Masukkan nama lengkap Anda..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                        placeholder="nama@email.com"
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 6 karakter"
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="space-y-2.5 pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-full text-sm font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-check2-circle mr-1"></i>Daftar Sebagai User
                    </button>
                    <a href="{{ route('home') }}" class="block w-full text-center py-2.5 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                        <i class="bi bi-eye mr-1"></i>Lanjut Tanpa Login (Sebagai Penglihat)
                    </a>
                </div>

                <div class="text-center pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 dark:text-sky-400 hover:underline">Login di sini</a>
                </div>
            </form>
        </div>
    </div>
@endsection
