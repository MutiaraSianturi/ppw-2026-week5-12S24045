<?php

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung diarahkan ke daftar buku
Route::get('/', fn () => redirect()->route('buku.index'));

// 1 baris = 7 rute: index, create, store, show, edit, update, destroy
Route::resource('buku', BukuController::class);
Route::resource('kategori', KategoriController::class);

// SEMENTARA, nanti dibungkus middleware auth di Tahap 6
Route::get('/buku/{id}/pinjam', [PeminjamanController::class, 'create'])->name('buku.pinjam.form');
Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'store'])->name('buku.pinjam');
Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::patch('/peminjaman/{id}/kembali', [PeminjamanController::class, 'kembali'])->name('peminjaman.kembali');