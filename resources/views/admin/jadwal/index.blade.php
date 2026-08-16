<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Jaga Dokter - SIKS KLINIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-purple-400">Pengaturan Jadwal Jaga</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg text-sm">← Kembali ke Dashboard</a>
        </div>

        @if(session('sukses'))
            <div class="bg-purple-600 text-white p-4 rounded-lg mb-6">{{ session('sukses') }}</div>
        @endif

        <!-- Form Tambah Jadwal -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 mb-8">
            <h2 class="text-lg font-bold mb-4">Buat Jadwal Jaga Baru</h2>
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <select name="dokter_id" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                    <option value="">Pilih Dokter Jaga </option>
                    @foreach($dokters as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_dokter }} ({{ $d->spesialisasi }})</option>
                    @endforeach
                </select>

                <select name="hari" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                    <option value="">Pilih Hari </option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>

                <div class="flex flex-col">
                    <label class="text-xs text-slate-400 mb-1">Jam Mulai Jaga</label>
                    <input type="time" name="jam_mulai" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-slate-400 mb-1">Jam Selesai Jaga</label>
                    <input type="time" name="jam_selesai" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                </div>

                <button type="submit" class="md:col-span-4 bg-purple-600 hover:bg-purple-700 p-2 rounded-lg text-sm font-bold">Simpan Jadwal</button>
            </form>
        </div>

        <!-- Tabel Jadwal -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-700 text-slate-200">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Dokter</th>
                        <th class="p-4">Hari</th>
                        <th class="p-4">Jam Jaga</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $index => $j)
                        <tr class="border-b border-slate-700 hover:bg-slate-750">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 font-semibold text-white">{{ $j->dokter->nama_dokter ?? 'Dokter Terhapus' }}</td>
                            <td class="p-4"><span class="bg-slate-700 px-2 py-1 rounded text-xs">{{ $j->hari }}</span></td>
                            <td class="p-4">{{ $j->jam_mulai }} - {{ $j->jam_selesai }} WIB</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 px-3 py-1 rounded text-xs text-white">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-400">Belum ada jadwal jaga disetting.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>