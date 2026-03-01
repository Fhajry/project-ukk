<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi ENUM status
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('menunggu_konfirmasi', 'ditolak', 'dipinjam', 'dikembalikan', 'hilang', 'siap_diambil', 'batal_otomatis') NOT NULL DEFAULT 'menunggu_konfirmasi'");
        
        // 2. Tambah kolom siap_diambil_at
        Schema::table('transaksis', function (Blueprint $table) {
            $table->timestamp('siap_diambil_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('siap_diambil_at');
        });

        // Kembalikan ENUM status ke semula (tanpa siap_diambil dan batal_otomatis)
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('menunggu_konfirmasi', 'ditolak', 'dipinjam', 'dikembalikan', 'hilang') NOT NULL DEFAULT 'menunggu_konfirmasi'");
    }
};
