<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = auth()->id();
        
        // Query yang diperbaiki - menggunakan OR condition yang benar
        $query = JadwalSidang::where('status', 'completed')
            ->where(function($q) use ($dosenId) {
                // Sebagai penguji
                $q->whereHas('penguji', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
                // ATAU sebagai pembimbing
                $q->orWhereHas('pendaftaran.pembimbing', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
            })
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang',
                'pendaftaran.programStudi',
                'ruangSidang',
                'penguji.dosen',
                'pendaftaran.pembimbing',
                'penilaians'
            ]);

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('pendaftaran', function($q) use ($request) {
                $q->where('kategori_sidang_id', $request->kategori);
            });
        }

        $riwayats = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Data untuk filter - ambil dari semua jadwal completed
        $tahunList = JadwalSidang::where('status', 'completed')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Jika tidak ada data tahun, gunakan tahun sekarang
        if ($tahunList->isEmpty()) {
            $tahunList = collect([date('Y'), date('Y') - 1]);
        }

        $kategoris = \App\Models\KategoriSidang::where('is_active', true)->get();

        // Statistik
        $stats = [
            'total' => JadwalSidang::where('status', 'completed')
                ->where(function($q) use ($dosenId) {
                    $q->whereHas('penguji', function($query) use ($dosenId) {
                        $query->where('dosen_id', $dosenId);
                    })
                    ->orWhereHas('pendaftaran.pembimbing', function($query) use ($dosenId) {
                        $query->where('dosen_id', $dosenId);
                    });
                })->count(),
            
            'sebagai_penguji' => JadwalSidang::where('status', 'completed')
                ->whereHas('penguji', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                })->count(),
            
            'sebagai_pembimbing' => JadwalSidang::where('status', 'completed')
                ->whereHas('pendaftaran.pembimbing', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                })->count(),
        ];

        // Debug: Log untuk melihat data
        \Log::info('Riwayat Query Debug', [
            'dosen_id' => $dosenId,
            'total_completed' => JadwalSidang::where('status', 'completed')->count(),
            'found_records' => $riwayats->total(),
            'stats' => $stats
        ]);

        return view('dosen.riwayat.index', compact('riwayats', 'tahunList', 'kategoris', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        \Log::info('Entering exportPdf method');
        
        $dosenId = auth()->id();
        
        $query = JadwalSidang::where('status', 'completed')
            ->where(function($q) use ($dosenId) {
                $q->whereHas('penguji', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
                $q->orWhereHas('pendaftaran.pembimbing', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
            })
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang',
                'pendaftaran.programStudi',
                'ruangSidang',
                'penguji.dosen',
                'pendaftaran.pembimbing',
                'penilaians'
            ]);

        // Apply filters
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('pendaftaran', function($q) use ($request) {
                $q->where('kategori_sidang_id', $request->kategori);
            });
        }

        $riwayats = $query->orderBy('created_at', 'desc')->get();
        
        \Log::info('Found ' . $riwayats->count() . ' records for PDF export');

        $data = [
            'riwayats' => $riwayats,
            'title' => 'Riwayat Sidang',
            'date' => date('d/m/Y'),
            'filters' => $this->getFilters($request)
        ];

        $pdf = PDF::loadView('dosen.riwayat.pdf', $data);
        return $pdf->download('riwayat_sidang_' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        \Log::info('Entering exportExcel method');
        
        $dosenId = auth()->id();
        
        $query = JadwalSidang::where('status', 'completed')
            ->where(function($q) use ($dosenId) {
                $q->whereHas('penguji', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
                $q->orWhereHas('pendaftaran.pembimbing', function($query) use ($dosenId) {
                    $query->where('dosen_id', $dosenId);
                });
            })
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang',
                'pendaftaran.programStudi',
                'ruangSidang',
                'penguji.dosen',
                'pendaftaran.pembimbing',
                'penilaians'
            ]);

        // Apply filters
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('pendaftaran', function($q) use ($request) {
                $q->where('kategori_sidang_id', $request->kategori);
            });
        }

        $riwayats = $query->orderBy('created_at', 'desc')->get();
        
        \Log::info('Found ' . $riwayats->count() . ' records for Excel export');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator("Sistem Sidang")
            ->setLastModifiedBy("Sistem Sidang")
            ->setTitle("Riwayat Sidang")
            ->setSubject("Riwayat Sidang")
            ->setDescription("Riwayat Sidang Dosen");

        // Header
        $sheet->setCellValue('A1', 'LAPORAN RIWAYAT SIDANG');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Filter info
        $filters = $this->getFilters($request);
        $sheet->setCellValue('A2', 'Filter: ' . $filters);
        $sheet->mergeCells('A2:H2');

        // Column headers
        $sheet->setCellValue('A4', 'No')
            ->setCellValue('B4', 'Tanggal')
            ->setCellValue('C4', 'Mahasiswa')
            ->setCellValue('D4', 'NIM')
            ->setCellValue('E4', 'Judul')
            ->setCellValue('F4', 'Kategori')
            ->setCellValue('G4', 'Peran')
            ->setCellValue('H4', 'Ruangan');

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

        // Data
        $row = 5;
        foreach ($riwayats as $index => $riwayat) {
            $myPenguji = $riwayat->penguji->firstWhere('dosen_id', $dosenId);
            $myPembimbing = null;
            
            if ($riwayat->pendaftaran && $riwayat->pendaftaran->pembimbing) {
                $myPembimbing = $riwayat->pendaftaran->pembimbing->firstWhere('dosen_id', $dosenId);
            }
            
            $peran = '';
            if ($myPenguji) {
                $peran .= 'Penguji ' . ucfirst($myPenguji->peran ?? '');
            }
            if ($myPembimbing) {
                $peran .= $myPenguji ? ', ' : '';
                $peran .= 'Pembimbing ' . ucfirst($myPembimbing->jenis ?? '');
            }

            $sheet->setCellValue('A' . $row, $index + 1)
                ->setCellValue('B' . $row, \Carbon\Carbon::parse($riwayat->tanggal ?? $riwayat->created_at)->format('d/m/Y'))
                ->setCellValue('C' . $row, $riwayat->pendaftaran->mahasiswa->name ?? '-')
                ->setCellValue('D' . $row, $riwayat->pendaftaran->mahasiswa->nim ?? '-')
                ->setCellValue('E' . $row, $riwayat->pendaftaran->judul ?? '-')
                ->setCellValue('F' . $row, $riwayat->pendaftaran->kategoriSidang->nama ?? '-')
                ->setCellValue('G' . $row, $peran)
                ->setCellValue('H' . $row, $riwayat->ruangSidang->nama ?? '-');

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $fileName = 'riwayat_sidang_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    private function getFilters($request)
    {
        $filters = [];
        
        if ($request->filled('tahun')) {
            $filters[] = 'Tahun: ' . $request->tahun;
        }
        
        if ($request->filled('bulan')) {
            $filters[] = 'Bulan: ' . \Carbon\Carbon::create()->month($request->bulan)->format('F');
        }
        
        if ($request->filled('kategori')) {
            $kategori = \App\Models\KategoriSidang::find($request->kategori);
            if ($kategori) {
                $filters[] = 'Kategori: ' . $kategori->nama;
            }
        }
        
        return empty($filters) ? 'Semua Data' : implode(', ', $filters);
    }
}