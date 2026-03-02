<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Transaksi;

class BukuDipinjamNotification extends Notification
{
    use Queueable;

    public $transaksi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $judul = $this->transaksi->buku->judul ?? 'Buku';
        $tempo = \Carbon\Carbon::parse($this->transaksi->tanggal_jatuh_tempo)->format('d M Y');
        
        return [
            'transaksi_id' => $this->transaksi->id,
            'message' => "Buku '{$judul}' telah kamu pinjam. Harap kembalikan maksimal pada tanggal {$tempo}.",
            'type' => 'dipinjam'
        ];
    }
}
