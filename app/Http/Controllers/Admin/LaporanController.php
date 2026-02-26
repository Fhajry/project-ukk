<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // halaman daftar user
    public function index()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.laporan.index', compact('users'));
    }

    // cetak pdf per user
    public function userPdf(User $user)
    {
        $transaksis = Transaksi::with('buku')
            ->where('user_id', $user->id)
            ->get();

        $pdf = Pdf::loadView('admin.laporan.user', compact('user', 'transaksis'));

        return $pdf->download('laporan-'.$user->name.'.pdf');
    }

    public function exportExcel()
    {
        // Ambil semua data transaksi untuk Admin
        $transaksis = Transaksi::with(['buku', 'user'])->latest()->get();

        // Kita kirimkan objek Auth::user() sebagai penanda siapa yang mencetak laporan
        $user = Auth::user();

        return Excel::download(new TransaksiExport($transaksis, $user), 'Laporan_Transaksi_Perpustakaan.xlsx');
    }
}
