<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSidang;

class KategoriSidangSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Sidang Proposal',
                'kode' => 'SP',
                'deskripsi' => 'Sidang untuk presentasi proposal penelitian atau tugas akhir',
                'dokumen_wajib' => [
                    'Draft Proposal',
                    'Lembar Persetujuan Pembimbing',
                    'KRS',
                    'Transkrip Nilai'
                ]
            ],
            [
                'nama' => 'Seminar Hasil',
                'kode' => 'SH',
                'deskripsi' => 'Seminar untuk presentasi hasil penelitian',
                'dokumen_wajib' => [
                    'Draft Laporan Hasil',
                    'Lembar Persetujuan Pembimbing',
                    'Bukti Publikasi (jika ada)'
                ]
            ],
            [
                'nama' => 'Sidang Akhir',
                'kode' => 'SA',
                'deskripsi' => 'Sidang ujian akhir tugas akhir/skripsi/tesis',
                'dokumen_wajib' => [
                    'Laporan Final',
                    'Lembar Persetujuan Pembimbing',
                    'Lembar Revisi Seminar Hasil',
                    'Bebas Pustaka',
                    'Bukti Publikasi',
                    'TOEFL/Sertifikat Bahasa',
                    'Transkrip Nilai'
                ]
            ],
            [
                'nama' => 'Ujian Komprehensif',
                'kode' => 'UK',
                'deskripsi' => 'Ujian komprehensif untuk program pascasarjana',
                'dokumen_wajib' => [
                    'KRS',
                    'Transkrip Nilai',
                    'Lembar Persetujuan Pembimbing'
                ]
            ],
        ];

        foreach ($kategori as $k) {
            KategoriSidang::create($k);
        }
    }
}