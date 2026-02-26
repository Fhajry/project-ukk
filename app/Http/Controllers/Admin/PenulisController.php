<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penulis;
use Illuminate\Http\Request;

class PenulisController extends Controller
{
    public function index()
    {
        $penulis = Penulis::latest()->paginate(10);

        return view('admin.penulis.index', compact('penulis'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_penulis' => 'required|string|max:255']);
        Penulis::create($request->all());

        return redirect()->route('penulis.index')->with('sukses', 'Penulis berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_penulis' => 'required|string|max:255']);
        Penulis::findOrFail($id)->update($request->all());

        return redirect()->route('penulis.index')->with('sukses', 'Penulis berhasil diubah!');
    }

    public function destroy($id)
    {
        Penulis::findOrFail($id)->delete();

        return redirect()->route('penulis.index')->with('sukses', 'Penulis berhasil dihapus!');
    }
}
