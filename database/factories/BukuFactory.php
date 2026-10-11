<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isbn'         => fake()->unique()->isbn13(),                 // 13 digit, tidak kembar
            'judul'        => rtrim(fake()->unique()->sentence(4), '.'),  // kalimat 4 kata, titik ujung dibuang
            'penulis'      => fake()->name(),
            'penerbit'     => fake()->randomElement([                     // pilih acak dari daftar
                'Gramedia Pustaka Utama', 'Erlangga', 'Informatika Bandung',
                'Andi Offset', 'Mizan', 'Deepublish', 'Penerbit Del Press',
            ]),
            'tahun_terbit' => fake()->numberBetween(2005, (int) date('Y')),
            // ambil id kategori acak yang SUDAH ada di database
            'kategori_id'  => Kategori::inRandomOrder()->value('id') ?? Kategori::factory(),
            'stok'         => fake()->numberBetween(0, 25),
            'sinopsis'     => fake()->paragraph(3),
        ];
    }
}