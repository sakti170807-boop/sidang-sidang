<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuangSidang;
use Illuminate\Http\Request;

class RuangSidangController extends Controller
{
    public function index()
    {
        $ruangans = RuangSidang::latest()->get();
        
        return view('admin.ruang-sidang.index', compact('ruangans'));
    }

    public function create()
    {
        return view('admin.ruang-sidang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
            'fasilitas' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set is_active default to false if not checked
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        RuangSidang::create($validated);

        return redirect()->route('admin.ruang-sidang.index')
            ->with('success', 'Ruang sidang berhasil ditambahkan');
    }

    public function show($id)
    {
        $ruang = RuangSidang::findOrFail($id);
        
        return view('admin.ruang-sidang.show', compact('ruang'));
    }

    public function edit($id)
    {
        $ruang = RuangSidang::findOrFail($id);
        
        return view('admin.ruang-sidang.edit', compact('ruang', 'id'));
    }

    public function update(Request $request, $id)
    {
        $ruang = RuangSidang::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
            'fasilitas' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Set is_active default to false if not checked
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $ruang->update($validated);

        return redirect()->route('admin.ruang-sidang.index')
            ->with('success', 'Ruang sidang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ruang = RuangSidang::findOrFail($id);
        
        // Cek apakah ruangan sedang digunakan di jadwal
        if ($ruang->jadwalSidang()->exists()) {
            return redirect()->route('admin.ruang-sidang.index')
                ->withErrors(['message' => 'Ruang sidang tidak dapat dihapus karena masih digunakan di jadwal']);
        }

        $ruang->delete();

        return redirect()->route('admin.ruang-sidang.index')
            ->with('success', 'Ruang sidang berhasil dihapus');
    }
}