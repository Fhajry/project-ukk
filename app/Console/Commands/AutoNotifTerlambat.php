<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Notifications\BukuTerlambatNotification;

class AutoNotifTerlambat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:notif-terlambat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi kepada user yang terlambat mengembalikan buku';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hariIni = now()->startOfDay();

        // Cari transaksi yang masih 'dipinjam' dan tanggal jatuh tempo sudah lewat
        $transaksis = Transaksi::with('user') 
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', $hariIni)
            ->get();

        $count = 0;

        foreach ($transaksis as $trx) {
            $trx->user->notify(new BukuTerlambatNotification($trx));
            $count++;
        }

        $this->info("Berhasil mengirimkan notifikasi keterlambatan kepada {$count} peminjam.");
    }
}
