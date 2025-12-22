<?php

// ============================================
// FILE: app/Http/Controllers/Mahasiswa/JadwalController.php
// ============================================

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $mahasiswaId = auth()->id();
        
        // Ambil jadwal yang BELUM completed (yang sudah completed ada di riwayat)
        $jadwals = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->where('status', '!=', 'completed') // Filter: tidak tampilkan yang sudah selesai
            ->with([
                'pendaftaran.mahasiswa.programStudi',
                'pendaftaran.kategoriSidang',
                'pendaftaran.pembimbing.dosen',
                'ruangSidang',
                'penguji.dosen',
                'penguji.penilaian'
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        return view('mahasiswa.jadwal.index', compact('jadwals'));
    }

    public function show(JadwalSidang $jadwal)
    {
        // Pastikan jadwal ini milik mahasiswa yang login
        if ($jadwal->pendaftaran->mahasiswa_id !== auth()->id()) {
            abort(403, 'Unauthorized - Ini bukan jadwal sidang Anda');
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

        // Hitung progress penilaian
        $totalPenguji = $jadwal->penguji->count();
        $pengujiSudahNilai = $jadwal->penguji()->whereHas('penilaian')->count();
        $progressPenilaian = $totalPenguji > 0 ? round(($pengujiSudahNilai / $totalPenguji) * 100) : 0;

        return view('mahasiswa.jadwal.show', compact('jadwal', 'progressPenilaian', 'pengujiSudahNilai', 'totalPenguji'));
    }
}