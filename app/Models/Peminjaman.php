<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi secara massal
    protected $fillable = [
        'buku_id',
        'nim',
        'nama_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    // Definisikan relasi ke tabel Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}