<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Transaksi;

class BukuSiapDiambilNotification extends Notification
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
        return [
            'transaksi_id' => $this->transaksi->id,
            'message' => "Buku '{$judul}' sudah siap diambil. Silakan ambil dalam 24 jam ke depan.",
            'type' => 'siap_diambil'
        ];
    }
}
