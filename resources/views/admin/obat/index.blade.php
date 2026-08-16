<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inventaris Obat - SIKS KLINIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen p-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-emerald-400">Inventaris Obat</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg text-sm">← Kembali ke Dashboard</a>
        </div>

        @if(session('sukses'))
            <div class="bg-emerald-600 text-white p-4 rounded-lg mb-6">{{ session('sukses') }}</div>
        @endif

        <!-- Form Tambah Obat -->
        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700 mb-8">
            <h2 class="text-lg font-bold mb-4">Tambah Obat Baru</h2>
            <form action="{{ route('admin.obat.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <input type="text" name="nama_obat" placeholder="Nama Obat" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                <input type="number" name="harga" placeholder="Harga (Rp)" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                <input type="number" name="stok" placeholder="Jumlah Stok" class="bg-slate-900 border border-slate-700 rounded-lg p-2 text-sm text-white" required>
                <button type="submit" class="md:col-span-3 bg-emerald-600 hover:bg-emerald-700 p-2 rounded-lg text-sm font-bold">Simpan Obat</button>
            </form>
        </div>

        <!-- Tabel Data Obat -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-700 text-slate-200">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Obat</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obats as $index => $obat)
                        <tr class="border-b border-slate-700 hover:bg-slate-750">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 font-semibold text-white">{{ $obat->nama_obat }}</td>
                            <td class="p-4">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td class="p-4">{{ $obat->stok }} pcs</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.obat.destroy', $obat->id) }}" method="POST" onsubmit="return confirm('Yakin hapus obat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 px-3 py-1 rounded text-xs text-white">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-400">Belum ada data obat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>