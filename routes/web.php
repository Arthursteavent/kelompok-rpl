<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman Publik (Boleh Login / Boleh Tanpa Login sebagai Penglihat): Daftar Berita & Detail Berita
Route::get('/', [ArticleController::class, 'home'])->name('home');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Autentikasi (Login & Daftar sebagai User Penglihat untuk tamu yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout (Untuk user yang sudah login)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman Manajemen Artikel: Bisa diakses oleh Role "admin" dan "penulis"
Route::middleware(['auth', 'role:admin,penulis'])->group(function () {
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});

// Halaman Khusus Role "admin": Manajemen Kategori & Pendaftaran Penulis/User
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->only(['index', 'update', 'destroy']);
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
});
