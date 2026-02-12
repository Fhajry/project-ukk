<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun', 'stok',
        'gambar', 'sinopsis', 'kategori_id',
    ];

    // Buku milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class);
    }
}
