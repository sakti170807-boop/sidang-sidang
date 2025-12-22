<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use App\Models\Pendaftaran;
use App\Models\RuangSidang;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalSidangController extends Controller
{
    public function index()
    {
        $jadwals = JadwalSidang::with([
            'pendaftaran.mahasiswa',
            'pendaftaran.kategoriSidang',
            'ruangSidang'
        ])
        ->when(request('status'), fn($q) => $q->where('status', request('status')))
        ->latest()
        ->paginate(10);

        return view('admin.jadwal-sidang.index', compact('jadwals'));
    }

    public function create()
    {
        // Ambil pendaftaran yang sudah diverifikasi admin dan belum ada jadwalnya
        $pendaftarans = Pendaftaran::where('status', 'approved')
            ->orWhere('status', 'verified_admin')
            ->whereDoesntHave('jadwal')
            ->with('mahasiswa')
            ->latest()
            ->get();

        // Ambil semua ruangan yang aktif
        $ruangans = RuangSidang::where('is_active', true)
            ->orderBy('nama')
            ->get();

        // Ambil semua dosen yang aktif
        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.jadwal-sidang.create', compact('pendaftarans', 'ruangans', 'dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_sidang_id' => 'required|exists:pendaftaran_sidang,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'ruang_sidang_id' => 'required|exists:ruang_sidang,id',
            'catatan' => 'nullable|string',
            'penguji' => 'required|array|min:1', // WAJIB minimal 1 penguji
            'penguji.*.dosen_id' => 'required|exists:users,id',
            'penguji.*.peran' => 'required|in:ketua,anggota,sekretaris'
        ], [
            'penguji.required' => 'Minimal harus menambahkan 1 dosen penguji',
            'penguji.min' => 'Minimal harus menambahkan 1 dosen penguji'
        ]);

        // Ambil data pendaftaran dengan relasi yang diperlukan
        $pendaftaran = Pendaftaran::with('pembimbing', 'kategoriSidang')
            ->findOrFail($validated['pendaftaran_sidang_id']);

        // Gabungkan tanggal dengan waktu untuk membuat datetime
        $tanggalMulai = $validated['tanggal'] . ' ' . $validated['waktu_mulai'] . ':00';
        $tanggalSelesai = $validated['tanggal'] . ' ' . $validated['waktu_selesai'] . ':00';

        // Cek konflik jadwal
        $conflict = JadwalSidang::where('ruang_sidang_id', $validated['ruang_sidang_id'])
            ->where(function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                  ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                  ->orWhere(function($query) use ($tanggalMulai, $tanggalSelesai) {
                      $query->where('tanggal_mulai', '<=', $tanggalMulai)
                            ->where('tanggal_selesai', '>=', $tanggalSelesai);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors(['message' => 'Ruangan sudah digunakan pada waktu tersebut']);
        }

        // Ambil pembimbing_id (ambil pembimbing pertama atau utama)
        $pembimbingId = $pendaftaran->pembimbing->first()?->id ?? null;
        
        if (!$pembimbingId) {
            return back()->withInput()->withErrors(['message' => 'Pendaftaran ini belum memiliki pembimbing']);
        }

        // Buat jadwal sesuai struktur tabel yang sebenarnya
        $jadwal = JadwalSidang::create([
            'ruang_sidang_id' => $validated['ruang_sidang_id'],
            'pendaftaran_sidang_id' => $validated['pendaftaran_sidang_id'],
            'kategori_sidang_id' => $pendaftaran->kategori_sidang_id,
            'pembimbing_id' => $pembimbingId,
            'tanggal' => $validated['tanggal'], // PENTING: Field tanggal
            'waktu_mulai' => $validated['waktu_mulai'], // PENTING: Field waktu_mulai
            'waktu_selesai' => $validated['waktu_selesai'], // PENTING: Field waktu_selesai
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'status' => 'terjadwal',
            'catatan' => $validated['catatan'] ?? null,
            'is_active' => true
        ]);

        // Tambahkan penguji (WAJIB, sudah divalidasi minimal 1)
        foreach ($validated['penguji'] as $penguji) {
            $jadwal->penguji()->create([
                'dosen_id' => $penguji['dosen_id'],
                'peran' => $penguji['peran']
            ]);
        }

        return redirect()->route('admin.jadwal-sidang.index')
            ->with('success', 'Jadwal sidang berhasil dibuat dan dosen penguji telah ditambahkan');
    }

    public function show(JadwalSidang $jadwal)
    {
        $jadwal->load([
            'pendaftaran.mahasiswa',
            'pendaftaran.kategoriSidang',
            'pendaftaran.pembimbing.dosen',
            'ruangSidang',
            'penguji.dosen'
        ]);

        return view('admin.jadwal-sidang.show', compact('jadwal'));
    }

    public function edit(JadwalSidang $jadwal)
    {
        $pendaftarans = Pendaftaran::where('status', 'approved')
            ->orWhere('status', 'verified_admin')
            ->with('mahasiswa')
            ->get();

        $ruangans = RuangSidang::where('is_active', true)->get();
        $dosens = User::where('role', 'dosen')->where('is_active', true)->get();

        $jadwal->load('penguji');

        return view('admin.jadwal-sidang.edit', compact('jadwal', 'pendaftarans', 'ruangans', 'dosens'));
    }

    public function update(Request $request, JadwalSidang $jadwal)
    {
        $validated = $request->validate([
            'pendaftaran_sidang_id' => 'required|exists:pendaftaran_sidang,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'ruang_sidang_id' => 'required|exists:ruang_sidang,id',
            'catatan' => 'nullable|string',
            'status' => 'nullable|in:terjadwal,dikonfirmasi,selesai,dibatalkan'
        ]);

        // Gabungkan tanggal dengan waktu
        $tanggalMulai = $validated['tanggal'] . ' ' . $validated['waktu_mulai'] . ':00';
        $tanggalSelesai = $validated['tanggal'] . ' ' . $validated['waktu_selesai'] . ':00';

        $jadwal->update([
            'ruang_sidang_id' => $validated['ruang_sidang_id'],
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'catatan' => $validated['catatan'],
            'status' => $validated['status'] ?? $jadwal->status
        ]);

        return redirect()->route('admin.jadwal-sidang.index')
            ->with('success', 'Jadwal sidang berhasil diperbarui');
    }

    public function destroy(JadwalSidang $jadwal)
    {
        if ($jadwal->status == 'selesai') {
            return back()->withErrors(['message' => 'Jadwal yang sudah selesai tidak dapat dihapus']);
        }

        $jadwal->delete();

        return redirect()->route('admin.jadwal-sidang.index')
            ->with('success', 'Jadwal sidang berhasil dihapus');
    }

    public function checkConflict(Request $request)
    {
        $tanggalMulai = $request->tanggal . ' ' . $request->waktu_mulai . ':00';
        $tanggalSelesai = $request->tanggal . ' ' . $request->waktu_selesai . ':00';

        $conflict = JadwalSidang::where('ruang_sidang_id', $request->ruang_sidang_id)
            ->where(function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                  ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                  ->orWhere(function($query) use ($tanggalMulai, $tanggalSelesai) {
                      $query->where('tanggal_mulai', '<=', $tanggalMulai)
                            ->where('tanggal_selesai', '>=', $tanggalSelesai);
                  });
            })
            ->exists();

        return response()->json(['conflict' => $conflict]);
    }

    public function assignPenguji(Request $request, JadwalSidang $jadwal)
    {
        $validated = $request->validate([
            'penguji.*.dosen_id' => 'required|exists:users,id',
            'penguji.*.peran' => 'required|in:ketua,anggota,sekretaris'
        ]);

        // Hapus penguji lama
        $jadwal->penguji()->delete();

        // Tambah penguji baru
        foreach ($validated['penguji'] as $penguji) {
            $jadwal->penguji()->create([
                'dosen_id' => $penguji['dosen_id'],
                'peran' => $penguji['peran'],
                'status' => 'pending'
            ]);
        }

        return back()->with('success', 'Penguji berhasil ditambahkan');
    }
}