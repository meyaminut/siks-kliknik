<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dokter - Admin SIKS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen p-4 md:p-8" x-data="{ openEdit: false, editData: {} }">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
            <div>
                <h1 class="text-2xl font-bold text-blue-400">Manajemen Data Dokter</h1>
                <p class="text-xs text-slate-400 mt-1">Tambah, kelola tarif, dan hapus data dokter klinik.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition">← Dashboard</a>
        </div>

        @if(session('sukses'))
            <div class="bg-emerald-600/90 border border-emerald-500 text-white p-4 rounded-xl shadow-lg flex items-center justify-between">
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        <!-- Form Tambah Dokter -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl space-y-4">
            <h2 class="text-sm font-bold text-slate-200">Tambah Dokter Baru</h2>
            
            <form action="{{ route('admin.dokter.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <input type="text" name="nama_dokter" placeholder="Nama Dokter & Gelar" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white focus:border-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="text" name="spesialisasi" placeholder="Spesialisasi" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white focus:border-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="number" name="tarif" placeholder="Tarif Konsultasi (Rp)" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white focus:border-blue-500 focus:outline-none" required>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm transition">Simpan Data Dokter</button>
                </div>
            </form>
        </div>

        <!-- Tabel Dokter -->
        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-700/60 uppercase text-xs text-slate-300">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Dokter</th>
                        <th class="p-4">Spesialisasi</th>
                        <th class="p-4">Tarif</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($dokters as $index => $dokter)
                        <tr class="hover:bg-slate-700/30 transition">
                            <td class="p-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-4 font-semibold text-white">{{ $dokter->nama_dokter }}</td>
                            <td class="p-4 text-slate-300">{{ $dokter->spesialisasi }}</td>
                            <td class="p-4 text-emerald-400 font-medium">Rp {{ number_format($dokter->tarif, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEdit = true; editData = { id: '{{ $dokter->id }}', nama: '{{ addslashes($dokter->nama_dokter) }}', spesialisasi: '{{ addslashes($dokter->spesialisasi) }}', tarif: '{{ $dokter->tarif }}' }" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">✏️ Ubah / Tarif</button>
                                    
                                    <form action="{{ route('admin.dokter.destroy', $dokter->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data dokter ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400">Belum ada data dokter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Edit -->
        <div x-show="openEdit" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
            <div class="bg-slate-800 border border-slate-700 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
                <h3 class="font-bold text-amber-400 text-base">Edit Dokter & Tarif</h3>
                <form :action="'/admin/dokter/' + editData.id" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Nama Dokter & Gelar</label>
                        <input type="text" name="nama_dokter" x-model="editData.nama" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white" required>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Spesialisasi</label>
                        <input type="text" name="spesialisasi" x-model="editData.spesialisasi" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white" required>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Tarif Konsultasi (Rp)</label>
                        <input type="number" name="tarif" x-model="editData.tarif" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-sm text-white" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="openEdit = false" class="bg-slate-700 px-3 py-1.5 rounded-lg text-xs text-white">Batal</button>
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 px-4 py-1.5 rounded-lg text-xs font-bold text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>
</html>