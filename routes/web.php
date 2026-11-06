<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung diarahkan ke daftar buku
Route::get('/', fn () => redirect()->route('buku.index'));

// 1 baris = 7 rute: index, create, store, show, edit, update, destroy
Route::resource('buku', BukuController::class);
Route::resource('kategori', KategoriController::class);

use App\Http\Controllers\SoapController;

Route::get('/soap/sipus.wsdl', [SoapController::class, 'wsdl']);
Route::post('/soap/sipus', [SoapController::class, 'handle']);