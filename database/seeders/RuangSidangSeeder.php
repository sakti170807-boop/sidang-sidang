<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RuangSidang;

class RuangSidangSeeder extends Seeder
{
    public function run(): void
    {
        $ruang = [
            [
                'kode' => 'A301',
                'nama' => 'Ruang Sidang A301',
                'gedung' => 'Gedung A',
                'kapasitas' => 15,
                'lokasi' => 'Lantai 3',
                'memiliki_proyektor' => true,
                'support_online' => true,
                'link_virtual' => 'https://zoom.us/j/123456789',
            ],
            [
                'kode' => 'B201',
                'nama' => 'Ruang Sidang B201',
                'gedung' => 'Gedung B',
                'kapasitas' => 20,
                'lokasi' => 'Lantai 2',
                'memiliki_proyektor' => true,
                'support_online' => false,
            ],
            [
                'kode' => 'C101',
                'nama' => 'Ruang Sidang C101',
                'gedung' => 'Gedung C',
                'kapasitas' => 10,
                'lokasi' => 'Lantai 1',
                'memiliki_proyektor' => true,
                'support_online' => true,
                'link_virtual' => 'https://meet.google.com/abc-defg-hij',
            ],
            [
                'kode' => 'ONLINE',
                'nama' => 'Sidang Online',
                'gedung' => 'Virtual',
                'kapasitas' => 50,
                'lokasi' => 'Online',
                'memiliki_proyektor' => false,
                'support_online' => true,
                'link_virtual' => 'https://zoom.us/j/sidangonline',
            ],
        ];

        foreach ($ruang as $r) {
            RuangSidang::create($r);
        }
    }
}