<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
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

        return view('buku.index', compact('buku', 'daftar_penulis', 'daftar_penerbit'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Buku::create($request->all());

        return redirect('/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);

        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect('/buku')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect('/buku')->with('success', 'Buku berhasil dihapus');
    }
}
