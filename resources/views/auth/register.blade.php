<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien - SIKS Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-emerald-400">Pendaftaran Pasien</h1>
            <p class="text-sm text-slate-400 mt-1">Lengkapi data untuk membuat akun baru</p>
        </div>

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama Anda" 
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-sm text-slate-100 placeholder-slate-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Username</label>
                <input type="text" name="username" required placeholder="Username unik" 
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-sm text-slate-100 placeholder-slate-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-sm text-slate-100 placeholder-slate-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nomor HP</label>
                <input type="text" name="no_hp" required placeholder="08xxxxxxxxx" 
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-sm text-slate-100 placeholder-slate-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" required 
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-sm text-slate-100">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <button type="submit" 
                class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold rounded-xl transition duration-200 shadow-lg shadow-emerald-500/20">
                Daftar Akun
            </button>
        </form>

        <p class="text-center text-sm text-slate-400 mt-6">
            Sudah punya akun? <a href="/login" class="text-emerald-400 hover:underline font-medium">Login di sini</a>
        </p>
    </div>
</body>
</html>