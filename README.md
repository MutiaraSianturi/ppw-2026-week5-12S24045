# SIPUS-Del — Sistem Informasi Perpustakaan Kampus Del

Tugas Mandiri Minggu 5  Pemrograman & Pengujian Web (12S3101)
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
1. `git clone https://github.com/MutiaraSianturi/ppw-2026-week5-12S24045.git`
2. `cd ppw-2026-week5-12S24045`
3. `composer install`
4. `copy .env.example .env`
5. `php artisan key:generate`
6. `type nul > database\database.sqlite`
7. `php artisan migrate:fresh --seed`
8. `php artisan serve`, lalu buka http://localhost:8000

## Bukti Audit SQL (Eager Loading)
Kueri dicatat oleh `DB::listen` di `AppServiceProvider` ke
`storage/logs/laravel.log`. Pengujian dilakukan dengan membuka halaman
`/buku` satu kali. File bukti: `docs/audit-eager-loading.log`.

| Kondisi | Jumlah kueri halaman /buku |
|---|---|
| Eager loading `with('kategori')` | 3 |

Penjelasan:
- 1 kueri `count` (dari `paginate()`) untuk menghitung total halaman.
- 1 kueri untuk mengambil 10 buku.
- 1 kueri untuk mengambil semua kategori sekaligus (`where id in (...)`).
- Data buku beserta kategorinya diambil dengan 2 kueri terpadu, bukan satu
  kueri per buku, sehingga tidak terjadi N+1 problem.
- Agar log bersih, session memakai driver `file` (`SESSION_DRIVER=file`)
  sehingga tabel `sessions` tidak muncul di log.

## Struktur MVC
- Model: `app/Models/Buku.php`, `app/Models/Kategori.php`
- View: `resources/views/buku`, `resources/views/kategori`,
  `resources/views/components/layout.blade.php`
- Controller: `app/Http/Controllers/BukuController.php`,
  `app/Http/Controllers/KategoriController.php`
- Route: `routes/web.php`
- 

# SIPUS-Del — Sistem Informasi Perpustakaan Kampus Del
Tugas Mandiri Minggu 5  Pemrograman & Pengujian Web (12S3101)
Institut Teknologi Del

Nama: Mutiara Y.H. Sianturi  
NIM: 12S24045  
Program Studi: Sistem Informasi  
Institut Teknologi Del  

---

## Deskripsi Ringkas
**SIPUS-Del** adalah aplikasi web perpustakaan kampus yang dirancang menggunakan arsitektur MVC (Model-View-Controller). Pada pengembangan lanjutan **Praktikum Week 9**, sistem ini diperluas secara komprehensif dengan mengintegrasikan:
1. **SOAP Web Service** untuk pengecekan status bebas pinjam mahasiswa secara sinkron berbasis XML/WSDL.
2. **RESTful API (v1)** untuk pengelolaan data buku lengkap (CRUD).
3. **Autentikasi Berbasis Token (Laravel Sanctum)** untuk mengamankan akses endpoint privat.
4. **Transaksi Basis Data Berbasis ACID & Concurrency Control** (`lockForUpdate`) untuk menjamin konsistensi stok pada proses peminjaman dan pengembalian buku.

---

## Fitur & Modul Sistem

### A. Fitur Dasar Web Server-Side (Week 5-6)
- **Manajemen Buku & Kategori**: Menggunakan `Route::resource` dengan 7 rute standar per entitas.
- **Relasi Database**: Relasi Eloquent ORM `Kategori hasMany Buku` dan `Buku belongsTo Kategori`.
- **Validasi Ketat**: Validasi server-side (`$request->validate()`) meliputi keunikan ISBN 13 digit, panjang judul, dan batas stok minimal 0.
- **Optimasi Kinerja (Eager Loading)**: Menerapkan `Buku::with('kategori')` untuk mencegah masalah *N+1 query problem*.
- **Antarmuka (Blade & Bootstrap 5)**: Layout konsisten menggunakan komponen kustom Blade bertema Del Purple (`#4C1D95`), pesan *flash*, pagination, dan struktur `@forelse`.

### B. Fitur Web Service & API (Week 9 Extension)
- **SOAP Web Service**:
  - WSDL Endpoint: `GET /soap/sipus.wsdl`
  - SOAP Handler Endpoint: `POST /soap/sipus` (untuk validasi status peminjaman berdasarkan NIM).
- **REST API v1 (Buku & Peminjaman)**:
  - **CRUD Buku**: Endpoint publik dan terproteksi untuk melihat, menambah, memperbarui, dan menghapus data buku.
  - **Autentikasi Sanctum**: Endpoint Login (`POST /api/v1/login`) yang menghasilkan *Bearer Token* dan Logout (`POST /api/v1/logout`).
  - **Transaksi Peminjaman ACID**: Endpoint (`POST /api/v1/peminjamans`) yang dilengkapi proteksi `DB::transaction` dan `lockForUpdate` untuk mencegah *race condition* saat stok buku menipis.

---

## Stack Teknologi
- **Bahasa & Framework**: PHP 8.5.11, Laravel 13
- **Database**: SQLite (`database/database.sqlite`)
- **Autentikasi**: Laravel Sanctum v4+
- **Protokol Web**: HTTP REST JSON API, SOAP XML / WSDL
- **Frontend / UI**: Bootstrap 5, Blade Component Templates

---

## Panduan Instalasi & Menjalankan Proyek

Ikuti langkah-langkah di bawah ini di terminal VS Code (pada *branch* `week9-rest-api`):

1. **Clone Repository & Masuk ke Folder Proyek**:
   ```powershell
   git clone [https://github.com/MutiaraSianturi/ppw-2026-week5-12S24045.git](https://github.com/MutiaraSianturi/ppw-2026-week5-12S24045.git)
   cd ppw-2026-week5-12S24045
