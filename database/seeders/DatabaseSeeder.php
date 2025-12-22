<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FakultasSeeder::class,
            ProgramStudiSeeder::class,
            UserSeeder::class,
            KategoriSidangSeeder::class,
            RuangSidangSeeder::class,
        ]);
    }
}

// database/seeders/FakultasSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $fakultas = [
            ['kode' => 'FT', 'nama' => 'Fakultas Teknik', 'deskripsi' => 'Fakultas yang menaungi program studi teknik'],
            ['kode' => 'FMIPA', 'nama' => 'Fakultas MIPA', 'deskripsi' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam'],
            ['kode' => 'FEB', 'nama' => 'Fakultas Ekonomi dan Bisnis', 'deskripsi' => 'Fakultas bidang ekonomi dan bisnis'],
            ['kode' => 'FH', 'nama' => 'Fakultas Hukum', 'deskripsi' => 'Fakultas ilmu hukum'],
        ];

        foreach ($fakultas as $f) {
            Fakultas::create($f);
        }
    }
}