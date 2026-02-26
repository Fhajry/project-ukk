<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Penulis;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $data = [
            'totalBuku' => Buku::count(),
            'totalUser' => User::where('role', 'user')->count(),
            'transaksiAktif' => Transaksi::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count(),
            'totalDenda' => Transaksi::sum('denda'), // Sesuaikan dengan logika Anda
        ];

        // Jika yang login adalah user biasa, tambahkan data personalnya
        if (auth()->check() && auth()->user()->role === 'user') {
            $userId = auth()->id();
            $data['dipinjam'] = Transaksi::where('user_id', $userId)->where('status', 'dipinjam')->count();
            $data['telat'] = Transaksi::where('user_id', $userId)->where('status', 'dipinjam')->where('tanggal_jatuh_tempo', '<', now())->count();
            $data['totalDenda'] = Transaksi::where('user_id', $userId)->sum('denda');
        }

        return view('home.dashboard', compact('data'));
    }

    public function buku(Request $request)
    {
        $daftar_penulis = Penulis::all();
        $daftar_penerbit = Penerbit::all();
        $kategoris = KategoriBuku::all();

        $buku = Buku::with(['kategori', 'penulis', 'penerbit'])
            ->when($request->search, function ($q, $search) {
                $q->where('judul', 'like', "%{$search}%");
            })
            ->when($request->penulis_id, function ($q, $penulis_id) {
                $q->where('penulis_id', $penulis_id);
            })
            ->when($request->penerbit_id, function ($q, $penerbit_id) {
                $q->where('penerbit_id', $penerbit_id);
            })
            ->when($request->kategori_id, function ($q, $kategori_id) {
                $q->where('kategori_id', $kategori_id);
            })
            ->latest()
            ->paginate(12);

        return view('home.buku', compact('buku', 'daftar_penulis', 'daftar_penerbit', 'kategoris'));
    }

    public function show($id)
    {
        // Mengambil data buku berdasarkan ID
        $buku = Buku::findOrFail($id);

        return view('home.detail', compact('buku'));
    }

    public function riwayat()
    {
        $transaksis = Transaksi::with('buku')
            ->where('user_id', Auth::id())
            ->get();

        return view('home.riwayat', compact('transaksis'));
    }
}
