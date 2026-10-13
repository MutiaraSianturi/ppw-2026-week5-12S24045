<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();                                        // primary key, nambah otomatis
        $table->string('kode_pinjam', 30)->unique();         // kode transaksi, tidak boleh kembar
        // foreign key ke tabel bukus.
        // cascadeOnDelete = kalau buku dihapus, riwayat pinjamnya ikut terhapus
        $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
        $table->string('nim_peminjam', 15);                  // NIM peminjam
        $table->string('nama_peminjam', 150);                // nama peminjam
        $table->date('tanggal_pinjam');                      // kapan dipinjam
        $table->date('tanggal_jatuh_tempo');                 // batas harus kembali
        $table->date('tanggal_kembali')->nullable();         // kosong sampai buku dikembalikan
        $table->string('status', 15)->default('dipinjam')->index(); // 'dipinjam' / 'dikembalikan'
        $table->timestamps();                                // created_at dan updated_at otomatis
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('peminjamans'); // dipakai saat rollback: hapus tabel
}
};
