<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    // Nama tabel dipaku manual. Kalau tidak, Laravel menebak 'peminjamen'
    protected $table = 'peminjamans';

    // Kolom yang boleh diisi lewat create()/update() (proteksi mass assignment)
    protected $fillable = [
        'kode_pinjam', 'buku_id', 'nim_peminjam', 'nama_peminjam',
        'tanggal_pinjam', 'tanggal_jatuh_tempo', 'tanggal_kembali', 'status',
    ];

    // Kolom tanggal otomatis jadi objek tanggal, jadi bisa ->format('d M Y')
    protected function casts(): array
    {
        return [
            'tanggal_pinjam'      => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali'     => 'date',
        ];
    }

    // Relasi: 1 peminjaman MILIK 1 buku (belongsTo)
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}