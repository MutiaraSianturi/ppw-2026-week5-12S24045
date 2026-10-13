<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('buku.index'));

// ===== Login / Logout =====
// name('login') WAJIB ada, karena middleware auth melempar tamu ke route bernama 'login'.
// guest = yang sudah login tidak perlu melihat halaman login lagi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== Rute yang mengubah data: WAJIB login (middleware auth) =====
Route::middleware('auth')->group(function () {
    // Semua aksi buku & kategori KECUALI index dan show (create, store, edit, update, destroy)
    Route::resource('buku', BukuController::class)->except(['index', 'show']);
    Route::resource('kategori', KategoriController::class)->except(['index', 'show']);

    // Sirkulasi peminjaman
    Route::get('/buku/{id}/pinjam', [PeminjamanController::class, 'create'])->name('buku.pinjam.form');
    Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'store'])->name('buku.pinjam');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::patch('/peminjaman/{id}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');
});

// ===== Rute baca-saja (katalog): boleh dilihat tanpa login =====
// Harus DITARUH SETELAH grup di atas, supaya /buku/create tidak ditangkap sebagai /buku/{buku}
Route::resource('buku', BukuController::class)->only(['index', 'show']);
Route::resource('kategori', KategoriController::class)->only(['index', 'show']);