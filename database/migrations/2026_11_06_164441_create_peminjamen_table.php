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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            // Tambahkan semua kolom yang dibutuhkan di sini
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->string('nim');
            $table->string('nama_peminjam');
            $table->dateTime('tanggal_pinjam')->nullable();
            $table->dateTime('tanggal_kembali')->nullable();
            $table->string('status')->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};