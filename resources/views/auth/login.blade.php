@extends('layouts.app')

@section('title', 'Login - Narasi Digital')

@section('content')
    <div class="max-w-md mx-auto py-8">
        <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
            <div class="mb-6">
                <h1 class="text-xl font-bold text-zinc-900 dark:text-white">Login</h1>
                <p class="text-xs text-zinc-500 mt-1">
                    Masuk ke akun Anda atau lanjut membaca tanpa login.
                </p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        autofocus
                        class="w-full px-3.5 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold mb-1">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password..."
                            required
                            class="w-full pl-3.5 pr-10 py-2 rounded-lg text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 placeholder-zinc-400 focus:outline-none"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer"
                            aria-label="Lihat Password"
                        >
                            <!-- Eye Icon -->
                            <svg class="eye-open w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <!-- Eye Slash Icon -->
                            <svg class="eye-closed hidden w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 text-zinc-600 dark:text-zinc-400 cursor-pointer">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('register') }}" class="font-medium text-zinc-900 dark:text-white hover:underline">
                        Daftar sebagai User
                    </a>
                </div>

                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-lg text-xs font-semibold bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 cursor-pointer">
                        Masuk
                    </button>

                    <a href="{{ route('home') }}" class="block w-full text-center py-2.5 rounded-lg text-xs font-medium border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Lanjut Tanpa Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
