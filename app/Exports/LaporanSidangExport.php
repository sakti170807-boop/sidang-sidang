<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\JadwalSidang;
use Carbon\Carbon;

class LaporanSidangExport
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function download($filename)
    {
        // Get the data
        $jadwals = JadwalSidang::with([
            'pendaftaran.mahasiswa',
            'pendaftaran.kategoriSidang',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ])
        ->whereBetween('tanggal_mulai', [$this->startDate, $this->endDate])
        ->orderBy('tanggal_mulai', 'asc')
        ->get();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Sistem Sidang')
            ->setTitle('Laporan Sidang')
            ->setSubject('Laporan Sidang Periode ' . $this->startDate . ' sampai ' . $this->endDate);

        // Add header
        $sheet->setCellValue('A1', 'LAPORAN SIDANG');
        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d F Y') . ' - ' . Carbon::parse($this->endDate)->format('d F Y'));
        
        // Add some spacing
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'Mahasiswa');
        $sheet->setCellValue('D4', 'NIM');
        $sheet->setCellValue('E4', 'Judul');
        $sheet->setCellValue('F4', 'Kategori');
        $sheet->setCellValue('G4', 'Ruangan');
        $sheet->setCellValue('H4', 'Status');
        $sheet->setCellValue('I4', 'Nilai');

        // Make header bold
        $headerRange = 'A4:I4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Add data
        $row = 5;
        foreach ($jadwals as $index => $jadwal) {
            // Calculate average nilai
            $nilaiList = $jadwal->penguji->map(fn($p) => $p->penilaian?->nilai_akhir)->filter();
            $nilaiRataRata = $nilaiList->count() > 0 ? round($nilaiList->average(), 2) : '-';
            
            $sheet->setCellValue('A' . $row, $index + 1)
                  ->setCellValue('B' . $row, Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y H:i'))
                  ->setCellValue('C' . $row, $jadwal->pendaftaran->mahasiswa->name ?? '-')
                  ->setCellValue('D' . $row, $jadwal->pendaftaran->mahasiswa->nim ?? '-')
                  ->setCellValue('E' . $row, $jadwal->pendaftaran->judul ?? '-')
                  ->setCellValue('F' . $row, $jadwal->pendaftaran->kategoriSidang->nama ?? '-')
                  ->setCellValue('G' . $row, $jadwal->ruangSidang->nama ?? '-')
                  ->setCellValue('H' . $row, ucfirst($jadwal->status))
                  ->setCellValue('I' . $row, $nilaiRataRata);

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Return response for download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}