<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PortalBerita - Blog Mini & Portal Informasi')</title>

    <!-- Prevent Flash of Unstyled Theme (Set Dark/Light Class Early) -->
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN with Dark Mode Class Strategy -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        };
    </script>
</head>
<body class="min-h-screen flex flex-col font-sans bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-200">
    @php
        $navCategories = \App\Models\Category::orderBy('name')->get();
        $currentKategoriSlug = request()->query('kategori');
        $currentSearchQuery = request()->query('q', '');
    @endphp

    <!-- Sticky Header Gaya Kompas.com (Supports Light & Dark Theme) -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-b border-slate-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
        <!-- Row 1: Brand + Search Bar + Theme Toggle + Auth/Role -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <!-- Kiri: Brand Logo & Tagline -->
                <a href="{{ route('home') }}" class="inline-flex flex-col group">
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 rounded-xl bg-blue-600 text-white inline-flex items-center justify-center shadow-sm group-hover:bg-blue-500 transition">
                            <i class="bi bi-newspaper text-lg"></i>
                        </span>
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            PORTAL<span class="text-blue-600 dark:text-sky-400">BERITA</span><span class="text-amber-500 text-base font-bold">.com</span>
                        </span>
                    </div>
                    <span class="text-[10px] font-semibold tracking-[0.18em] text-slate-500 dark:text-slate-400 pl-11 -mt-0.5">
                        JERNIH MELIHAT INFORMASI
                    </span>
                </a>

                <!-- Tengah: Search Bar Langsung di Header -->
                <div class="w-full lg:w-auto lg:flex-1 lg:max-w-md order-3 lg:order-2">
                    <form action="{{ route('home') }}" method="GET" class="relative">
                        @if ($currentKategoriSlug)
                            <input type="hidden" name="kategori" value="{{ $currentKategoriSlug }}">
                        @endif
                        <input
                            type="text"
                            name="q"
                            value="{{ $currentSearchQuery }}"
                            placeholder="Cari berita, topik, atau penulis..."
                            class="w-full pl-4 pr-20 py-2 rounded-full text-sm bg-slate-100 dark:bg-slate-800/90 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-400 border border-slate-300 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                        <div class="absolute inset-y-0 right-1.5 flex items-center gap-1">
                            @if ($currentSearchQuery !== '' || $currentKategoriSlug)
                                <a href="{{ route('home') }}" class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" title="Reset Filter">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </a>
                            @endif
                            <button type="submit" class="px-2.5 py-1 rounded-full text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-sky-400 transition" title="Cari">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Kanan: Tombol Ganti Tema (Hitam/Putih) + Aksi Role / Profil -->
                <div class="flex items-center gap-2.5 order-2 lg:order-3">
                    <!-- Tombol Toggle Tema Terang / Gelap -->
                    <button
                        type="button"
                        id="themeToggleBtn"
                        onclick="togglePortalTheme()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition cursor-pointer"
                        title="Ganti Tema Terang / Gelap"
                    >
                        <i class="bi bi-sun-fill text-amber-500 dark:hidden text-sm"></i>
                        <span class="dark:hidden">Terang</span>
                        <i class="bi bi-moon-stars-fill text-sky-400 hidden dark:inline text-sm"></i>
                        <span class="hidden dark:inline">Gelap</span>
                    </button>

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <a href="{{ route('articles.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                                <i class="bi bi-plus-lg"></i> Tulis Artikel
                            </a>
                        @endif

                        <!-- User Profile Dropdown (No White Hover Issue) -->
                        <div class="relative" id="userDropdownContainer">
                            <button
                                type="button"
                                onclick="toggleUserDropdown(event)"
                                class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200/80 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 transition cursor-pointer"
                            >
                                <i class="bi bi-person-circle text-blue-600 dark:text-sky-400 text-lg"></i>
                                <div class="text-left leading-tight hidden md:block">
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</div>
                                    @php
                                        $roleBadgeClasses = match(auth()->user()->role) {
                                            'admin' => 'bg-rose-600 text-white',
                                            'penulis' => 'bg-blue-600 text-white',
                                            default => 'bg-emerald-600 text-white',
                                        };
                                    @endphp
                                    <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-semibold {{ $roleBadgeClasses }}">
                                        {{ auth()->user()->roleLabel() }}
                                    </span>
                                </div>
                                <i class="bi bi-chevron-down text-xs text-slate-500 dark:text-slate-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                id="userDropdownMenu"
                                class="hidden absolute right-0 mt-2 w-60 rounded-2xl bg-white dark:bg-slate-900 shadow-xl border border-slate-200 dark:border-slate-700 py-2 z-50"
                            >
                                <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800 text-xs">
                                    <div class="text-slate-400">Login sebagai ({{ auth()->user()->roleLabel() }}):</div>
                                    <div class="font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5">{{ auth()->user()->email }}</div>
                                </div>

                                @if (auth()->user()->canManageArticles())
                                    <a href="{{ route('articles.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                        <i class="bi bi-journal-text text-blue-600 dark:text-sky-400"></i> CRUD Artikel
                                    </a>
                                @endif

                                @if (auth()->user()->canManageCategories())
                                    <a href="{{ route('categories.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                        <i class="bi bi-tags text-blue-600 dark:text-sky-400"></i> CRUD Kategori
                                    </a>
                                @endif

                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                        <i class="bi bi-person-plus text-blue-600 dark:text-sky-400"></i> Daftar Penulis / User
                                    </a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST" class="mt-1 pt-1 border-t border-slate-100 dark:border-slate-800">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-1 px-3.5 py-2 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                            Gabung User
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 transition">
                            <i class="bi bi-person-circle text-blue-600 dark:text-sky-400"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Row 2: Horizontal Category Navigation Bar Gaya Kompas -->
        <nav class="bg-slate-100/80 dark:bg-slate-950/70 border-t border-slate-200/80 dark:border-slate-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6 overflow-x-auto no-scrollbar text-sm font-bold whitespace-nowrap">
                    <a href="{{ route('home') }}"
                       class="py-2.5 border-b-2 transition inline-flex items-center gap-1.5 {{ request()->routeIs('home') && !$currentKategoriSlug ? 'border-blue-600 dark:border-sky-400 text-blue-600 dark:text-sky-400' : 'border-transparent text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-sky-400' }}">
                        News
                    </a>

                    @foreach ($navCategories as $index => $cat)
                        <a href="{{ route('home', array_filter(['kategori' => $cat->slug, 'q' => $currentSearchQuery])) }}"
                           class="py-2.5 border-b-2 transition inline-flex items-center gap-1.5 {{ $currentKategoriSlug === $cat->slug ? 'border-blue-600 dark:border-sky-400 text-blue-600 dark:text-sky-400' : 'border-transparent text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-sky-400' }}">
                            <span>{{ $cat->name }}</span>
                            @if ($index === 0)
                                <span class="px-1.5 py-0.2 text-[10px] font-extrabold rounded bg-rose-600 text-white leading-tight">NEW</span>
                            @endif
                        </a>
                    @endforeach

                    @auth
                        @if (auth()->user()->canManageArticles())
                            <a href="{{ route('articles.index') }}"
                               class="py-2.5 border-b-2 transition inline-flex items-center gap-1.5 {{ request()->routeIs('articles.index') || request()->routeIs('articles.create') || request()->routeIs('articles.edit') ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-amber-600 dark:text-amber-400 hover:opacity-80' }}">
                                <i class="bi bi-journal-text"></i> CRUD Artikel
                            </a>
                        @endif
                        @if (auth()->user()->canManageCategories())
                            <a href="{{ route('categories.index') }}"
                               class="py-2.5 border-b-2 transition inline-flex items-center gap-1.5 {{ request()->routeIs('categories.*') ? 'border-sky-500 text-sky-600 dark:text-sky-400' : 'border-transparent text-sky-600 dark:text-sky-400 hover:opacity-80' }}">
                                <i class="bi bi-tags"></i> CRUD Kategori
                            </a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('users.index') }}"
                               class="py-2.5 border-b-2 transition inline-flex items-center gap-1.5 {{ request()->routeIs('users.*') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-emerald-600 dark:text-emerald-400 hover:opacity-80' }}">
                                <i class="bi bi-person-plus"></i> Daftar Penulis/User
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/70 text-emerald-800 dark:text-emerald-200 flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5 text-sm font-medium">
                        <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/70 text-rose-800 dark:text-rose-200 flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2.5 text-sm font-medium">
                        <i class="bi bi-shield-exclamation text-rose-600 dark:text-rose-400 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 dark:text-rose-400 hover:opacity-75">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800/70 text-rose-800 dark:text-rose-200 shadow-sm">
                    <div class="font-bold text-sm mb-1 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-rose-600 dark:text-rose-400"></i>
                        <span>Mohon periksa kembali input Anda:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Large News Portal Footer (Adapts to Light & Dark Theme) -->
    <footer class="mt-12 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-8">
                <!-- Kolom Kiri: Brand, Sosmed & Standar Portal -->
                <div class="lg:col-span-4 space-y-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                        <span class="w-10 h-10 rounded-xl bg-blue-600 text-white inline-flex items-center justify-center text-xl shadow-sm">
                            <i class="bi bi-newspaper"></i>
                        </span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            Portal<span class="text-blue-600 dark:text-sky-400">Berita</span><span class="text-amber-500 text-sm font-bold">.id</span>
                        </span>
                    </a>

                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Portal informasi aktual, edukasi, dan teknologi terkini yang menyajikan berita terpercaya dengan sistem redaksi multi-role terintegrasi.
                    </p>

                    <!-- Social Media Icons -->
                    <div class="flex flex-wrap gap-2 pt-1">
                        @foreach (['facebook' => 'Facebook', 'twitter-x' => 'X / Twitter', 'telegram' => 'Telegram', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'whatsapp' => 'WhatsApp', 'tiktok' => 'TikTok'] as $icon => $label)
                            <a href="{{ route('home') }}" title="{{ $label }}" class="w-9 h-9 rounded-full border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-600 hover:border-blue-600 hover:text-white dark:hover:bg-blue-600 inline-flex items-center justify-center transition">
                                <i class="bi bi-{{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>

                    <!-- Badge Standar Redaksi -->
                    <div class="pt-2">
                        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 mb-2">Standar & Teknologi Portal:</div>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                                <i class="bi bi-patch-check-fill text-sky-500"></i> Terverifikasi Redaksi
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                                <i class="bi bi-shield-lock-fill text-emerald-500"></i> Multi-Role Auth
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Tengah: Kanal Berita -->
                <div class="lg:col-span-5">
                    <h6 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Kanal Berita</h6>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Berita Utama</a></li>
                            @foreach ($navCategories as $cat)
                                <li>
                                    <a href="{{ route('home', ['kategori' => $cat->slug]) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <ul class="space-y-2">
                            <li><a href="{{ route('home', ['q' => 'AI']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Kecerdasan Buatan</a></li>
                            <li><a href="{{ route('home', ['q' => 'RPL']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Rekayasa Perangkat Lunak</a></li>
                            <li><a href="{{ route('home', ['q' => 'Kampus']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Dunia Kampus</a></li>
                            <li><a href="{{ route('home', ['q' => 'UMKM']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">UMKM & Startup</a></li>
                            <li><a href="{{ route('home', ['q' => 'Timnas']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Sepak Bola</a></li>
                            <li><a href="{{ route('home', ['q' => 'Energi']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Energi Bersih</a></li>
                        </ul>

                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Artikel Terkini</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Artikel Terpopuler</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Headline Redaksi</a></li>
                            <li><a href="{{ route('home', ['q' => 'Laravel']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Tutorial & Coding</a></li>
                            <li><a href="{{ route('home', ['q' => 'Teknologi']) }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Opini & Kolom</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Indeks Berita</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom Kanan: Layanan & Akses Portal -->
                <div class="lg:col-span-3">
                    <h6 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Layanan & Akses Portal</h6>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Beranda PortalBerita</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Login Akun (Multi-Role)</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Daftar Sebagai User Penglihat</a></li>
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Mode Penglihat (Tanpa Login)</a></li>
                        @else
                            @if (auth()->user()->canManageArticles())
                                <li><a href="{{ route('articles.create') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Tulis Artikel Baru</a></li>
                                <li><a href="{{ route('articles.index') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Manajemen CRUD Artikel</a></li>
                            @endif
                            @if (auth()->user()->canManageCategories())
                                <li><a href="{{ route('categories.index') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Manajemen CRUD Kategori</a></li>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <li><a href="{{ route('users.index') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Daftarkan Penulis / User</a></li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>

            <!-- Bottom Links & Copyright -->
            <div class="pt-6 border-t border-slate-200 dark:border-slate-800 text-center space-y-2">
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs font-medium">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Tentang PortalBerita</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Susunan Redaksi</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Info Iklan & Kerja Sama</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Ketentuan Penggunaan</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Kebijakan Data Pribadi</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Pedoman Media Siber</a>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-sky-400 transition">Hubungi Kami</a>
                </div>
                <div class="text-xs text-slate-400 dark:text-slate-500">
                    Copyright &copy; {{ date('Y') }} PT PortalBerita Media Digital (Proyek Kelompok Rekayasa Perangkat Lunak). All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts: Theme Toggle, Dropdown & Modal Helpers -->
    <script>
        function togglePortalTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('portal_theme', isDark ? 'dark' : 'light');
        }

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('userDropdownMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
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
    </script>
</body>
</html>
