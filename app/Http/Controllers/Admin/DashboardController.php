<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminindex()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $data = [
                'totalBuku' => Buku::count(),
                'totalUser' => User::where('role', 'user')->count(),
                'transaksiAktif' => Transaksi::where('status', 'dipinjam')->count(),
                'totalDenda' => Transaksi::sum('denda'),
            ];

            return view('admin.dashboard', compact('data'));
        } else {
            abort(403);
        }
    }
}
