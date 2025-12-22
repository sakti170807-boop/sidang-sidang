<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['mahasiswa', 'kategoriSidang', 'programStudi', 'pembimbing.dosen']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_sidang_id')) {
            $query->where('kategori_sidang_id', $request->kategori_sidang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            })->orWhere('judul', 'like', "%{$search}%");
        }

        $pendaftarans = $query->latest()->paginate(20);

        return view('admin.pendaftaran.index', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['mahasiswa', 'kategoriSidang', 'programStudi', 'pembimbing.dosen', 'dokumen', 'jadwal']);
        
        $dosenList = User::where('role', 'dosen')
            ->where('program_studi_id', $pendaftaran->program_studi_id)
            ->get();

        return view('admin.pendaftaran.show', compact('pendaftaran', 'dosenList'));
    }

    public function verify(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string'
        ]);

        $pendaftaran->update([
            'status' => 'verified_admin',
            'catatan_admin' => $request->catatan_admin,
            'tanggal_verifikasi_admin' => now()
        ]);

        return back()->with('success', 'Pendaftaran berhasil diverifikasi');
    }

    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'catatan_admin' => 'required|string'
        ]);

        $pendaftaran->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin
        ]);

        return back()->with('success', 'Pendaftaran ditolak');
    }
}