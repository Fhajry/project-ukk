<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class KategoriBukuController extends Controller
{
    public function index()
    {

        $kategori = KategoriBuku::latest()->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|unique:kategoris']);

        KategoriBuku::create($request->all());

        return redirect()->route('kategori.index')->with('sukses', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = KategoriBuku::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
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
