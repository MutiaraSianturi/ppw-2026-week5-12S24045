<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Akun pustakawan untuk login =====
        // Password otomatis di-hash karena Model User punya cast 'hashed'
        User::create([
            'name'     => 'Pustakawan Del',
            'email'    => 'pustakawan@del.ac.id',
            'password' => 'password123',
        ]);

        // ===== 5 kategori (sama seperti Minggu 5) =====
        $kategoris = [
            ['kode_kategori' => 'INF', 'nama_kategori' => 'Informatika & Pemrograman'],
            ['kode_kategori' => 'SIS', 'nama_kategori' => 'Sistem Informasi'],
            ['kode_kategori' => 'ELK', 'nama_kategori' => 'Teknik Elektro'],
            ['kode_kategori' => 'MAT', 'nama_kategori' => 'Matematika & Statistika'],
            ['kode_kategori' => 'UMM', 'nama_kategori' => 'Pengetahuan Umum'],
        ];
        foreach ($kategoris as $k) {
            Kategori::create($k);
        }

        // ===== 18 buku acak (+ 2 buku khusus di bawah = total 20) =====
        Buku::factory()->count(18)->create();

        // Buku khusus untuk uji transaksi
        $bukuNol = Buku::create([
            'isbn' => '9786020000010', 'judul' => 'Pemrograman Web Lanjut (Stok Kosong)',
            'penulis' => 'Tim Dosen Del', 'penerbit' => 'Penerbit Del Press',
            'tahun_terbit' => 2024, 'kategori_id' => 1,
            'stok' => 0,   // stok 0: buat bukti penolakan peminjaman
            'sinopsis' => 'Buku uji dengan stok 0.',
        ]);

        $bukuSatu = Buku::create([
            'isbn' => '9786020000027', 'judul' => 'Basis Data Relasional (Stok Satu)',
            'penulis' => 'Tim Dosen Del', 'penerbit' => 'Penerbit Del Press',
            'tahun_terbit' => 2024, 'kategori_id' => 2,
            'stok' => 1,   // stok 1: buat uji pinjam terakhir
            'sinopsis' => 'Buku uji dengan stok 1.',
        ]);

        // ===== Sampel transaksi =====
        // Buku stok 0 memang sedang dipinjam orang (datanya jadi masuk akal)
        Peminjaman::create([
            'kode_pinjam' => 'PJM-SEED-001', 'buku_id' => $bukuNol->id,
            'nim_peminjam' => '12S24001', 'nama_peminjam' => 'Andi Simanjuntak',
            'tanggal_pinjam' => now()->subDays(3)->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(4)->toDateString(),
            'status' => 'dipinjam',
        ]);

        // Contoh transaksi yang sudah dikembalikan
        Peminjaman::create([
            'kode_pinjam' => 'PJM-SEED-002', 'buku_id' => $bukuSatu->id,
            'nim_peminjam' => '12S24002', 'nama_peminjam' => 'Siti Nababan',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
            'tanggal_kembali' => now()->subDays(5)->toDateString(),
            'status' => 'dikembalikan',
        ]);
    }
}