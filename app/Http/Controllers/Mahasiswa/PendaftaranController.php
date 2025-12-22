<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\KategoriSidang;
use App\Models\User;
use App\Models\Pembimbing;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function index()
    {
        $statusFilter = request('status');
        
        $pendaftarans = Pendaftaran::where('mahasiswa_id', auth()->id())
            ->with(['kategoriSidang', 'pembimbing.dosen', 'dokumen', 'jadwal.ruangSidang', 'jadwal.penguji'])
            ->when($statusFilter, function($query) use ($statusFilter) {
                if ($statusFilter === 'scheduled') {
                    // Filter untuk yang terjadwal: ada jadwal dan statusnya bukan completed
                    $query->whereHas('jadwal', function($q) {
                        $q->where('status', '!=', 'completed');
                    });
                } elseif ($statusFilter === 'completed') {
                    // Filter untuk yang selesai: ada jadwal dengan status completed
                    $query->whereHas('jadwal', function($q) {
                        $q->where('status', 'completed');
                    });
                } else {
                    // Filter berdasarkan status pendaftaran biasa
                    $query->where('status', $statusFilter);
                }
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('mahasiswa.pendaftaran.index', compact('pendaftarans'));
    }

    public function create()
    {
        $mahasiswa = auth()->user();

        $kategoris = KategoriSidang::where('is_active', true)->get();

        $dosens = User::where('role', 'dosen')
            ->where('program_studi_id', $mahasiswa->program_studi_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $pendaftarans = Pendaftaran::where('mahasiswa_id', $mahasiswa->id)
            ->with('kategoriSidang')
            ->latest()
            ->paginate(3);

        return view('mahasiswa.pendaftaran.create', compact('kategoris', 'dosens', 'pendaftarans'));
    }

    public function store(Request $request)
    {
        // Pastikan folder dokumen-sidang ada
        if (!Storage::disk('public')->exists('dokumen-sidang')) {
            Storage::disk('public')->makeDirectory('dokumen-sidang');
        }
        
        // Validasi input
        $validated = $request->validate([
            'kategori_sidang_id' => 'required|exists:kategori_sidang,id',
            'judul' => 'required|string|max:500',
            'abstrak' => 'required|string',
            'pembimbing_utama_id' => 'required|exists:users,id',
            'pembimbing_pendamping_id' => 'nullable|exists:users,id|different:pembimbing_utama_id',
        ], [
            'pembimbing_pendamping_id.different' => 'Pembimbing pendamping tidak boleh sama dengan pembimbing utama',
        ]);
        
        // Validasi dokumen secara manual
        if ($request->has('dokumen')) {
            foreach ($request->dokumen as $index => $dok) {
                if (isset($dok['jenis']) && !empty($dok['jenis'])) {
                    // Jika ada jenis, maka file wajib ada
                    if (!$request->hasFile("dokumen.$index.file")) {
                        return back()
                            ->withInput()
                            ->withErrors(['dokumen' => "File untuk dokumen '{$dok['jenis']}' wajib diupload"]);
                    }
                    
                    $file = $request->file("dokumen.$index.file");
                    
                    // Validasi tipe file
                    $allowedMimes = ['pdf', 'doc', 'docx'];
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array(strtolower($extension), $allowedMimes)) {
                        return back()
                            ->withInput()
                            ->withErrors(['dokumen' => "File '{$dok['jenis']}' harus berformat PDF, DOC, atau DOCX"]);
                    }
                    
                    // Validasi ukuran file (max 2MB = 2048 KB)
                    if ($file->getSize() > 2048 * 1024) {
                        return back()
                            ->withInput()
                            ->withErrors(['dokumen' => "Ukuran file '{$dok['jenis']}' maksimal 2MB"]);
                    }
                }
            }
        }

        DB::beginTransaction();
        
        try {
            // Buat pendaftaran
            $pendaftaran = Pendaftaran::create([
                'mahasiswa_id' => auth()->id(),
                'kategori_sidang_id' => $validated['kategori_sidang_id'],
                'program_studi_id' => auth()->user()->program_studi_id,
                'judul' => $validated['judul'],
                'abstrak' => $validated['abstrak'],
                'status' => 'draft'
            ]);

            // Ambil data dosen pembimbing utama
            $dosenUtama = User::findOrFail($validated['pembimbing_utama_id']);
            
            // Buat pembimbing utama
            DB::table('pembimbing')->insert([
                'pendaftaran_id' => $pendaftaran->id,
                'dosen_id' => $validated['pembimbing_utama_id'],
                'email' => $dosenUtama->email,
                'jenis' => 'utama',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Buat pembimbing pendamping jika ada
            if (!empty($validated['pembimbing_pendamping_id'])) {
                $dosenPendamping = User::findOrFail($validated['pembimbing_pendamping_id']);
                
                DB::table('pembimbing')->insert([
                    'pendaftaran_id' => $pendaftaran->id,
                    'dosen_id' => $validated['pembimbing_pendamping_id'],
                    'email' => $dosenPendamping->email,
                    'jenis' => 'pendamping',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Upload dokumen jika ada
            if ($request->has('dokumen') && is_array($request->dokumen)) {
                foreach ($request->dokumen as $index => $dok) {
                    // Pastikan ada jenis dokumen dan file
                    if (isset($dok['jenis']) && !empty($dok['jenis']) && $request->hasFile("dokumen.$index.file")) {
                        $file = $request->file("dokumen.$index.file");
                        
                        if ($file && $file->isValid()) {
                            // Generate nama file unik dengan sanitasi
                            $originalName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                            
                            // Sanitasi nama file
                            $baseName = preg_replace('/[^A-Za-z0-9\-]/', '_', $baseName);
                            $fileName = time() . '_' . $index . '_' . $baseName . '.' . $extension;
                            
                            // Simpan file ke storage/app/public/dokumen-sidang
                            $filePath = $file->storeAs('dokumen-sidang', $fileName, 'public');
                            
                            // Simpan ke database dengan kolom yang sesuai
                            Dokumen::create([
                                'pendaftaran_id' => $pendaftaran->id,
                                'jenis_dokumen' => $dok['jenis'],
                                'file_path' => $filePath,
                                'nama_file' => $originalName,
                                'ukuran_file' => $file->getSize(),
                                'path' => $filePath,
                                'mime_type' => $file->getMimeType(),
                                'ukuran' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.pendaftaran.show', $pendaftaran)
                ->with('success', 'Pendaftaran berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika terjadi error
            if (isset($pendaftaran)) {
                $dokumens = Dokumen::where('pendaftaran_id', $pendaftaran->id)->get();
                foreach ($dokumens as $dok) {
                    Storage::disk('public')->delete($dok->file_path);
                }
            }
            
            return back()
                ->withInput()
                ->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(Pendaftaran $pendaftaran)
    {
        abort_if($pendaftaran->mahasiswa_id !== auth()->id(), 403);

        $pendaftaran->load([
            'kategoriSidang',
            'pembimbing.dosen',
            'dokumen',
            'jadwal.ruangSidang',
            'jadwal.penguji.dosen',
            'jadwal.penguji.penilaian'
        ]);

        // Jika ada jadwal, hitung progress penilaian
        $jadwal = $pendaftaran->jadwal;
        $progressPenilaian = 0;
        $pengujiSudahNilai = 0;
        $totalPenguji = 0;

        if ($jadwal) {
            $totalPenguji = $jadwal->penguji->count();
            $pengujiSudahNilai = $jadwal->penguji()->whereHas('penilaian')->count();
            $progressPenilaian = $totalPenguji > 0 ? round(($pengujiSudahNilai / $totalPenguji) * 100) : 0;
        }

        return view('mahasiswa.pendaftaran.show', compact('pendaftaran', 'progressPenilaian', 'pengujiSudahNilai', 'totalPenguji'));
    }

    public function submit(Pendaftaran $pendaftaran)
    {
        abort_if($pendaftaran->mahasiswa_id !== auth()->id(), 403);

        if ($pendaftaran->status !== 'draft') {
            return back()->withErrors(['message' => 'Pendaftaran tidak dapat disubmit']);
        }

        $kategori = $pendaftaran->kategoriSidang;
        $uploadedDocs = $pendaftaran->dokumen->pluck('jenis_dokumen')->toArray();
        $missingDocs = array_diff($kategori->dokumen_wajib ?? [], $uploadedDocs);

        if (!empty($missingDocs)) {
            return back()->withErrors(['message' => 'Dokumen belum lengkap: ' . implode(', ', $missingDocs)]);
        }

        $pendaftaran->update([
            'status' => 'submitted',
            'tanggal_submit' => now()
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil disubmit');
    }
}