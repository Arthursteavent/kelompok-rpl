<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Halaman Publik: Daftar Berita & Detail Berita
Route::get('/', [ArticleController::class, 'home'])->name('home');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Halaman Manajemen CRUD Artikel & Kategori (Relasi Tabel)
Route::resource('articles', ArticleController::class)->except(['show']);
Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
