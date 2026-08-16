@extends('layouts.app')
@section('title', 'Dashboard Pasien')
@section('badge')
    <span class="text-xs px-2.5 py-1 rounded-full uppercase font-bold bg-purple-500/20 text-purple-400 border border-purple-500/30">Pasien</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto w-full space-y-6">
    
    <!-- Welcome Header -->
    <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="bg-purple-500/10 text-purple-400 border border-purple-500/30 text-xs font-semibold px-3 py-1 rounded-full uppercase">
                Portal Pasien
            </span>
            <h2 class="text-2xl font-bold text-white mt-2">
                Selamat Datang, <span class="text-purple-400">{{ auth()->user()->name }}</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Layanan antrean otomatis & informasi jadwal dokter klinik.</p>
        </div>
        <div class="bg-slate-900 border border-slate-700/60 px-4 py-2.5 rounded-xl text-xs text-right">
            <span class="text-slate-400 block">Jadwal Hari Ini</span>
            <strong class="text-emerald-400 text-sm font-semibold">{{ $hariIni }}, {{ now()->translatedFormat('d F Y') }}</strong>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('sukses'))
        <div class="bg-emerald-600/90 border border-emerald-500 text-white p-4 rounded-2xl shadow-lg flex items-center justify-between">
            <span class="text-sm font-semibold">{{ session('sukses') }}</span>
            <button onclick="this.parentElement.remove()" class="text-sm font-bold opacity-75 hover:opacity-100">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-600/90 border border-rose-500 text-white p-4 rounded-2xl shadow-lg flex items-center justify-between">
            <span class="text-sm font-semibold">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-sm font-bold opacity-75 hover:opacity-100">✕</button>
        </div>
    @endif

    <!-- TIKET ANTREAN AKTIF PASIEN (Jika Ada Tiket Menunggu) -->
    @if(isset($tiketAktif) && $tiketAktif)
        <div class="bg-gradient-to-br from-slate-800 via-slate-800 to-indigo-950/60 border-2 border-amber-500/50 p-6 rounded-2xl shadow-2xl relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider animate-pulse">
                            Tiket Antrean Aktif
                        </span>
                        <span class="text-xs text-slate-400">Tanggal: {{ \Carbon\Carbon::parse($tiketAktif->tanggal_kunjungan)->format('d-m-Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-white">Tiket Anda Sedang Dalam Antrean</h3>
                    <p class="text-xs text-slate-300">
                        Dokter Tujuan: <strong class="text-sky-300">{{ $tiketAktif->dokter->nama_dokter ?? 'Dokter' }}</strong> 
                        ({{ $tiketAktif->dokter->spesialisasi ?? '-' }})
                    </p>
                    @if($tiketAktif->status == 'sedang_diperiksa')
                        <div class="bg-blue-900/80 border border-blue-500/40 p-3 rounded-xl text-xs text-blue-200 mt-2 flex items-center gap-2">
                            <span><strong>Instruksi:</strong> Anda sedang dipanggil! Silakan segera masuk ke dalam ruang periksa dokter.</span>
                        </div>
                    @else
                        <div class="bg-slate-900/80 border border-amber-500/30 p-3 rounded-xl text-xs text-amber-200 mt-2 flex items-center gap-2">
                            <span><strong>Instruksi:</strong> Silakan menanti panggilan petugas / dokter di ruang tunggu klinik. Pastikan selalu memperhatikan papan informasi panggilan.</span>
                        </div>
                    @endif
                </div>

                <div class="bg-slate-900/90 border border-amber-500/40 p-6 rounded-2xl text-center min-w-[180px] shadow-inner">
                    <span class="text-xs text-slate-400 uppercase font-semibold tracking-widest">Nomor Antrean</span>
                    <div class="text-4xl font-extrabold text-amber-400 my-1 font-mono tracking-tight">
                        #{{ sprintf('%03d', $tiketAktif->nomor_antrian) }}
                    </div>
                    @if($tiketAktif->status == 'sedang_diperiksa')
                        <span class="bg-blue-500/20 text-blue-300 border border-blue-500/40 text-[10px] font-bold px-2 py-0.5 rounded-full inline-block uppercase animate-pulse">
                            SEDANG DIPERIKSA
                        </span>
                    @else
                        <span class="bg-amber-500/10 text-amber-300 border border-amber-500/20 text-[10px] font-bold px-2 py-0.5 rounded-full inline-block uppercase">
                            MENUNGGU DOKTER
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- TABEL JADWAL DOKTER HARI INI -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex justify-between items-center border-b border-slate-700 pb-4">
            <div>
                <h3 class="text-lg font-bold text-sky-400 flex items-center gap-2">
                    <span></span> Jadwal Dokter Praktek Hari Ini ({{ $hariIni }})
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Pilih dokter yang tersedia hari ini dan klik tombol "Ambil Antrian" untuk mendapatkan nomor antrean tiket.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-700/60 uppercase text-xs text-slate-300">
                    <tr>
                        <th class="p-3.5">Nama Dokter</th>
                        <th class="p-3.5">Spesialisasi</th>
                        <th class="p-3.5">Jam Praktek</th>
                        <th class="p-3.5">Tarif</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                        @foreach($jadwalHariIni as $j)
                            <tr class="hover:bg-slate-700/40 transition">
                                <td class="p-3.5 font-bold text-white">
                                    {{ $j->dokter->nama_dokter ?? 'Dokter' }}
                                </td>
                                <td class="p-3.5">
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/30 text-xs px-2.5 py-1 rounded-lg">
                                        {{ $j->dokter->spesialisasi ?? 'Dokter Umum' }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-mono text-sky-400 text-xs">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB
                                </td>
                                <td class="p-3.5 text-emerald-400 font-semibold">
                                    Rp {{ number_format($j->dokter->tarif ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="p-3.5 text-center">
                                    @if(isset($tiketAktif) && $tiketAktif)
                                        <button disabled class="bg-slate-700 text-slate-500 cursor-not-allowed font-medium px-4 py-2 rounded-xl text-xs">
                                            Sedang Menunggu
                                        </button>
                                    @else
                                        <form action="{{ route('pasien.antrian.store') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="dokter_id" value="{{ $j->dokter_id }}">
                                            <button type="submit" 
                                                class="bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-lg transition duration-150">
                                                Ambil Antrian
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 text-xs">
                                Tidak ada dokter yang terjadwal praktek pada hari ini ({{ $hariIni }}).
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIWAYAT KUNJUNGAN PASIEN -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="border-b border-slate-700 pb-3">
            <h3 class="text-base font-bold text-slate-200">Riwayat Antrean & Kunjungan Anda</h3>
            <p class="text-xs text-slate-400 mt-0.5">Daftar riwayat tiket antrean yang pernah Anda ambil.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-700/60 uppercase text-xs text-slate-300">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Dokter</th>
                        <th class="p-3">No. Antrian</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @if(isset($riwayatKunjungan) && $riwayatKunjungan->count() > 0)
                        @foreach($riwayatKunjungan as $rk)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="p-3 text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($rk->tanggal_kunjungan)->format('d F Y') }}
                                </td>
                                <td class="p-3 font-medium text-white">
                                    {{ $rk->dokter->nama_dokter ?? '-' }}
                                </td>
                                <td class="p-3 font-mono font-bold text-amber-400">
                                    #{{ sprintf('%03d', $rk->nomor_antrian) }}
                                </td>
                                <td class="p-3 text-center">
                                    @if($rk->status == 'menunggu_dokter')
                                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            Menunggu Dokter
                                        </span>
                                    @elseif($rk->status == 'sedang_diperiksa')
                                        <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 text-xs px-2.5 py-1 rounded-full font-semibold animate-pulse">
                                            Sedang Diperiksa
                                        </span>
                                    @elseif($rk->status == 'selesai')
                                        <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="bg-slate-700 text-slate-400 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            {{ ucfirst($rk->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 text-xs">
                                Belum ada riwayat antrean.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection