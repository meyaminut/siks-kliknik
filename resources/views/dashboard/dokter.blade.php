<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - SIKS KLINIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen p-4 md:p-8" x-data="{ openEditProfil: false }">

    <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Header Dashboard Dokter -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <div>
                <span class="bg-blue-500/10 text-blue-400 border border-blue-500/30 text-xs font-semibold px-3 py-1 rounded-full uppercase">
                    Portal Medis Dokter
                </span>
                <h1 class="text-2xl font-bold text-white mt-2">
                    Selamat Datang, <span class="text-blue-400">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">Kelola data profil medis, antrean pasien real-time, dan jadwal jaga Anda.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button 
                    @click="openEditProfil = true" 
                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-4 py-2.5 rounded-xl text-xs transition shadow-lg">
                    Edit Profil Dokter
                </button>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2.5 rounded-xl text-xs transition shadow-lg">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert Notifikasi -->
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

        <!-- Grid Info & Profil -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profil Card -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl relative overflow-hidden space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Profil Medis Saya</h3>
                
                <div>
                    <p class="text-xs text-slate-400">Nama Lengkap & Gelar</p>
                    <p class="text-base font-bold text-white mt-0.5">{{ auth()->user()->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">Spesialisasi</p>
                    <span class="inline-block bg-blue-600/20 text-blue-400 border border-blue-500/30 text-xs font-semibold px-3 py-1 rounded-lg mt-1">
                        {{ $dokter->spesialisasi ?? auth()->user()->dokter->spesialisasi ?? 'Belum diatur' }}
                    </span>
                </div>

                <div>
                    <p class="text-xs text-slate-400">Tarif Konsultasi</p>
                    <p class="text-sm font-semibold text-emerald-400 mt-0.5">
                        Rp {{ number_format($dokter->tarif ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Workspace Info -->
            <div class="md:col-span-2 bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-200">Klinik SIKS Digital Workspace</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                        Sistem antrean medis real-time. Pasien yang mengambil tiket antrean hari ini untuk ruang periksa Anda akan otomatis muncul pada tabel daftar antrean di bawah.
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-700/60 mt-4 flex items-center justify-between text-xs text-slate-400">
                    <span>Status Jaga: <strong class="text-emerald-400">Aktif</strong></span>
                    <span>HARI INI: {{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- TABEL ANTREAN PASIEN REAL-TIME (FILTER: ID DOKTER LOGIN & STATUS 'menunggu_dokter') -->
        <div class="bg-slate-800 border-2 border-amber-500/40 rounded-2xl overflow-hidden shadow-2xl space-y-4 p-6 relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 border-b border-slate-700 pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            Real-time Queue
                        </span>
                        <span class="text-xs text-slate-400">Total Menunggu: <strong class="text-amber-400 font-bold">{{ isset($antrianPasien) ? $antrianPasien->count() : 0 }}</strong> Pasien</span>
                    </div>
                    <h2 class="text-lg font-bold text-amber-400 mt-2 flex items-center gap-2">
                        <span></span> Daftar Pasien Menunggu di Ruang Dokter
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar pasien yang di-filter berdasarkan ID Dokter login Anda dengan status <strong>menunggu_dokter</strong> hari ini.</p>
                </div>
                <button onclick="window.location.reload()" class="bg-slate-700 hover:bg-slate-600 text-xs font-semibold px-3 py-2 rounded-xl text-slate-200 transition">
                    Refresh Antrean
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-700/60 uppercase text-xs text-slate-300">
                        <tr>
                            <th class="p-3.5">No. Antrian</th>
                            <th class="p-3.5">Nama Pasien</th>
                            <th class="p-3.5">Kontak / No HP</th>
                            <th class="p-3.5">Status Data</th>
                            <th class="p-3.5 text-center">Aksi State Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @if(isset($antrianPasien) && $antrianPasien->count() > 0)
                            @foreach($antrianPasien as $antrian)
                                <tr class="hover:bg-slate-700/40 transition">
                                    <td class="p-3.5">
                                        <span class="text-xl font-extrabold font-mono text-amber-400 bg-amber-500/10 px-3 py-1 rounded-xl border border-amber-500/30">
                                            #{{ sprintf('%03d', $antrian->nomor_antrian) }}
                                        </span>
                                    </td>
                                    <td class="p-3.5">
                                        <strong class="text-white block">{{ $antrian->pasien->name ?? 'Pasien' }}</strong>
                                        <span class="text-xs text-slate-400">@ {{ $antrian->pasien->username ?? '-' }}</span>
                                    </td>
                                    <td class="p-3.5 text-xs text-slate-300 font-mono">
                                        {{ $antrian->pasien->no_hp ?? '-' }}
                                    </td>
                                    <td class="p-3.5">
                                        <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider inline-block">
                                            {{ $antrian->status }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ubah State ke sedang_diperiksa -->
                                            <form action="{{ route('dokter.antrian.update', $antrian->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="sedang_diperiksa">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition shadow">
                                                    🩺 Periksa
                                                </button>
                                            </form>

                                            <!-- Ubah State ke selesai -->
                                            <form action="{{ route('dokter.antrian.update', $antrian->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition shadow">
                                                    Selesai
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400 text-xs">
                                    <div class="space-y-1">
                                        <p class="text-2xl"></p>
                                        <p class="font-semibold text-slate-300">Belum ada pasien yang sedang menunggu antrean di ruangan Anda.</p>
                                        <p class="text-slate-500">Pasien yang menekan tombol "Ambil Antrian" hari ini akan muncul di sini secara otomatis.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Jadwal Jaga Dokter -->
        <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-xl space-y-4 p-6">
            <div class="flex justify-between items-center border-b border-slate-700 pb-4">
                <div>
                    <h2 class="text-base font-bold text-blue-400">Jadwal Jaga Saya</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar jadwal tugas jaga klinik yang disusun oleh Admin.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-700/60 uppercase text-xs text-slate-300">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Hari Jaga</th>
                            <th class="p-3">Jam Mulai</th>
                            <th class="p-3">Jam Selesai</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @if(isset($dokter) && $dokter->jadwal && $dokter->jadwal->count() > 0)
                            @foreach($dokter->jadwal as $index => $j)
                                <tr class="hover:bg-slate-700/30 transition">
                                    <td class="p-3 font-medium text-slate-400">{{ $index + 1 }}</td>
                                    <td class="p-3 font-semibold text-white">{{ $j->hari }}</td>
                                    <td class="p-3 text-blue-400 font-mono">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} WIB</td>
                                    <td class="p-3 text-blue-400 font-mono">{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB</td>
                                    <td class="p-3 text-center">
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            Terjadwal
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400 text-xs">
                                    Belum ada jadwal jaga yang diset oleh Admin untuk Anda.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Edit Profil Dokter -->
        <div x-show="openEditProfil" 
             class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             x-cloak>
            
            <div class="bg-slate-800 border border-slate-700 w-full max-w-lg rounded-2xl p-6 shadow-2xl relative"
                 @click.away="openEditProfil = false">
                
                <div class="flex justify-between items-center border-b border-slate-700 pb-4 mb-5">
                    <h3 class="font-bold text-amber-400 text-base">Ubah Profil Dokter</h3>
                    <button @click="openEditProfil = false" class="text-slate-400 hover:text-white text-lg font-bold">✕</button>
                </div>

                <form action="/dokter/profil" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Lengkap & Gelar</label>
                        <input 
                            type="text" 
                            name="nama_dokter" 
                            value="{{ old('nama_dokter', auth()->user()->name) }}" 
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-amber-500" 
                            required>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Spesialisasi</label>
                        <input 
                            type="text" 
                            name="spesialisasi" 
                            value="{{ old('spesialisasi', $dokter->spesialisasi ?? auth()->user()->dokter->spesialisasi ?? '') }}" 
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-amber-500" 
                            required>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-700/60 mt-6">
                        <button type="button" @click="openEditProfil = false" class="bg-slate-700 px-4 py-2 rounded-xl text-xs text-white">Batal</button>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 px-5 py-2 rounded-xl text-xs font-bold text-white shadow-lg">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
</html>