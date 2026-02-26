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
            $table->string('judul');
            $table->foreignId('penulis_id')->constrained('penulis')->onDelete('cascade');
            $table->foreignId('penerbit_id')->constrained('penerbits')->onDelete('cascade');
            $table->year('tahun');
            $table->integer('stok');
            $table->string('gambar')->nullable();
            $table->text('sinopsis')->nullable();
            // Relasi ke tabel kategoris
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
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
