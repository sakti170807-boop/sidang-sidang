<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index()
    {
        $mahasiswaId = auth()->id();
        
        // Ambil jadwal sidang yang sudah selesai milik mahasiswa ini
        $hasilSidang = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->whereIn('status', ['selesai', 'completed'])
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji.dosen',
                'penguji.penilaian'
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('mahasiswa.hasil.index', compact('hasilSidang'));
    }

    public function show($id)
    {
        $mahasiswaId = auth()->id();
        
        // Ambil jadwal dengan validasi bahwa ini milik mahasiswa yang login
        $jadwal = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji.dosen',
                'penguji.penilaian'
            ])
            ->findOrFail($id);

        // Hitung rata-rata nilai dari semua penguji
        $nilaiPenguji = $jadwal->penguji->map(function($penguji) {
            return $penguji->penilaian ? $penguji->penilaian->nilai_akhir : null;
        })->filter()->values();

        $nilaiAkhir = $nilaiPenguji->count() > 0 ? $nilaiPenguji->average() : null;

        // Tentukan status kelulusan
        $statusKelulusan = null;
        $predikat = null;
        
        if ($nilaiAkhir !== null) {
            if ($nilaiAkhir >= 80) {
                $statusKelulusan = 'Lulus';
                $predikat = 'Sangat Memuaskan';
            } elseif ($nilaiAkhir >= 70) {
                $statusKelulusan = 'Lulus';
                $predikat = 'Memuaskan';
            } elseif ($nilaiAkhir >= 60) {
                $statusKelulusan = 'Lulus';
                $predikat = 'Cukup';
            } else {
                $statusKelulusan = 'Tidak Lulus';
                $predikat = 'Kurang';
            }
        }

        return view('mahasiswa.hasil.show', compact('jadwal', 'nilaiAkhir', 'statusKelulusan', 'predikat'));
    }
}