<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $dosenId = auth()->id();
        
        // Ambil jadwal dimana dosen ini sebagai penguji
        $jadwals = JadwalSidang::whereHas('penguji', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })
            // Hanya yang sudah terjadwal/ongoing (siap dinilai)
            ->whereIn('status', ['terjadwal', 'scheduled', 'ongoing'])
            ->with([
                'pendaftaran.mahasiswa.programStudi',
                'pendaftaran.kategoriSidang',
                'ruangSidang',
                'penguji' => function($q) use ($dosenId) {
                    $q->where('dosen_id', $dosenId)->with('penilaian');
                }
            ])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(20);

        return view('dosen.penilaian.index', compact('jadwals'));
    }

    public function show(JadwalSidang $jadwal)
    {
        $dosenId = auth()->id();
        
        $penguji = $jadwal->penguji()
            ->where('dosen_id', $dosenId)
            ->first();

        if (!$penguji) {
            return back()->with('error', 'Anda bukan penguji di sidang ini');
        }
        
        $jadwal->load([
            'pendaftaran.mahasiswa.programStudi',
            'pendaftaran.kategoriSidang',
            'ruangSidang',
            'penguji.dosen',
            'penguji.penilaian'
        ]);

        // Get the penilaian for this penguji
        $penilaian = $penguji->penilaian;

        return view('dosen.penilaian.show', compact('jadwal', 'penguji', 'penilaian'));
    }

    public function store(Request $request, JadwalSidang $jadwal)
    {
        $dosenId = auth()->id();
        
        $penguji = $jadwal->penguji()
            ->where('dosen_id', $dosenId)
            ->firstOrFail();

        $validated = $request->validate([
            'nilai_presentasi' => 'required|numeric|min:0|max:100',
            'nilai_materi' => 'required|numeric|min:0|max:100',
            'nilai_diskusi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string'
        ], [
            'nilai_presentasi.required' => 'Nilai presentasi harus diisi',
            'nilai_materi.required' => 'Nilai materi harus diisi',
            'nilai_diskusi.required' => 'Nilai diskusi harus diisi',
            'nilai_presentasi.min' => 'Nilai minimum adalah 0',
            'nilai_presentasi.max' => 'Nilai maksimum adalah 100',
        ]);

        // Hitung nilai akhir
        $nilaiAkhir = (
            $validated['nilai_presentasi'] + 
            $validated['nilai_materi'] + 
            $validated['nilai_diskusi']
        ) / 3;

        $validated['nilai_akhir'] = round($nilaiAkhir, 2);
        $validated['jadwal_sidang_id'] = $jadwal->id;
        $validated['penguji_id'] = $penguji->id;

        // Simpan penilaian
        Penilaian::updateOrCreate(
            [
                'jadwal_sidang_id' => $jadwal->id,
                'penguji_id' => $penguji->id
            ],
            $validated
        );

        // CEK APAKAH SEMUA PENGUJI SUDAH MENILAI
        $this->checkAndUpdateJadwalStatus($jadwal);

        return back()->with('success', 'Penilaian berhasil disimpan');
    }

    /**
     * Cek apakah semua penguji sudah memberikan penilaian
     * Jika sudah semua, update status jadwal menjadi 'completed'
     */
    private function checkAndUpdateJadwalStatus(JadwalSidang $jadwal)
    {
        // Hitung total penguji
        $totalPenguji = $jadwal->penguji()->count();
        
        // Hitung penguji yang sudah memberikan penilaian
        $pengujiSudahNilai = $jadwal->penguji()
            ->whereHas('penilaian')
            ->count();
        
        \Log::info('Check Penilaian Status', [
            'jadwal_id' => $jadwal->id,
            'total_penguji' => $totalPenguji,
            'sudah_nilai' => $pengujiSudahNilai
        ]);

        // Jika semua penguji sudah menilai, update status jadwal
        if ($totalPenguji > 0 && $pengujiSudahNilai >= $totalPenguji) {
            $jadwal->update([
                'status' => 'completed'
            ]);
            
            \Log::info('Jadwal Updated to Completed', [
                'jadwal_id' => $jadwal->id
            ]);
        }
    }
}