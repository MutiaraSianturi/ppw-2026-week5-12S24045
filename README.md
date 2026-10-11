# SIPUS-Del — Sistem Informasi Perpustakaan Kampus Del

Tugas Mandiri Minggu 5 — Pemrograman & Pengujian Web (12S3101)
Institut Teknologi Del

Nama: Mutiara Y.H. Sianturi
NIM:  12S24045

## Deskripsi
Aplikasi web perpustakaan sederhana untuk mengelola data **buku** dan
**kategori**, dibangun dengan pola arsitektur MVC (server-side rendering)
memakai Laravel.

## Fitur
- CRUD Buku dan Kategori memakai `Route::resource` (7 rute per entitas)
- Implicit Route Model Binding
- Relasi Kategori hasMany Buku dan Buku belongsTo Kategori
- Proteksi mass assignment dengan `$fillable`
- Validasi server-side (`$request->validate()`): ISBN unik 13 digit,
  judul minimal 5 karakter, stok minimal 0
- Proteksi CSRF (`@csrf`) dan method spoofing (`@method`) di semua form
- Master layout Blade Component `<x-layout>` bertema Del Purple (#4C1D95)
- Flash message, pagination Bootstrap 5, dan `@forelse` untuk data kosong
- Eager Loading `Buku::with('kategori')` untuk mencegah N+1
- Seeder dan Factory (Faker): 5 kategori dan 20 buku

## Teknologi
Laravel 13, PHP 8.5, SQLite, Bootstrap 5, Blade

Catatan: modul menyebut Laravel 11. Proyek ini memakai Laravel 13 sesuai
arahan dosen. Konsep dan struktur yang dipelajari sama.

## Cara Instalasi
1. `git clone https://github.com/[MutiaraSianturi]/ppw-2026-week5-12S24045.git`
2. `cd ppw-2026-week5-12S24045`
3. `composer install`
4. `copy .env.example .env`
5. `php artisan key:generate`
6. `type nul > database\database.sqlite`
7. `php artisan migrate:fresh --seed`
8. `php artisan serve`, lalu buka http://localhost:8000

## Bukti Audit SQL (Eager Loading vs N+1)
Kueri dicatat oleh `DB::listen` di `AppServiceProvider` ke
`storage/logs/laravel.log`. Pengujian dilakukan dengan membuka halaman
`/buku` satu kali. File bukti ada di folder `docs/`:

- `docs/audit-n-plus-1.log` (tanpa `with()`)
- `docs/audit-eager-loading.log` (dengan `with('kategori')`)

| Kondisi | Jumlah kueri halaman /buku |
|---|---|
| Lazy loading (N+1) | 12 |
| Eager loading `with('kategori')` | 3 |

Penjelasan:
- Tanpa eager loading: 1 kueri `count` (dari `paginate()`) + 1 kueri buku
  + 10 kueri kategori (satu per buku) = 12 kueri.
- Dengan eager loading: 1 kueri `count` + 1 kueri buku + 1 kueri kategori
  (`where id in (...)`) = 3 kueri.
- Data buku beserta kategorinya diambil dengan 2 kueri terpadu. Kueri
  `count` tambahan berasal dari `paginate()`, bukan dari relasi.
- Agar log bersih, session memakai driver `file` (`SESSION_DRIVER=file`)
  sehingga tabel `sessions` tidak muncul di log.

## Struktur MVC
- Model: `app/Models/Buku.php`, `app/Models/Kategori.php`
- View: `resources/views/buku`, `resources/views/kategori`,
  `resources/views/components/layout.blade.php`
- Controller: `app/Http/Controllers/BukuController.php`,
  `app/Http/Controllers/KategoriController.php`
- Route: `routes/web.php`