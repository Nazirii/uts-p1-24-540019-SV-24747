<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Aman dijalankan berulang (idempotent) karena dipanggil setiap container backend start.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@agenda.test'],
            ['name' => 'Admin Agenda', 'password' => 'password123']
        );

        if (Agenda::count() === 0) {
            Agenda::create([
                'judul'      => 'Rapat Koordinasi UTS',
                'tanggal'    => '2026-10-27',
                'waktu'      => '09:00',
                'lokasi'     => 'Gedung Herman Yohanes',
                'keterangan' => 'Contoh data awal dari seeder.',
            ]);
        }
    }
}
