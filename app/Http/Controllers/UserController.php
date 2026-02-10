<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $daftar_penulis = Buku::distinct()->pluck('penulis');
        $daftar_penerbit = Buku::distinct()->pluck('penerbit');

        $buku = Buku::query()
            ->when($request->search, function ($q, $search) {
                $q->where('judul', 'like', "%{$search}%");
            })
            ->when($request->penulis, function ($q, $penulis) {
                $q->where('penulis', $penulis);
            })
            ->when($request->penerbit, function ($q, $penerbit) {
                $q->where('penerbit', $penerbit);
            })
            ->latest()
            ->get();

        return view('user.buku', compact('buku', 'daftar_penulis', 'daftar_penerbit'));
    }

    public function riwayat()
    {
        $transaksis = Transaksi::with('buku')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.riwayat', compact('transaksis'));
    }
}
