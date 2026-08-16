<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric'
        ]);

        Obat::create($request->all());
        return redirect()->back()->with('sukses', 'Data obat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric'
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update($request->all());
        return redirect()->back()->with('sukses', 'Data obat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Obat::findOrFail($id)->delete();
        return redirect()->back()->with('sukses', 'Data obat berhasil dihapus.');
    }
}