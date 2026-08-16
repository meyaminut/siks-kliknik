<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIKS KLINIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen">

    <!-- NAVBAR HEADER -->
    <nav class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-bold text-blue-400">SIKS KLINIK</h1>
            <span class="bg-purple-600 text-xs px-2 py-1 rounded-full font-semibold">ADMIN</span>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition">
                Logout
            </button>
        </form>
    </nav>

    <div class="container mx-auto px-6 py-8">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 mb-8 max-w-lg mx-auto shadow-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Selamat datang di Dashboard Admin</h2>
            <div class="space-y-2 text-slate-300">
                <div class="flex justify-between border-b border-slate-700 pb-2">
                    <span class="text-slate-400">Nama:</span>
                    <span class="font-medium text-white">{{ Auth::user()->name ?? 'Admin Utama' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Username:</span>
                    <span class="font-medium text-white">{{ Auth::user()->username ?? 'admin1' }}</span>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-slate-200 mb-4 text-center">Menu Kelola Master Data</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
 
            <a href="{{ route('admin.dokter.index') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-blue-500 rounded-xl p-6 text-center transition group shadow-lg">
                <div class="text-3xl mb-3"></div>
                <h4 class="text-lg font-bold text-white group-hover:text-blue-400">Kelola Dokter</h4>
                <p class="text-xs text-slate-400 mt-2">Tambah, ubah, hapus data dokter & atur tarif konsultasi</p>
            </a>

            <a href="{{ route('admin.obat.index') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-emerald-500 rounded-xl p-6 text-center transition group shadow-lg">
                <div class="text-3xl mb-3"></div>
                <h4 class="text-lg font-bold text-white group-hover:text-emerald-400">Inventaris Obat</h4>
                <p class="text-xs text-slate-400 mt-2">Kelola master obat, stok, dan atur harga obat</p>
            </a>

            <a href="{{ route('admin.jadwal.index') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-purple-500 rounded-xl p-6 text-center transition group shadow-lg">
                <div class="text-3xl mb-3"></div>
                <h4 class="text-lg font-bold text-white group-hover:text-purple-400">Jadwal Jaga</h4>
                <p class="text-xs text-slate-400 mt-2">Atur jadwal jaga harian dokter klinik</p>
            </a>
        </div>
    </div>

</body>
</html>

<script>
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };
</script>