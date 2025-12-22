<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\JadwalSidang;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswaId = auth()->id();

        $stats = [
            'total_pendaftaran' => Pendaftaran::where('mahasiswa_id', $mahasiswaId)->count(),
            'menunggu_verifikasi' => Pendaftaran::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('status', ['submitted', 'verified_pembimbing'])
                ->count(),
            'sidang_terjadwal' => Pendaftaran::where('mahasiswa_id', $mahasiswaId)
                ->where('status', 'scheduled')
                ->count(),
            'sidang_selesai' => Pendaftaran::where('mahasiswa_id', $mahasiswaId)
                ->where('status', 'completed')
                ->count(),
        ];

        $pendaftaranAktif = Pendaftaran::where('mahasiswa_id', $mahasiswaId)
            ->whereNotIn('status', ['completed', 'rejected'])
            ->with(['kategoriSidang', 'pembimbing.dosen'])
            ->latest()
            ->first();

        // PERBAIKAN: Hapus relasi 'penguji' karena tabel belum ada
        $jadwalMendatang = JadwalSidang::whereHas('pendaftaran', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->where('tanggal_mulai', '>=', now())
            ->with(['ruangSidang']) // Hanya load ruangSidang
            ->orderBy('tanggal_mulai')
            ->first();

        // Pengumuman dengan error handling
        try {
            $pengumuman = Pengumuman::where('is_active', true)
                ->where(function($q) {
                    $q->where('target_audience', 'all')
                      ->orWhere('target_audience', 'mahasiswa')
                      ->orWhere('program_studi_id', auth()->user()->program_studi_id);
                })
                ->where(function($q) {
                    $q->whereNull('tanggal_mulai')
                      ->orWhere('tanggal_mulai', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('tanggal_selesai')
                      ->orWhere('tanggal_selesai', '>=', now());
                })
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $pengumuman = collect([]);
        }

        return view('mahasiswa.dashboard', compact('stats', 'pendaftaranAktif', 'jadwalMendatang', 'pengumuman'));
    }
}