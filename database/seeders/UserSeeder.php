<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            // admin
            ['name' => 'Admin Utama', 'username' => 'admin1', 'password' => $password, 'role' => 'admin', 'no_hp' => '081234567801', 'jenis_kelamin' => 'L'],
            ['name' => 'Admin Staf 1', 'username' => 'admin2', 'password' => $password, 'role' => 'admin', 'no_hp' => '081234567802', 'jenis_kelamin' => 'P'],
            ['name' => 'Admin Staf 2', 'username' => 'admin3', 'password' => $password, 'role' => 'admin', 'no_hp' => '081234567803', 'jenis_kelamin' => 'L'],

            // dokter 
            ['name' => 'Dr. Andi Pratama', 'username' => 'dokter1', 'password' => $password, 'role' => 'dokter', 'no_hp' => '081234567804', 'jenis_kelamin' => 'L', 'spesialisasi' => 'Dokter Umum', 'tarif' => 100000],
            ['name' => 'Dr. Siti Rahma', 'username' => 'dokter2', 'password' => $password, 'role' => 'dokter', 'no_hp' => '081234567805', 'jenis_kelamin' => 'P', 'spesialisasi' => 'Spesialis Anak', 'tarif' => 150000],
            ['name' => 'Dr. Budi Santoso', 'username' => 'dokter3', 'password' => $password, 'role' => 'dokter', 'no_hp' => '081234567806', 'jenis_kelamin' => 'L', 'spesialisasi' => 'Spesialis Gigi', 'tarif' => 120000],

            // apoteker
            ['name' => 'Apoteker Rina', 'username' => 'apoteker1', 'password' => $password, 'role' => 'apoteker', 'no_hp' => '081234567807', 'jenis_kelamin' => 'P'],
            ['name' => 'Apoteker Dewi', 'username' => 'apoteker2', 'password' => $password, 'role' => 'apoteker', 'no_hp' => '081234567808', 'jenis_kelamin' => 'P'],
            ['name' => 'Apoteker Hendra', 'username' => 'apoteker3', 'password' => $password, 'role' => 'apoteker', 'no_hp' => '081234567809', 'jenis_kelamin' => 'L'],

            // owner
            ['name' => 'Owner Utama', 'username' => 'owner1', 'password' => $password, 'role' => 'owner', 'no_hp' => '081234567810', 'jenis_kelamin' => 'L'],
            ['name' => 'Co-Owner 1', 'username' => 'owner2', 'password' => $password, 'role' => 'owner', 'no_hp' => '081234567811', 'jenis_kelamin' => 'P'],
            ['name' => 'Co-Owner 2', 'username' => 'owner3', 'password' => $password, 'role' => 'owner', 'no_hp' => '081234567812', 'jenis_kelamin' => 'L'],

            // pasien
            ['name' => 'Pasien Budi', 'username' => 'pasien1', 'password' => $password, 'role' => 'pasien', 'no_hp' => '081234567813', 'jenis_kelamin' => 'L'],
            ['name' => 'Pasien Ani', 'username' => 'pasien2', 'password' => $password, 'role' => 'pasien', 'no_hp' => '081234567814', 'jenis_kelamin' => 'P'],
            ['name' => 'Pasien Cici', 'username' => 'pasien3', 'password' => $password, 'role' => 'pasien', 'no_hp' => '081234567815', 'jenis_kelamin' => 'P'],
        ];

        foreach ($users as $userData) {
            $spesialisasi = $userData['spesialisasi'] ?? null;
            $tarif = $userData['tarif'] ?? null;
            unset($userData['spesialisasi'], $userData['tarif']);

            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                $userData
            );

            if ($user->role === 'dokter') {
                $dokter = Dokter::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_dokter' => $user->name,
                        'spesialisasi' => $spesialisasi ?? 'Dokter Umum',
                        'tarif' => $tarif ?? 100000,
                    ]
                );

                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                foreach ($hariList as $hari) {
                    \App\Models\JadwalDokter::firstOrCreate([
                        'dokter_id'  => $dokter->id,
                        'hari'       => $hari,
                    ], [
                        'jam_mulai'   => '08:00:00',
                        'jam_selesai' => '16:00:00',
                    ]);
                }
            }
        }
    }
}