<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    protected $table = 'penulis';

    protected $fillable = ['nama_penulis'];

    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}
