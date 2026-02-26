<?php

namespace App\Http\Controllers\Admin;


use App\Models\Penerbit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PenerbitController extends Controller
{
    public function index()
    {
        $penerbit = Penerbit::latest()->get();

        return view('admin.penerbit.index', compact('penerbit'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_penerbit' => 'required|string|max:255']);
        Penerbit::create($request->all());

        return redirect()->route('penerbit.index')->with('sukses', 'penerbit berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_penerbit' => 'required|string|max:255']);
        Penerbit::findOrFail($id)->update($request->all());

        return redirect()->route('penerbit.index')->with('sukses', 'penerbit berhasil diubah!');
    }

    public function destroy($id)
    {
        Penerbit::findOrFail($id)->delete();

        return redirect()->route('penerbit.index')->with('sukses', 'penerbit berhasil dihapus!');
    }
}
