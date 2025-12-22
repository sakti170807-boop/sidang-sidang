<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengujiSidang;
use App\Models\JadwalSidang;
use App\Models\User;

class PengujiSidangSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil jadwal pertama
        $jadwal = JadwalSidang::first();
        
        if (!$jadwal) {
            $this->command->warn('Tidak ada jadwal sidang. Buat jadwal terlebih dahulu.');
            return;
        }

        // Ambil dosen-dosen
        $dosens = User::where('role', 'dosen')->limit(3)->get();

        if ($dosens->count() < 3) {
            $this->command->warn('Tidak cukup dosen. Minimal butuh 3 dosen.');
            return;
        }

        // Tambahkan penguji
        PengujiSidang::create([
            'jadwal_sidang_id' => $jadwal->id,
            'dosen_id' => $dosens[0]->id,
            'peran' => 'ketua',
            'status' => 'confirmed'
        ]);

        PengujiSidang::create([
            'jadwal_sidang_id' => $jadwal->id,
            'dosen_id' => $dosens[1]->id,
            'peran' => 'sekretaris',
            'status' => 'confirmed'
        ]);

        PengujiSidang::create([
            'jadwal_sidang_id' => $jadwal->id,
            'dosen_id' => $dosens[2]->id,
            'peran' => 'anggota',
            'status' => 'confirmed'
        ]);

        // Update status jadwal
        $jadwal->update(['status' => 'completed']);

        $this->command->info('Penguji sidang berhasil ditambahkan!');
    }
}