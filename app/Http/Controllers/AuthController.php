<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        // ini logika kalo udah login langsung di arahin k role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return response()
        ->view('auth.login') // sesuaikan dengan nama file view login kamu
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }

    /**
     * Memproses otentikasi login.
     */
    public function login(Request $request)
    {
        // Custom Error Messages dalam Bahasa Indonesia
        $messages = [
            'username.required' => 'Username/Email tidak boleh kosong.',
            'username.min'      => 'Username minimal harus 4 karakter.',
            'username.regex'    => 'Username memuat karakter simbol/kode yang tidak diizinkan.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min'      => 'Password minimal harus 6 karakter.',
        ];

        // Rules Validasi Server
        $request->validate([
            // regex: hanya boleh huruf, angka, titik, underscore, dan @ (jika pakai email)
            'username' => ['required', 'min:4', 'regex:/^[a-zA-Z0-9._@-]+$/', $messages],
            'password' => ['required', 'min:6'],
        ], $messages);

        // Proses Autentikasi
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dokter.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Helper untuk mengarahkan halaman berdasarkan role user.
     */
    private function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'dokter':
                return redirect()->route('dokter.dashboard');
            case 'apoteker':
                return redirect()->route('apoteker.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'pasien':
                return redirect()->route('pasien.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role pengguna tidak valid.');
        }
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}