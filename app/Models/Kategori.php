<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory; // supaya bisa pakai Kategori::factory() untuk data dummy

    // $fillable = kolom yang BOLEH diisi massal lewat create()/update().
    // Kolom lain ditolak. Ini proteksi dari serangan mass assignment.
    protected $fillable = ['kode_kategori', 'nama_kategori'];

    // Relasi: 1 kategori punya BANYAK buku (hasMany)
    public function bukus(): HasMany
    {
        return $this->hasMany(Buku::class);
    }
}