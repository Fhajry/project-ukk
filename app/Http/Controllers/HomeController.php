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
        $user = auth()->user();

        // 1. Ambil Statistik Ringkas
        $sedangDipinjam = \App\Models\Transaksi::where('user_id', $user->id)->where('status', 'dipinjam')->count();
        //  siapkan status 'siap_diambil' untuk fitur otomatisasi
        $siapDiambil = \App\Models\Transaksi::where('user_id', $user->id)->where('status', 'siap_diambil')->count();
        $totalDenda = \App\Models\Transaksi::where('user_id', $user->id)->sum('denda');
        $telat = \App\Models\Transaksi::where('user_id', $user->id)->where('status', 'dipinjam')->where('tanggal_jatuh_tempo', '<', now())->count();

        // 2. Ambil Transaksi Aktif (Menunggu, Siap Diambil, Dipinjam)
        $transaksiAktif = \App\Models\Transaksi::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu_konfirmasi', 'siap_diambil', 'dipinjam'])
            ->latest()
            ->get();

        return view('home.dashboard', compact('sedangDipinjam', 'siapDiambil', 'totalDenda', 'telat', 'transaksiAktif'));
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
            ->latest()
            ->get();

        return view('home.riwayat', compact('transaksis'));
    }

    public function notifikasi()
    {
        $notifikasis = auth()->user()->notifications()->latest()->paginate(10);

        return view('home.notifikasi', compact('notifikasis'));
    }

    public function bacaNotifikasi($id)
    {
        $notifikasi = auth()->user()->notifications()->findOrFail($id);
        $notifikasi->markAsRead();

        return back();
    }
}
