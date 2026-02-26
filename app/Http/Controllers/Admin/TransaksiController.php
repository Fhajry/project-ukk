<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\Buku;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function pinjam($id)
    {
        $buku = Buku::findOrFail($id);
        $pengaturan = Pengaturan::first();

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
            'tanggal_jatuh_tempo' => now()->addDays($pengaturan->hari_jatuh_tempo),
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
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksi = Transaksi::with('buku')->findOrFail($id);

        // Cegah buku dikembalikan 2x (mencegah stok nambah terus)
        if ($transaksi->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        // Panggil pengaturan dari database
        $pengaturan = Pengaturan::first();

        $hariIni = now()->startOfDay();
        $tempo = Carbon::parse($transaksi->tanggal_jatuh_tempo)->startOfDay();

        $denda = 0;

        // Hitung denda dinamis jika telat
        if ($hariIni->gt($tempo)) {
            $hariTerlambat = $tempo->diffInDays($hariIni);
            $denda = $hariTerlambat * $pengaturan->denda_harian; // ⬅️ Pakai data dari DB
        }

        // Gunakan DB Transaction agar aman
        DB::transaction(function () use ($transaksi, $denda) {
            $transaksi->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'denda' => $denda,
            ]);

            if ($transaksi->buku) {
                $transaksi->buku->increment('stok');
            }
        });

        $pesanDenda = $denda > 0 ? ' Denda: Rp '.number_format($denda, 0, ',', '.') : ' (Tepat Waktu)';

        return back()->with('success', 'Buku berhasil dikembalikan.'.$pesanDenda);
    }

    public function hilang($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $transaksi = Transaksi::findOrFail($id);

        // Cegah klik hilang 2x
        if ($transaksi->status === 'hilang') {
            return back()->with('error', 'Buku ini sudah ditandai hilang.');
        }

        // Panggil pengaturan dari database
        $pengaturan = Pengaturan::first();

        $transaksi->update([
            'status' => 'hilang',
            'denda' => $pengaturan->denda_hilang, // ⬅️ Pakai data dari DB
        ]);

        return back()->with('success', 'Buku ditandai hilang. Denda Rp '.number_format($pengaturan->denda_hilang, 0, ',', '.'));
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
