<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ApotekerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DokterAdminController;

Route::get('/', function () {
    if (Auth::check()) {
        // jika sudah login di arahkan sesuai dengan role nya 
        $role = Auth::user()->role;
        return redirect()->route($role . '.dashboard');
    }

    return redirect()->route('login');
});

// route authen
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// route dashboard tiap role
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/dokter/dashboard', [DokterController::class, 'index'])->name('dokter.dashboard');
Route::get('/apoteker/dashboard', [ApotekerController::class, 'index'])->name('apoteker.dashboard');
Route::get('/owner/dashboard', [OwnerController::class, 'index'])->name('owner.dashboard');
Route::get('/pasien/dashboard', [PasienController::class, 'index'])->name('pasien.dashboard');

Route::prefix('admin')->group(function () {
    // Kelola Obat
    Route::get('/obat', [ObatController::class, 'index'])->name('admin.obat.index');
    Route::post('/obat', [ObatController::class, 'store'])->name('admin.obat.store');
    Route::put('/obat/{id}', [ObatController::class, 'update'])->name('admin.obat.update');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('admin.obat.destroy');

    // Kelola Dokter
    Route::get('/dokter', [DokterAdminController::class, 'indexDokter'])->name('admin.dokter.index');
    Route::post('/dokter', [DokterAdminController::class, 'storeDokter'])->name('admin.dokter.store');
    Route::put('/dokter/{id}', [DokterAdminController::class, 'updateDokter'])->name('admin.dokter.update');
    Route::delete('/dokter/{id}', [DokterAdminController::class, 'destroyDokter'])->name('admin.dokter.destroy');

    // Kelola Jadwal Jaga
    Route::get('/jadwal', [DokterAdminController::class, 'indexJadwal'])->name('admin.jadwal.index');
    Route::post('/jadwal', [DokterAdminController::class, 'storeJadwal'])->name('admin.jadwal.store');
    Route::delete('/jadwal/{id}', [DokterAdminController::class, 'destroyJadwal'])->name('admin.jadwal.destroy');
});

Route::post('/dokter/profil', [DokterController::class, 'updateProfil'])->name('dokter.updateProfil');
Route::put('/admin/dokter/{id}', [DokterAdminController::class, 'updateDokter'])->name('admin.dokter.update');

// Route Antrian Pasien & Dokter
Route::post('/pasien/antrian', [PasienController::class, 'ambilAntrian'])->name('pasien.antrian.store');
Route::put('/dokter/antrian/{id}', [DokterController::class, 'updateStatusAntrian'])->name('dokter.antrian.update');