<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIKS KLINIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk fitur Toggle Password Mata -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-800 border border-slate-700 p-8 rounded-2xl shadow-2xl">
        
        <!-- Header Login -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-400">SIKS KLINIK</h1>
            <p class="text-xs text-slate-400 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Alert Error Umum -->
        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/50 text-rose-400 p-3.5 rounded-xl text-xs mb-5 space-y-1">
                <p class="font-bold">Terjadi Kesalahan Input:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPassword: false }">
            @csrf

            <!-- Field Username / Email -->
            <div>
                <label class="block text-xs text-slate-400 mb-1.5 font-medium">Username / Email</label>
                <input 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}" 
                    placeholder="Masukkan username atau email" 
                    class="w-full bg-slate-900 border @error('username') border-rose-500 @else border-slate-700 @enderror rounded-lg p-3 text-sm text-white focus:outline-none focus:border-blue-500 transition"
                >
                @error('username')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Password + Icon Mata -->
            <div>
                <label class="block text-xs text-slate-400 mb-1.5 font-medium">Password</label>
                <div class="relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        name="password" 
                        placeholder="••••••••" 
                        class="w-full bg-slate-900 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-lg p-3 pr-10 text-sm text-white focus:outline-none focus:border-blue-500 transition"
                    >
                    
                    <!-- Tombol Icon Mata (Hide/Unhide) -->
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition focus:outline-none">
                        
                        <!-- Icon Mata Terbuka (Unhide) -->
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Icon Mata Tertutup (Hide) -->
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.982 8.982 0 012.122-.063c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21f-3.23-3.23" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-sm shadow-lg transition mt-2">
                Masuk ke Sistem
            </button>
        </form>

    </div>

</body>
</html>