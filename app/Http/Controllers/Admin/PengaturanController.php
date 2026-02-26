<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Ambil data pertama. Jika tabel masih kosong, buat default-nya otomatis.
        $pengaturan = Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'denda_harian' => 1000,
                'denda_hilang' => 45000,
                'hari_jatuh_tempo' => 7,
            ]
        );

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'denda_harian' => 'required|integer|min:0',
            'denda_hilang' => 'required|integer|min:0',
            'hari_jatuh_tempo' => 'required|integer|min:1',
        ]);

        $pengaturan = Pengaturan::first();
        $pengaturan->update([
            'denda_harian' => $request->denda_harian,
            'denda_hilang' => $request->denda_hilang,
            'hari_jatuh_tempo' => $request->hari_jatuh_tempo,
        ]);

        return back()->with('success', 'Konfigurasi perpustakaan berhasil diperbarui!');
    }
}
