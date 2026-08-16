<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - SIKS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col">
    <!-- Topbar / Navbar -->
    <nav class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <h1 class="font-bold text-lg text-sky-400">SIKS KLINIK</h1>
            @yield('badge')
        </div>
        <form action="/logout" method="POST">
            @csrf
            <button class="bg-rose-500/20 text-rose-400 border border-rose-500/30 px-4 py-1.5 rounded-lg text-sm hover:bg-rose-500 hover:text-white transition">
                Logout
            </button>
        </form>
    </nav>

    <!-- Main Content Dynamic -->
    <main class="p-8 flex-1 flex items-center justify-center">
        @yield('content')
    </main>
</body>
</html>