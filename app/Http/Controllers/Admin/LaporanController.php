<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\JadwalSidang;
use App\Models\KategoriSidang;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanSidangExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Stats
        $stats = [
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'total_pendaftaran' => Pendaftaran::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_sidang' => JadwalSidang::whereBetween('tanggal_mulai', [$startDate, $endDate])->count(),
            'sidang_selesai' => JadwalSidang::whereBetween('tanggal_mulai', [$startDate, $endDate])
                ->whereIn('status', ['completed', 'selesai'])
                ->count(),
        ];

        // Laporan per kategori
        $laporanKategori = KategoriSidang::withCount([
            'pendaftaran' => function($q) use ($startDate, $endDate) {
                $q->whereHas('jadwal', function($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate]);
                });
            }
        ])
        ->get()
        ->map(function($item) {
            return (object)[
                'nama' => $item->nama,
                'total' => $item->pendaftaran_count
            ];
        });

        // Dosen aktif
        $dosenAktif = User::where('role', 'dosen')
            ->withCount([
                'pengujiSidang' => function($q) use ($startDate, $endDate) {
                    $q->whereHas('jadwalSidang', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal_mulai', [$startDate, $endDate]);
                    });
                }
            ])
            ->having('penguji_sidang_count', '>', 0)
            ->orderByDesc('penguji_sidang_count')
            ->take(10)
            ->get()
            ->map(function($dosen) {
                return (object)[
                    'name' => $dosen->name,
                    'total_sidang' => $dosen->penguji_sidang_count
                ];
            });

        // Mahasiswa per prodi
        $mahasiswaPerProdi = ProgramStudi::withCount('mahasiswa')
            ->get()
            ->map(function($prodi) {
                return (object)[
                    'nama' => $prodi->nama,
                    'total' => $prodi->mahasiswa_count
                ];
            });

        return view('admin.laporan.index', compact(
            'stats',
            'laporanKategori',
            'dosenAktif',
            'mahasiswaPerProdi',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Ambil data jadwal dengan relasi lengkap
        $jadwals = JadwalSidang::with([
            'pendaftaran.mahasiswa',
            'pendaftaran.kategoriSidang',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ])
        ->whereBetween('tanggal_mulai', [$startDate, $endDate])
        ->orderBy('tanggal_mulai', 'asc')
        ->get();

        // Load view untuk PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('jadwals', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape'); // Set landscape untuk tabel lebar

        // PENTING: Download langsung, bukan stream
        return $pdf->download('laporan-sidang-' . $startDate . '-' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $export = new LaporanSidangExport($startDate, $endDate);
        return $export->download('laporan-sidang-' . $startDate . '-' . $endDate . '.xlsx');
    }
}