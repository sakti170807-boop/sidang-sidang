<?php

// ============================================
// FILE: app/Http/Controllers/Dosen/JadwalController.php
// ============================================

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $dosenId = auth()->id();
        
        // Ambil jadwal dimana dosen ini sebagai penguji
        $jadwals = JadwalSidang::whereHas('penguji', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            // HANYA tampilkan yang belum completed
            ->where('status', '!=', 'completed')
            ->with([
                'pendaftaran.mahasiswa.programStudi',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji' => function($q) use ($dosenId) {
                    $q->where('dosen_id', $dosenId)->with('penilaian');
                }
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(15);
        
        \Log::info('Jadwal Dosen', [
            'dosen_id' => $dosenId,
            'total_jadwal' => $jadwals->total()
        ]);

        return view('dosen.jadwal.index', compact('jadwals'));
    }

    public function show(JadwalSidang $jadwal)
    {
        $dosenId = auth()->id();
        
        // Cek apakah dosen ini adalah penguji di sidang ini
        $penguji = $jadwal->penguji()
            ->where('dosen_id', $dosenId)
            ->first();

        if (!$penguji) {
            return back()->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        $jadwal->load([
            'pendaftaran.mahasiswa.programStudi',
            'pendaftaran.kategoriSidang',
            'pendaftaran.pembimbing.dosen',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ]);

        return view('dosen.jadwal.show', compact('jadwal', 'penguji'));
    }

    public function konfirmasi(Request $request, JadwalSidang $jadwal)
    {
        try {
            $penguji = $jadwal->penguji()
                ->where('dosen_id', auth()->id())
                ->firstOrFail();

            $penguji->update([
                'status' => 'confirmed'
            ]);

            return back()->with('success', 'Kehadiran berhasil dikonfirmasi');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal konfirmasi kehadiran: ' . $e->getMessage());
        }
    }

    public function tolak(Request $request, JadwalSidang $jadwal)
    {
        $validated = $request->validate([
            'alasan' => 'required|string|min:10'
        ], [
            'alasan.required' => 'Alasan penolakan harus diisi',
            'alasan.min' => 'Alasan minimal 10 karakter'
        ]);

        try {
            $penguji = $jadwal->penguji()
                ->where('dosen_id', auth()->id())
                ->firstOrFail();

            $penguji->update([
                'status' => 'rejected',
                'catatan' => $validated['alasan']
            ]);

            return redirect()->route('dosen.jadwal.index')
                ->with('success', 'Penolakan berhasil disampaikan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak jadwal: ' . $e->getMessage());
        }
    }
}
