<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        // 3 huruf kapital acak; unique() supaya tidak ada kode kembar
        'kode_kategori' => strtoupper(fake()->unique()->lexify('???')),
        'nama_kategori' => fake()->words(2, true), // 2 kata acak
    ];
}
}
