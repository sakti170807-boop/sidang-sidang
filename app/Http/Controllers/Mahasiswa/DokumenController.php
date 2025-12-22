<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran_sidang,id',
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);

        // Verify ownership
        if ($pendaftaran->mahasiswa_id !== auth()->id()) {
            abort(403);
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('dokumen_sidang/' . $pendaftaran->id, $filename, 'public');

        Dokumen::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_file' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'ukuran' => $file->getSize()
        ]);

        return back()->with('success', 'Dokumen berhasil diupload');
    }

    public function destroy(Dokumen $dokumen)
    {
        // Verify ownership
        if ($dokumen->pendaftaran->mahasiswa_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($dokumen->path);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }

    public function download(Dokumen $dokumen)
    {
        // Verify ownership or access
        if ($dokumen->pendaftaran->mahasiswa_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403);
        }

        return Storage::disk('public')->download($dokumen->path, $dokumen->nama_file);
    }
}