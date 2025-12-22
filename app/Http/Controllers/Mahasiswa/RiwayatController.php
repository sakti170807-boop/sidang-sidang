<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $mahasiswaId = auth()->id();
        
        // Ambil jadwal yang sudah completed
        $riwayats = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->where('status', 'completed')
            ->with([
                'pendaftaran.mahasiswa.programStudi',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji.dosen',
                'penguji.penilaian'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('mahasiswa.riwayat.index', compact('riwayats'));
    }

    public function show(JadwalSidang $jadwal)
    {
        // Pastikan jadwal ini milik mahasiswa yang login
        if ($jadwal->pendaftaran->mahasiswa_id !== auth()->id()) {
            abort(403, 'Unauthorized - Ini bukan riwayat sidang Anda');
        }

        // Pastikan status completed
        if ($jadwal->status !== 'completed') {
            return redirect()->route('mahasiswa.jadwal.show', $jadwal)
                ->with('info', 'Sidang ini belum selesai, lihat di menu Jadwal Sidang');
        }

        // Load semua data yang diperlukan
        $jadwal->load([
            'pendaftaran.mahasiswa.programStudi',
            'pendaftaran.kategoriSidang',
            'pendaftaran.pembimbing.dosen',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ]);

        // Hitung nilai rata-rata
        $penilaians = $jadwal->penguji->pluck('penilaian')->flatten();
        $nilaiRataRata = $penilaians->count() > 0 ? round($penilaians->avg('nilai'), 2) : 0;

        return view('mahasiswa.riwayat.show', compact('jadwal', 'nilaiRataRata'));
    }

    public function exportPdf()
    {
        // Implementation untuk export PDF
    }

    public function exportExcel()
    {
        // Implementation untuk export Excel
    }
}