<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokter;
use App\Models\Kunjungan;
use Carbon\Carbon;

class DokterController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role != 'dokter') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        $dokter = Dokter::with('jadwal')->where('user_id', Auth::id())->first();

        $antrianPasien = collect();
        if ($dokter) {
            // Filter antrean pasien berdasarkan ID dokter yang login & status ('menunggu_dokter' atau 'sedang_diperiksa')
            $antrianPasien = Kunjungan::with('pasien')
                ->where('dokter_id', $dokter->id)
                ->whereIn('status', ['menunggu_dokter', 'sedang_diperiksa'])
                ->where('tanggal_kunjungan', Carbon::now()->toDateString())
                ->orderBy('nomor_antrian', 'asc')
                ->get();
        }

        return view('dashboard.dokter', compact('dokter', 'antrianPasien'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $user->update([
            'name' => $request->nama_dokter,
        ]);

        Dokter::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_dokter'  => $request->nama_dokter,
                'spesialisasi' => $request->spesialisasi,
            ]
        );

        return back()->with('sukses', 'Profil Dokter berhasil diperbarui!');
    }

    public function updateStatusAntrian(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role != 'dokter') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:sedang_diperiksa,selesai,batal',
        ]);

        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->update([
            'status' => $request->status,
        ]);

        return back()->with('sukses', 'Status pemeriksaan pasien berhasil diperbarui!');
    }
}