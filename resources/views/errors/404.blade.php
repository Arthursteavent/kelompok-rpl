@extends('layouts.app')

@section('title', '404 Halaman Tidak Ditemukan - Narasi Digital')

@section('content')
    <div class="max-w-lg mx-auto py-12 sm:py-16">
        <div class="rounded-2xl p-8 sm:p-10 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-center shadow-sm transition-colors duration-200">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white font-bold text-lg mb-5 border border-zinc-200 dark:border-zinc-700">
                404
            </div>

            <p class="text-xs font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-1">
                404 Not Found
            </p>

            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white mb-3">
                Halaman Tidak Ditemukan
            </h1>

            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-8">
                Maaf, halaman atau alamat URL yang Anda tuju tidak tersedia, mungkin telah dihapus, atau alamatnya salah diketik.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button
                    type="button"
                    onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('home') }}'; }"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border border-zinc-300 dark:border-zinc-700 transition-colors cursor-pointer"
                >
                    <span>&larr;</span>
                    <span>Kembali</span>
                </button>

                <a
                    href="{{ route('home') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-zinc-900 text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors"
                >
                    Ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
