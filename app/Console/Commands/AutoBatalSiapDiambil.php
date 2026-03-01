<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class AutoBatalSiapDiambil extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:auto-batal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan peminjaman otomatis jika buku tidak diambil dalam 24 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batasWaktu = now()->subHours(24);

        // Cari transaksi yang statusnya 'siap_diambil' dan sudah lebih dari 24 jam
        $transaksis = Transaksi::where('status', 'siap_diambil')
            ->where('siap_diambil_at', '<', $batasWaktu)
            ->get();

        $count = 0;

        foreach ($transaksis as $trx) {
            DB::transaction(function () use ($trx) {
                // Ubah status
                $trx->update([
                    'status' => 'batal_otomatis'
                ]);

                // Kembalikan stok buku
                if ($trx->buku) {
                    $trx->buku->increment('stok');
                }
            });
            $count++;
        }

        $this->info("Berhasil membatalkan {$count} transaksi yang lewat batas waktu 24 jam.");
    }
}
