<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class KategoriBukuController extends Controller
{
    public function index()
    {
        $kategori = KategoriBuku::latest()->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|unique:kategoris']);

        KategoriBuku::create($request->all());

        return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil ditambahkan!');
    }


    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required']);

        $kategori = KategoriBuku::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        KategoriBuku::findOrFail($id)->delete();

        return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil dihapus!');
    }
}
