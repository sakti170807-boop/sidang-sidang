<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\JadwalSidang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'pendaftaran_baru' => Pendaftaran::where('status', 'submitted')->count(),
            'menunggu_verifikasi' => Pendaftaran::where('status', 'verified_pembimbing')
                ->orWhere('status', 'submitted')
                ->count(),
            'sidang_terjadwal' => JadwalSidang::where('status', 'scheduled')
                ->orWhere('status', 'confirmed')
                ->count(),
            'sidang_selesai' => JadwalSidang::where('status', 'completed')->count(),
        ];

        // Pendaftaran Terbaru (10 terbaru)
        $pendaftaranTerbaru = Pendaftaran::with(['mahasiswa', 'kategoriSidang'])
            ->latest()
            ->limit(10)
            ->get();

        // Jadwal Mendatang (jadwal yang akan datang)
        // Gunakan created_at sebagai fallback jika tanggal tidak ada
        $jadwalMendatang = JadwalSidang::with([
            'pendaftaran.mahasiswa',
            'ruangSidang'
        ])
        ->where(function($query) {
            // Cek jika kolom tanggal ada
            if (\Schema::hasColumn('jadwal_sidang', 'tanggal')) {
                $query->where('tanggal', '>=', now()->toDateString());
            } else {
                // Fallback ke created_at
                $query->where('created_at', '>=', now());
            }
        })
        ->whereIn('status', ['scheduled', 'confirmed'])
        ->orderBy('created_at', 'asc')
        ->limit(10)
        ->get();

        return view('admin.dashboard', compact('stats', 'pendaftaranTerbaru', 'jadwalMendatang'));
    }
}