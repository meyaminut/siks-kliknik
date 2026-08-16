<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalDokter;
use App\Models\Kunjungan;
use Carbon\Carbon;

class PasienController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role != 'pasien') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        // Map nama hari ke bahasa Indonesia
        $daysMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hariIni = $daysMap[Carbon::now()->format('l')];

        // Tampilkan jadwal dokter khusus hari ini saja
        $jadwalHariIni = JadwalDokter::with('dokter')
            ->where('hari', $hariIni)
            ->get();

        // Cek apakah pasien punya tiket aktif (status menunggu_dokter atau sedang_diperiksa) hari ini
        $tiketAktif = Kunjungan::with('dokter')
            ->where('pasien_id', Auth::id())
            ->where('tanggal_kunjungan', Carbon::now()->toDateString())
            ->whereIn('status', ['menunggu_dokter', 'sedang_diperiksa'])
            ->first();

        // Riwayat kunjungan pasien
        $riwayatKunjungan = Kunjungan::with('dokter')
            ->where('pasien_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.pasien', compact('hariIni', 'jadwalHariIni', 'tiketAktif', 'riwayatKunjungan'));
    }

    public function ambilAntrian(Request $request)
    {
        if (!Auth::check() || Auth::user()->role != 'pasien') {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'keluhan'   => 'nullable|string|max:500',
        ]);

        $hariIniTanggal = Carbon::now()->toDateString();

        // Cek apakah pasien sudah mengambil antrian hari ini yang masih aktif
        $existingTicket = Kunjungan::where('pasien_id', Auth::id())
            ->where('tanggal_kunjungan', $hariIniTanggal)
            ->whereIn('status', ['menunggu_dokter', 'sedang_diperiksa'])
            ->first();

        if ($existingTicket) {
            return back()->with('error', 'Anda sudah mengambil antrean untuk hari ini dan sedang menunggu panggilan.');
        }

        // Hitung urutan pasien terakhir pada hari dan dokter tersebut (+1)
        $lastQueue = Kunjungan::where('dokter_id', $request->dokter_id)
            ->where('tanggal_kunjungan', $hariIniTanggal)
            ->max('nomor_antrian');

        $nextQueueNumber = ($lastQueue ? $lastQueue : 0) + 1;

        // Buat tiket kunjungan baru dengan status menunggu_dokter
        Kunjungan::create([
            'pasien_id'         => Auth::id(),
            'dokter_id'         => $request->dokter_id,
            'tanggal_kunjungan' => $hariIniTanggal,
            'nomor_antrian'     => $nextQueueNumber,
            'status'            => 'menunggu_dokter',
            'keluhan'           => $request->keluhan,
        ]);

        return back()->with('sukses', 'Tiket antrean berhasil diambil! Nomor antrean Anda: #' . sprintf('%03d', $nextQueueNumber));
    }
}