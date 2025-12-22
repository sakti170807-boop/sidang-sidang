<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $mahasiswaId = auth()->id();
        
        // TESTING: Tampilkan SEMUA jadwal untuk mahasiswa ini (tanpa filter status)
        $jadwals = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            // COMMENT DULU untuk testing
            // ->whereIn('status', ['completed', 'selesai'])
            ->with([
                'pendaftaran.mahasiswa.programStudi',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji.dosen',
                'penguji.penilaian'
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        // Debug log
        \Log::info('Mahasiswa ID: ' . $mahasiswaId);
        \Log::info('Total Jadwals Found: ' . $jadwals->count());

        return view('mahasiswa.penilaian.index', compact('jadwals'));
    }

    public function show(JadwalSidang $jadwal)
    {
        if ($jadwal->pendaftaran->mahasiswa_id !== auth()->id()) {
            abort(403, 'Unauthorized - Ini bukan jadwal sidang Anda');
        }

        $jadwal->load([
            'pendaftaran.mahasiswa.programStudi',
            'pendaftaran.kategoriSidang',
            'pendaftaran.pembimbing.dosen',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ]);

        $nilaiPenguji = $jadwal->penguji->filter(function($penguji) {
            return $penguji->penilaian !== null;
        });
        
        $nilaiAkhir = null;
        if ($nilaiPenguji->count() > 0) {
            $nilaiAkhir = $nilaiPenguji->avg(function($penguji) {
                return $penguji->penilaian->nilai_akhir;
            });
        }

        return view('mahasiswa.penilaian.show', compact('jadwal', 'nilaiAkhir'));
    }
}