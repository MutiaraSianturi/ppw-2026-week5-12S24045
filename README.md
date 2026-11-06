# SIPUS-Del — Sistem Informasi Perpustakaan Kampus Del (Week 9 Extension)

Tugas Mandiri Pemrograman & Pengujian Web (12S3101 / PPW)
Institut Teknologi Del

Nama: Mutiara Y.H. Sianturi
NIM:  12S24045

## Deskripsi
Pengembangan lanjutan dari aplikasi perpustakaan SIPUS-Del. Pada modul Week 9 ini, sistem diperluas dengan menambahkan dukungan **SOAP Web Service**, **REST API (v1)** lengkap dengan pengelolaan CRUD Buku, **Autentikasi Berbasis Token (Laravel Sanctum)**, serta **Transaksi Peminjaman Buku berbasis ACID** untuk menjaga konsistensi stok data.

## Fitur Utama Week 9
- **SOAP Web Service**: Endpoint WSDL dan handler untuk mengecek status bebas pinjam mahasiswa berdasarkan NIM (`/soap/sipus.wsdl` & `/soap/sipus`).
- **REST API Buku**: Endpoint lengkap (GET, POST, PUT, PATCH, DELETE) untuk pengelolaan data buku di `api/v1/bukus`.
- **Autentikasi Sanctum**: Fitur login (`/api/v1/login`) dan logout (`/api/v1/logout`) berbasis token bearer yang aman.
- **Transaksi Peminjaman (ACID)**: Fitur peminjaman dan pengembalian buku (`/api/v1/peminjamans`) yang dilengkapi mekanisme pengamanan stok (`lockForUpdate`) untuk menghindari race condition.

## Fitur Dasar Sebelumnya (Week 5-6)
- CRUD Buku dan Kategori memakai `Route::resource`
- Relasi Kategori `hasMany` Buku dan Buku `belongsTo` Kategori
- Validasi server-side (`$request->validate()`)
- Eager Loading `Buku::with('kategori')` untuk mencegah N+1 problem

## Teknologi
Laravel 13, PHP 8.5, SQLite, Laravel Sanctum, SOAP Extension, REST JSON API

## Cara Instalasi & Menjalankan Project
1. `git clone https://github.com/MutiaraSianturi/ppw-2026-week5-12S24045.git`
2. `cd ppw-2026-week5-12S24045`
3. `composer install`
4. `copy .env.example .env`
5. `php artisan key:generate`
6. Buat database SQLite: `New-Item -ItemType File -Path "database\database.sqlite"` (atau lewat touch/manual)
7. Jalankan migrasi: `php artisan migrate`
8. Jalankan server lokal: `php artisan serve`, lalu akses http://localhost:8000

## Daftar Endpoint API & SOAP
- **SOAP WSDL**: `GET http://localhost:8000/soap/sipus.wsdl`
- **SOAP Handler**: `POST http://localhost:8000/soap/sipus`
- **API Login**: `POST http://localhost:8000/api/v1/login` (mengirim `email` & `password`)
- **API Buku (REST)**: `GET/POST/PUT/DELETE /api/v1/bukus`
- **API Peminjaman**: `GET/POST /api/v1/peminjamans` (membutuhkan Bearer Token Sanctum)

## Struktur Controller & Endpoint Utama
- Model: `app/Models/Buku.php`, `app/Models/Kategori.php`, `app/Models/Peminjaman.php`, `app/Models/User.php`
- Controller REST API: `app/Http/Controllers/Api/V1/BukuApiController.php`, `AuthController.php`, `PeminjamanApiController.php`
- Controller SOAP: `app/Http/Controllers/SoapController.php`
- Route: `routes/web.php` & `routes/api.php`