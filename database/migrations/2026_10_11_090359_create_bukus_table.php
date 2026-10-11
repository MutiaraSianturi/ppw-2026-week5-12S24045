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
    Schema::create('bukus', function (Blueprint $table) {
        $table->id();
        $table->string('isbn', 13)->unique();          // ISBN 13 digit, tidak boleh kembar
        $table->string('judul', 200)->index();         // index = pencarian judul lebih cepat
        $table->string('penulis', 150);
        $table->string('penerbit', 150);
        $table->unsignedSmallInteger('tahun_terbit');  // angka tanpa minus
        // foreign key ke tabel kategoris.
        // cascadeOnDelete = kalau kategori dihapus, buku di dalamnya ikut terhapus
        $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
        $table->unsignedInteger('stok')->default(0);   // stok tidak bisa minus, awalnya 0
        $table->text('sinopsis')->nullable();          // boleh dikosongkan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('bukus');
}
};
