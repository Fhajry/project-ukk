<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {

        $daftar_penulis = Buku::distinct()->pluck('penulis');
        $daftar_penerbit = Buku::distinct()->pluck('penerbit');
        $kategoris = KategoriBuku::all();

        $buku = Buku::with('kategori')
            ->when($request->search, function ($q, $search) {
                $q->where('judul', 'like', "%{$search}%");
            })
            ->when($request->penulis, function ($q, $penulis) {
                $q->where('penulis', $penulis);
            })
            ->when($request->penerbit, function ($q, $penerbit) {
                $q->where('penerbit', $penerbit);
            })->when($request->kategori, function ($q, $kategori_id) {
                $q->where('kategori_id', $kategori_id);
            })
            ->latest()
            ->get();

        return view('admin.buku.index', compact('buku', 'daftar_penulis', 'daftar_penerbit', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBuku::all();

        return view('admin.buku.create', compact('kategoris'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = KategoriBuku::all();

        return view('admin.buku.edit', compact('buku', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'kategori_id' => 'required',
        ]);

        // dd($request->all());
        // 2. Ambil semua data input dari form
        $input = $request->all();

        // 3. Cek jika ada file gambar yang diupload
        if ($request->hasFile('gambar')) {
            $input['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        Buku::create($input);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'kategori_id' => 'required',
        ]);

        $data = $request->except(['gambar']); // Ambil semua data kecuali gambar

        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($buku->gambar && Storage::exists('public/'.$buku->gambar)) {
                Storage::delete('public/'.$buku->gambar);
            }

            // 2. Upload gambar baru
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect('/buku')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect('/buku')->with('success', 'Buku berhasil dihapus');
    }
}
