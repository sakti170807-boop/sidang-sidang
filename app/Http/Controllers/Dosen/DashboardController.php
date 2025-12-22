<?php
// app/Http/Controllers/Dosen/DashboardController.php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\JadwalSidang;
// use App\Models\PengujiSidang; // Comment dulu karena model belum dibuat
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dosenId = Auth::id();

        // Statistics
        $stats = [
            // Mahasiswa yang dibimbing (sudah approved)
            'mahasiswa_bimbingan' => Pembimbing::where('dosen_id', $dosenId)
                ->where('status', 'approved')
                ->count(),
            
            // Pendaftaran yang menunggu verifikasi
            'menunggu_verifikasi' => Pembimbing::where('dosen_id', $dosenId)
                ->where('status', 'pending')
                ->count(),
            
            // SEMENTARA: Set 0 karena tabel penguji_sidang belum ada
            'jadwal_menguji' => 0,
            // Nanti setelah tabel penguji_sidang dibuat, ganti dengan:
            // 'jadwal_menguji' => PengujiSidang::where('dosen_id', $dosenId)
            //     ->whereHas('jadwalSidang', function($q) {
            //         $q->where('tanggal_mulai', '>=', now());
            //     })
            //     ->count(),
            
            // SEMENTARA: Set 0 karena tabel penguji_sidang dan penilaian belum ada
            'menunggu_penilaian' => 0,
            // Nanti setelah tabel dibuat, ganti dengan:
            // 'menunggu_penilaian' => PengujiSidang::where('dosen_id', $dosenId)
            //     ->whereHas('jadwalSidang', function($q) {
            //         $q->where('status', 'completed');
            //     })
            //     ->whereNull('nilai')
            //     ->count(),
        ];

        // Ambil pendaftaran yang menunggu verifikasi dosen ini
        $pendingVerifikasi = Pembimbing::where('dosen_id', $dosenId)
            ->where('status', 'pending')
            ->with([
                'pendaftaran.mahasiswa', 
                'pendaftaran.kategoriSidang'
            ])
            ->latest()
            ->take(5)
            ->get();

        // Jadwal sidang mendatang
        // SEMENTARA: Tampilkan semua jadwal mendatang
        // Nanti setelah tabel penguji_sidang ada, filter berdasarkan dosen yang menguji
        $jadwalMendatang = JadwalSidang::where('tanggal_mulai', '>=', now())
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.kategoriSidang', 
                'ruangSidang',
                // 'penguji.dosen' // Uncomment setelah tabel penguji_sidang dibuat
            ])
            // Nanti setelah tabel penguji_sidang ada, tambahkan filter:
            // ->whereHas('penguji', function($q) use ($dosenId) {
            //     $q->where('dosen_id', $dosenId);
            // })
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'stats', 
            'pendingVerifikasi', 
            'jadwalMendatang'
        ));
    }
}