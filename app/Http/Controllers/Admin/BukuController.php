<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Penulis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
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
            ->paginate(10);

        return view('admin.buku.index', compact('buku', 'daftar_penulis', 'daftar_penerbit', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBuku::all();
        $penulis = Penulis::all();
        $penerbit = Penerbit::all();

        return view('admin.buku.create', compact('kategoris', 'penulis', 'penerbit'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = KategoriBuku::all();
        $penulis = Penulis::all();
        $penerbit = Penerbit::all();

        return view('admin.buku.edit', compact('buku', 'kategoris', 'penulis', 'penerbit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis_id' => 'required',
            'penerbit_id' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'kategori_id' => 'required',
        ]);

        $input = $request->all();

        if ($request->hasFile('gambar')) {
            $input['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        

        Buku::create($input);

        return redirect()->route('bukus.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'penulis_id' => 'required',
            'penerbit_id' => 'required',
            'tahun' => 'required|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'kategori_id' => 'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($buku->gambar && Storage::exists('public/'.$buku->gambar)) {
                Storage::delete('public/'.$buku->gambar);
            }
            // Upload gambar baru
            $data['gambar'] = $request->file('gambar')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('bukus.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Hapus file gambar dari storage sebelum hapus data
        if ($buku->gambar && Storage::exists('public/'.$buku->gambar)) {
            Storage::delete('public/'.$buku->gambar);
        }

        $buku->delete();

        return redirect()->route('bukus.index')->with('success', 'Buku berhasil dihapus');
    }
}
