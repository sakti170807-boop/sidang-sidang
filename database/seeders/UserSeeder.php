<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sidang.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Dosen
        $dosens = [
            [
                'name' => 'Ahmad Yusuf',
                'email' => 'dosen1@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nip' => '198501012010011001',
                'nidn' => '0101018501',
                'program_studi_id' => 1,
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'S.Kom., M.Kom',
                'no_telp' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'dosen2@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nip' => '198702152011012001',
                'nidn' => '0215028701',
                'program_studi_id' => 1,
                'gelar_depan' => 'Prof.',
                'gelar_belakang' => 'S.T., M.T., Ph.D',
                'no_telp' => '081234567891',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'dosen3@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nip' => '198903202012011002',
                'nidn' => '0320038901',
                'program_studi_id' => 1,
                'gelar_belakang' => 'S.Kom., M.T',
                'no_telp' => '081234567892',
                'is_active' => true,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dosen4@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'nip' => '199005102013012001',
                'nidn' => '0510059001',
                'program_studi_id' => 2,
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'S.T., M.Sc',
                'no_telp' => '081234567893',
                'is_active' => true,
            ],
        ];

        foreach ($dosens as $d) {
            User::create($d);
        }

        // Mahasiswa
        $mahasiswas = [
            [
                'name' => 'Andi Pratama',
                'email' => 'mahasiswa1@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim' => '1901010001',
                'program_studi_id' => 1,
                'no_telp' => '082134567890',
                'is_active' => true,
            ],
            [
                'name' => 'Sari Wulandari',
                'email' => 'mahasiswa2@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim' => '1901010002',
                'program_studi_id' => 1,
                'no_telp' => '082134567891',
                'is_active' => true,
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'mahasiswa3@sidang.test',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim' => '1901010003',
                'program_studi_id' => 1,
                'no_telp' => '082134567892',
                'is_active' => true,
            ],
        ];

        foreach ($mahasiswas as $m) {
            User::create($m);
        }
    }
}