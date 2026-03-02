<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransaksiExport;
use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use App\Models\User;
use App\Notifications\BukuSiapDiambilNotification;
use App\Notifications\BukuDipinjamNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransaksiController extends Controller
{
    public function pinjam($id)
    {
        if (Auth::user()->role !== 'user') {
            abort(403);
        }

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

        DB::transaction(function () use ($id, $pengaturan, $buku) {
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
        });

        return redirect()->route('home.buku')->with('success', 'Permintaan peminjaman berhasil dikirim. Menunggu konfirmasi admin.');
    }

    // Fungsi untuk Admin Menyetujui -> Status Siap Diambil
    public function setujuiPeminjaman($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // CEk statusnya memang sedang menunggu
        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status transaksi tidak valid');
        }

        $transaksi->update([
            'status' => 'siap_diambil',
            'siap_diambil_at' => now(), // Catat waktu kapan buku dinyatakan siap
        ]);

        // Kirim notifikasi ke User
        $transaksi->user->notify(new BukuSiapDiambilNotification($transaksi));

        return back()->with('success', 'Buku siap diambil. Notifikasi telah dikirim ke peminjam.');
    }

    // Fungsi untuk Admin saat User mengambil buku
    public function konfirmasiPengambilan($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Pastikan statusnya memang sedang siap_diambil
        if ($transaksi->status !== 'siap_diambil') {
            return back()->with('error', 'Status transaksi tidak valid');
        }

        DB::transaction(function () use ($transaksi) {
            $transaksi->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => now(), // Waktu pinjam dihitung HANYA saat buku benar-benar diambil
                'tanggal_jatuh_tempo' => now()->addDays(7), // Jatuh tempo 7 hari dari waktu pengambilan
            ]);
            
            // Kirim notifikasi buku resmi terpinjam
            $transaksi->user->notify(new BukuDipinjamNotification($transaksi));
        });

        return back()->with('success', 'Buku telah diambil peminjam. Masa peminjaman dimulai hari ini.');
    }

    // Fungsi untuk Admin Menolak
    public function tolakPeminjaman($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Status transaksi tidak valid');
        }

        DB::transaction(function () use ($transaksi) {
            // Ubah status jadi ditolak
            $transaksi->update([
                'status' => 'ditolak',
            ]);

            // KEMBALIKAN STOK BUKU (Penting!)
            $buku = Buku::find($transaksi->buku_id);
            if ($buku) {
                $buku->increment('stok');
            }
        });

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

    public function adminTransaksi(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Mulai Query dengan relasi
        $query = Transaksi::with(['user', 'buku']);

        // 1. Filter Pencarian (Nama User ATAU Judul Buku)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Cari di relasi tabel users
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                // ATAU Cari di relasi tabel bukus
                    ->orWhereHas('buku', function ($bukuQuery) use ($search) {
                        $bukuQuery->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Filter Berdasarkan Tanggal Pinjam
        if ($request->filled('tgl_pinjam')) {
            $query->whereDate('tanggal_pinjam', $request->tgl_pinjam);
        }

        // 3. Filter Berdasarkan Tanggal Kembali
        if ($request->filled('tgl_kembali')) {
            $query->whereDate('tanggal_kembali', $request->tgl_kembali);
        }

        // Eksekusi Query (Gunakan paginate agar lebih rapi jika datanya banyak)
        $transaksis = $query->latest()->paginate(15);
        // Jika tidak mau pakai pagination, ganti paginate(15) menjadi get()

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

    public function exportExcel()
    {
        // 1. Ambil semua data transaksi beserta relasi buku dan user
        $transaksis = Transaksi::with(['buku', 'user'])->latest()->get();

        // 2. Ambil data admin yang sedang login (untuk dicetak di laporan)
        $user = Auth::user();

        // 3. Proses download file Excel
        return Excel::download(new TransaksiExport($transaksis, $user), 'Laporan_Transaksi_Perpustakaan.xlsx');
    }
}
