<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User; // <--- Wajib di-import
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <--- Wajib di-import

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat akun pustakawan default untuk login API Sanctum
        User::create([
            'name' => 'Pustakawan Del',
            'email' => 'pustakawan@del.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // 5 kategori dibuat manual supaya namanya realistis (bukan acak)
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

        // 20 buku dummy dari factory; kategorinya diambil acak dari 5 di atas
        Buku::factory()->count(20)->create();
    }
}