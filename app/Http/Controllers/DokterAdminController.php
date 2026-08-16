<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class DokterAdminController extends Controller
{
    // === DOKTER CRUD ===
    public function indexDokter()
    {
        $dokters = Dokter::all();
        return view('admin.dokter.index', compact('dokters'));
    }

    public function storeDokter(Request $request)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'tarif'        => 'required|numeric|min:0'
        ]);

        Dokter::create([
            'nama_dokter'  => $request->nama_dokter,
            'spesialisasi' => $request->spesialisasi,
            'tarif'        => $request->tarif,
        ]);

        return redirect()->back()->with('sukses', 'Data dokter berhasil ditambahkan.');
    }

    public function updateDokter(Request $request, $id)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'tarif'        => 'required|numeric|min:0'
        ]);

        $dokter = Dokter::findOrFail($id);
        $dokter->update([
            'nama_dokter'  => $request->nama_dokter,
            'spesialisasi' => $request->spesialisasi,
            'tarif'        => $request->tarif,
        ]);

        return redirect()->back()->with('sukses', 'Data & tarif dokter berhasil diperbarui!');
    }

    public function destroyDokter($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();

        return redirect()->back()->with('sukses', 'Data dokter berhasil dihapus.');
    }

    // === JADWAL JAGA CRUD ===
    public function indexJadwal()
    {
        $jadwals = JadwalDokter::with('dokter')->get();
        $dokters = Dokter::all();
        return view('admin.jadwal.index', compact('jadwals', 'dokters'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'dokter_id'  => 'required|exists:dokters,id',
            'hari'       => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required'
        ]);

        JadwalDokter::create($request->all());
        return redirect()->back()->with('sukses', 'Jadwal jaga berhasil ditambahkan.');
    }

    public function destroyJadwal($id)
    {
        JadwalDokter::findOrFail($id)->delete();
        return redirect()->back()->with('sukses', 'Jadwal berhasil dihapus.');
    }
}