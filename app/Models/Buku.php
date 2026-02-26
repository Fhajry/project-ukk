<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'judul', 'penulis_id', 'penerbit_id', 'tahun', 'stok',
        'gambar', 'sinopsis', 'kategori_id',
    ];

    // Buku milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'penulis_id');
    }

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }
}
