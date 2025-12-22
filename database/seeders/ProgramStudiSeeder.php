<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = [
            // Fakultas Teknik (ID: 1)
            ['fakultas_id' => 1, 'kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kode' => 'TE', 'nama' => 'Teknik Elektro', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kode' => 'TM', 'nama' => 'Teknik Mesin', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kode' => 'TI-S2', 'nama' => 'Magister Teknik Informatika', 'jenjang' => 'S2'],
            
            // Fakultas MIPA (ID: 2)
            ['fakultas_id' => 2, 'kode' => 'MAT', 'nama' => 'Matematika', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kode' => 'FIS', 'nama' => 'Fisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kode' => 'KIM', 'nama' => 'Kimia', 'jenjang' => 'S1'],
            
            // Fakultas Ekonomi dan Bisnis (ID: 3)
            ['fakultas_id' => 3, 'kode' => 'AKT', 'nama' => 'Akuntansi', 'jenjang' => 'S1'],
            ['fakultas_id' => 3, 'kode' => 'MNJ', 'nama' => 'Manajemen', 'jenjang' => 'S1'],
            ['fakultas_id' => 3, 'kode' => 'EKO', 'nama' => 'Ekonomi Pembangunan', 'jenjang' => 'S1'],
            
            // Fakultas Hukum (ID: 4)
            ['fakultas_id' => 4, 'kode' => 'HKM', 'nama' => 'Ilmu Hukum', 'jenjang' => 'S1'],
            ['fakultas_id' => 4, 'kode' => 'HKM-S2', 'nama' => 'Magister Hukum', 'jenjang' => 'S2'],
        ];

        foreach ($prodi as $p) {
            ProgramStudi::create($p);
        }
    }
}