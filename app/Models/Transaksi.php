<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo', // ⬅️ TAMBAH
        'tanggal_kembali',
        'siap_diambil_at',     // ⬅️ TAMBAH BARU
        'status',
        'denda',               // ⬅️ TAMBAH
    ];

    // OPTIONAL tapi SANGAT DISARANKAN
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
        'siap_diambil_at' => 'datetime', // ⬅️ TAMBAH BARU
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
