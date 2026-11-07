<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Narasi Digital')</title>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('portal_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>
<body class="min-h-screen flex flex-col font-sans bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100 antialiased">
    @php
        $navCategories = \App\Models\Category::orderBy('name')->get();
        $currentKategoriSlug = request()->query('kategori');
        $currentSearchQuery = request()->query('q', '');
    @endphp

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-800 transition-colors duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Brand Text Only -->
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    Narasi Digital
                </a>

                <!-- Search Bar -->
                <div class="w-full md:w-auto md:flex-1 md:max-w-md order-3 md:order-2">
                    <form action="{{ route('home') }}" method="GET" class="relative flex items-center">
                        @if ($currentKategoriSlug)
                            <input type="hidden" name="kategori" value="{{ $currentKategoriSlug }}">
                        @endif
                        <input
                            type="text"
                            name="q"
                            value="{{ $currentSearchQuery }}"
                            placeholder="Cari berita..."
                            class="w-full px-3.5 py-1.5 rounded-lg text-sm bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 border border-zinc-300 dark:border-zinc-700 focus:outline-none focus:border-zinc-500"
                        >
                        @if ($currentSearchQuery !== '' || $currentKategoriSlug)
                            <a href="{{ route('home') }}" class="ml-2 text-xs text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 order-2 md:order-3 text-xs">
                    <button
                        type="button"
                        onclick="togglePortalTheme()"
                        class="px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 border border-zinc-300 dark:border-zinc-700 cursor-pointer"
                    >
                        <span class="dark:hidden">Mode Gelap</span>
                        <span class="hidden dark:inline">Mode Terang</span>
                    </button>

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <a href="{{ route('articles.create') }}" class="hidden sm:inline-block px-3 py-1.5 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 font-semibold">
                                Tulis Artikel
                            </a>
                        @endif

                        <div class="relative" id="userDropdownContainer">
                            <button
                                type="button"
                                onclick="toggleUserDropdown(event)"
                                class="px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border border-zinc-300 dark:border-zinc-700 font-medium cursor-pointer"
                            >
                                {{ auth()->user()->name }} ({{ auth()->user()->roleLabel() }})
                            </button>

                            <div
                                id="userDropdownMenu"
                                class="hidden absolute right-0 mt-2 w-52 rounded-xl bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 shadow-lg border border-zinc-200 dark:border-zinc-800 py-1.5 z-50"
                            >
                                <div class="px-3.5 py-2 border-b border-zinc-100 dark:border-zinc-800 text-xs">
                                    <div class="text-zinc-400">{{ auth()->user()->roleLabel() }}</div>
                                    <div class="font-medium truncate">{{ auth()->user()->email }}</div>
                                </div>

                                @if (auth()->user()->canManageArticles())
                                    <a href="{{ route('articles.index') }}" class="block px-3.5 py-2 text-xs hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                        Kelola Artikel
                                    </a>
                                @endif

                                @if (auth()->user()->canManageCategories())
                                    <a href="{{ route('categories.index') }}" class="block px-3.5 py-2 text-xs hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                        Kelola Kategori
                                    </a>
                                @endif

                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('users.index') }}" class="block px-3.5 py-2 text-xs hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                        Kelola User
                                    </a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST" class="border-t border-zinc-100 dark:border-zinc-800 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border border-zinc-300 dark:border-zinc-700">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 font-semibold">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Category Navigation Bar -->
        <nav class="bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800/80 transition-colors duration-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex items-center gap-6 overflow-x-auto text-xs font-medium whitespace-nowrap py-2.5">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') && !$currentKategoriSlug ? 'text-zinc-900 dark:text-white font-semibold underline underline-offset-4' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                        Semua
                    </a>

                    @foreach ($navCategories as $cat)
                        <a href="{{ route('home', array_filter(['kategori' => $cat->slug, 'q' => $currentSearchQuery])) }}"
                           class="{{ $currentKategoriSlug === $cat->slug ? 'text-zinc-900 dark:text-white font-semibold underline underline-offset-4' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <a href="{{ route('articles.index') }}"
                               class="ml-auto {{ request()->routeIs('articles.*') && !request()->routeIs('articles.show') ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                                CRUD Artikel
                            </a>
                        @endif
                        @if (auth()->user()->canManageCategories())
                            <a href="{{ route('categories.index') }}"
                               class="{{ request()->routeIs('categories.*') ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                                CRUD Kategori
                            </a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}"
                               class="{{ request()->routeIs('users.*') ? 'text-zinc-900 dark:text-white font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                                Daftar Penulis/User
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            @if (session('success'))
                <div class="mb-6 px-4 py-3 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200 flex items-center justify-between text-sm">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-zinc-400 hover:text-zinc-600 text-xs">Tutup</button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 flex items-center justify-between text-sm">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-xs">Tutup</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 text-sm">
                    <div class="font-semibold mb-1">Periksa kembali input Anda:</div>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-zinc-900 text-zinc-400 border-t border-zinc-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-zinc-800">
                <div class="md:col-span-5 space-y-2">
                    <div class="text-lg font-bold text-white">Narasi Digital</div>
                    <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                        Portal berita dan informasi harian. Menyajikan artikel teknologi, pendidikan, ekonomi, olahraga, dan sains.
                    </p>
                </div>

                <div class="md:col-span-4">
                    <div class="text-xs font-semibold uppercase tracking-wider text-zinc-200 mb-3">Kategori</div>
                    <ul class="grid grid-cols-2 gap-1.5 text-xs">
                        @foreach ($navCategories as $cat)
                            <li>
                                <a href="{{ route('home', ['kategori' => $cat->slug]) }}" class="hover:text-white">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="md:col-span-3">
                    <div class="text-xs font-semibold uppercase tracking-wider text-zinc-200 mb-3">Menu</div>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white">Daftar User</a></li>
                        @else
                            @if (auth()->user()->canManageArticles())
                                <li><a href="{{ route('articles.index') }}" class="hover:text-white">Kelola Artikel</a></li>
                            @endif
                            @if (auth()->user()->canManageCategories())
                                <li><a href="{{ route('categories.index') }}" class="hover:text-white">Kelola Kategori</a></li>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <li><a href="{{ route('users.index') }}" class="hover:text-white">Kelola User</a></li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>

            <div class="pt-6 flex flex-wrap items-center justify-between gap-4 text-xs text-zinc-500">
                <div>&copy; {{ date('Y') }} Narasi Digital. Proyek Kelompok RPL.</div>
                <div class="flex gap-4">
                    <a href="{{ route('home') }}" class="hover:text-zinc-300">Tentang</a>
                    <a href="{{ route('home') }}" class="hover:text-zinc-300">Redaksi</a>
                    <a href="{{ route('home') }}" class="hover:text-zinc-300">Kontak</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function togglePortalTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('portal_theme', isDark ? 'dark' : 'light');
        }

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('userDropdownMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const container = document.getElementById('userDropdownContainer');
            const menu = document.getElementById('userDropdownMenu');
            if (container && menu && !container.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('hidden');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (eyeOpen && eyeClosed) {
                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);
            }
        }
    </script>
</body>
</html>
