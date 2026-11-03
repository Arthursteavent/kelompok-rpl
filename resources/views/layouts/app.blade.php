<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PortalBerita - Blog Mini & Portal Informasi')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.4rem;
        }
        .card {
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
        }
        .article-thumb {
            height: 210px;
            object-fit: cover;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            background-color: #e2e8f0;
        }
        .headline-card {
            border-radius: 1.25rem;
            overflow: hidden;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
        }
        .headline-img {
            height: 100%;
            min-height: 320px;
            object-fit: cover;
        }
        .category-pill {
            border-radius: 50rem;
            padding: 0.45rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }
        .article-body {
            font-size: 1.08rem;
            line-height: 1.85;
            color: #334155;
        }
        .article-body p {
            margin-bottom: 1.25rem;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <!-- Top Info Bar -->
    <div class="bg-dark text-white-50 py-1 small border-bottom border-secondary border-opacity-25">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                <span class="mx-2">|</span>
                <span class="text-light fw-medium">Blog Mini & Portal Berita</span>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25">
                    <i class="bi bi-diagram-3 me-1"></i>Relasi Tabel: Categories (1) &rarr; (N) Articles
                </span>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand text-dark d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="bg-primary text-white rounded-3 px-2 py-1 d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-newspaper"></i>
                </span>
                <span>Portal<span class="text-primary">Berita</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill {{ request()->routeIs('home') || request()->routeIs('articles.show') ? 'active bg-primary bg-opacity-10 text-primary' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i>Daftar Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill {{ request()->routeIs('articles.index') || request()->routeIs('articles.edit') ? 'active bg-primary bg-opacity-10 text-primary' : '' }}" href="{{ route('articles.index') }}">
                            <i class="bi bi-journal-text me-1"></i>CRUD Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-3 rounded-pill {{ request()->routeIs('categories.*') ? 'active bg-primary bg-opacity-10 text-primary' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-tags me-1"></i>CRUD Kategori
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('articles.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tulis Artikel
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali input Anda:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6">
                    <div class="fw-bold text-dark">
                        <i class="bi bi-newspaper text-primary me-1"></i>Portal<span class="text-primary">Berita</span> — Blog Mini
                    </div>
                    <p class="text-muted small mb-0 mt-1">
                        Aplikasi Web Berita dengan Bootstrap 5 & Relasi Tabel (Kategori & Artikel).
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-flex gap-3 small">
                        <a href="{{ route('home') }}" class="text-decoration-none text-secondary">Beranda</a>
                        <a href="{{ route('articles.index') }}" class="text-decoration-none text-secondary">Kelola Artikel</a>
                        <a href="{{ route('categories.index') }}" class="text-decoration-none text-secondary">Kelola Kategori</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

