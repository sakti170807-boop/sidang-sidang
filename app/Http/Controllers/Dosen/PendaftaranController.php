<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pembimbing;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $dosenId = auth()->id();
        
        $pendaftarans = Pendaftaran::whereHas('pembimbing', function($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
        ->with(['mahasiswa', 'kategoriSidang', 'pembimbing.dosen'])
        ->when(request('status'), fn($q) => $q->where('status', request('status')))
        ->latest()
        ->paginate(10);

        return view('dosen.pendaftaran.index', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $dosenId = auth()->id();
        
        // Cek apakah dosen adalah pembimbing
        $pembimbing = Pembimbing::where('pendaftaran_id', $pendaftaran->id)
            ->where('dosen_id', $dosenId)
            ->first();
        
        if (!$pembimbing) {
            abort(403, 'Anda bukan pembimbing untuk pendaftaran ini');
        }

        // Load semua relasi yang diperlukan
        $pendaftaran->load([
            'mahasiswa',
            'kategoriSidang',
            'programStudi',
            'dokumen',
            'pembimbing.dosen'
        ]);

        return view('dosen.pendaftaran.show', compact('pendaftaran', 'pembimbing'));
    }

    public function approve(Pendaftaran $pendaftaran)
    {
        $dosenId = auth()->id();
        
        $pembimbing = Pembimbing::where('pendaftaran_id', $pendaftaran->id)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();
        
        if ($pembimbing->status !== 'pending') {
            return back()->withErrors(['message' => 'Pendaftaran sudah diproses sebelumnya']);
        }

        $pembimbing->update([
            'status' => 'approved'
        ]);

        // Cek apakah semua pembimbing sudah approve
        $allApproved = Pembimbing::where('pendaftaran_id', $pendaftaran->id)
            ->where('status', '!=', 'approved')
            ->count() === 0;

        if ($allApproved) {
            $pendaftaran->update([
                'tanggal_verifikasi_pembimbing' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil disetujui');
    }

    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);

        $dosenId = auth()->id();
        
        $pembimbing = Pembimbing::where('pendaftaran_id', $pendaftaran->id)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();
        
        if ($pembimbing->status !== 'pending') {
            return back()->withErrors(['message' => 'Pendaftaran sudah diproses sebelumnya']);
        }

        $pembimbing->update([
            'status' => 'rejected',
            'catatan' => $request->catatan
        ]);

        $pendaftaran->update([
            'status' => 'rejected',
            'catatan_admin' => 'Ditolak oleh ' . auth()->user()->name . ': ' . $request->catatan
        ]);

        return redirect()->route('dosen.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil ditolak');
    }
}