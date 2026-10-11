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
    Schema::create('kategoris', function (Blueprint $table) {
        $table->id();                                   // kolom id (primary key, nambah otomatis)
        $table->string('kode_kategori', 10)->unique();  // kode maks 10 huruf, tidak boleh kembar
        $table->string('nama_kategori', 100);           // nama kategori
        $table->timestamps();                           // otomatis buat created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('kategoris'); // dipakai saat rollback: hapus tabel
}
};
