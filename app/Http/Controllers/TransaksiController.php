<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function pinjam($id)
    {
        $buku = Buku::findOrFail($id);

        // Cek stok
        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku habis');
        }

        // Cek apakah user sudah punya request pending untuk buku yang sama
        $cekPending = Transaksi::where('user_id', Auth::id())
            ->where('buku_id', $id)
            ->where('status', 'menunggu_konfirmasi')
            ->exists();

        if ($cekPending) {
            return back()->with('error', 'Anda sudah mengajukan peminjaman untuk buku ini.');
        }

        // Buat Transaksi dengan status Menunggu
        Transaksi::create([
            'user_id' => Auth::id(),
            'buku_id' => $id,
            'tanggal_pinjam' => now(), // Tanggal kosong karena belum disetujui
            'tanggal_jatuh_tempo' => now(),
            'status' => 'menunggu_konfirmasi', // <--- STATUS BARU
            'denda' => 0,
        ]);

        // Kurangi stok (Booking buku agar tidak diambil orang lain)
        $buku->decrement('stok');

        return redirect()->route('home.buku')->with('success', 'Permintaan peminjaman berhasil dikirim. Menunggu konfirmasi admin.');
    }

    // Fungsi untuk Admin Menyetujui
    public function setujuiPeminjaman($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Pastikan statusnya memang sedang menunggu
        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status transaksi tidak valid');
        }

        $transaksi->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(), // Waktu mulai dihitung saat admin klik setuju
            'tanggal_jatuh_tempo' => now()->addDays(7), // Jatuh tempo 7 hari dari SEKARANG
        ]);

        return back()->with('success', 'Peminjaman disetujui. Waktu pinjam dimulai sekarang.');
    }

    // Fungsi untuk Admin Menolak
    public function tolakPeminjaman($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status transaksi tidak valid');
        }

        // Ubah status jadi ditolak
        $transaksi->update([
            'status' => 'ditolak',

        ]);

        // KEMBALIKAN STOK BUKU (Penting!)
        $buku = Buku::find($transaksi->buku_id);
        $buku->increment('stok');

        return back()->with('success', 'Peminjaman ditolak. Stok buku telah dikembalikan.');
    }

    // USER KEMBALIKAN BUKU
    public function kembali($id)
    {
        $transaksi = Transaksi::with('buku')->findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $hariIni = now();
        $tempo = $transaksi->tanggal_jatuh_tempo;

        $denda = 0;

        if ($hariIni->gt($tempo)) {
            $hariTerlambat = $tempo->diffInDays($hariIni);
            $denda = $hariTerlambat * 1000; // ⬅️ 1.000 / HARI
        }

        $transaksi->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda' => $denda,
        ]);

        $transaksi->buku->increment('stok');

        return back()->with(
            'success',
            'Buku dikembalikan. Denda: Rp '.number_format($denda)
        );
    }

    public function hilang($id)
    {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksi = Transaksi::findOrFail($id);

        $transaksi->update([
            'status' => 'hilang',
            'denda' => 45000, // ⬅️ DENDA HILANG
        ]);

        return back()->with('success', 'Buku ditandai hilang. Denda Rp 45.000');
    }

    public function adminUsers()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $users = User::all();

        return view('admin.users', compact('users'));
    }

    public function adminTransaksi()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksis = Transaksi::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.transaksi', compact('transaksis'));
    }
    // public function adminIndex()
    // {
    //     if (Auth::user()->role !== 'admin') {
    //         abort(403);
    //     }

    //     $transaksis = Transaksi::with(['user', 'buku'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('admin.transaksi', compact('transaksis'));
    // }
    public function laporanUser(User $user)
    {
        $transaksis = $user->transaksis()
            ->with('buku')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.user', [
            'user' => $user,
            'transaksis' => $transaksis,
        ]);

        return $pdf->download('laporan-'.$user->name.'.pdf');
    }
}
