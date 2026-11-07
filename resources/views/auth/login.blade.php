@extends('layouts.app')

@section('title', 'Login - PortalBerita')

@section('content')
    <div class="max-w-lg mx-auto py-6">
        <div class="rounded-3xl p-6 sm:p-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-lg">
            <div class="text-center mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-sky-400 mb-2">
                    <i class="bi bi-shield-lock"></i> Autentikasi PortalBerita
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Selamat Datang di PortalBerita</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Silakan masuk menggunakan akun Anda, atau lanjut membaca berita tanpa login.
                </p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Alamat Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan alamat email..."
                        required
                        autofocus
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 dark:text-sky-400 hover:underline">
                        Daftar sebagai User &rarr;
                    </a>
                </div>

                <div class="space-y-2.5 pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-full text-sm font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition cursor-pointer">
                        <i class="bi bi-box-arrow-in-right mr-1"></i>Masuk Sekarang
                    </button>

                    <div class="text-center text-xs text-slate-400">atau</div>

                    <a href="{{ route('home') }}" class="block w-full text-center py-2.5 rounded-full text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                        <i class="bi bi-eye mr-1"></i>Lanjut Tanpa Login (Sebagai Penglihat)
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
