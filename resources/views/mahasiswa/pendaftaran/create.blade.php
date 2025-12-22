@extends('layouts.app')

@section('title', 'Buat Pendaftaran Sidang')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Pendaftaran Sidang Baru
        </h2>
        <a href="{{ route('mahasiswa.pendaftaran.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <!-- Form Buat Pendaftaran -->
    <div class="bg-white shadow-md rounded-xl p-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('mahasiswa.pendaftaran.store') }}" method="POST" enctype="multipart/form-data" id="form-pendaftaran">
            @csrf

            <!-- Kategori Sidang -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">
                    Kategori Sidang <span class="text-red-500">*</span>
                </label>
                <select name="kategori_sidang_id" id="kategori_sidang_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" 
                                data-dokumen="{{ json_encode($kategori->dokumen_wajib ?? []) }}"
                                {{ old('kategori_sidang_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Pilih kategori sidang yang sesuai</p>
            </div>

            <!-- Judul Sidang -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">
                    Judul Sidang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" value="{{ old('judul') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                       placeholder="Masukkan judul sidang"
                       maxlength="500"
                       required>
                <p class="text-sm text-gray-500 mt-1">Maksimal 500 karakter</p>
            </div>

            <!-- Abstrak -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">
                    Abstrak <span class="text-red-500">*</span>
                </label>
                <textarea name="abstrak" rows="5" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                          placeholder="Masukkan abstrak sidang Anda"
                          required>{{ old('abstrak') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Jelaskan ringkasan dari penelitian Anda</p>
            </div>

            <!-- Pembimbing Utama -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">
                    Pembimbing Utama <span class="text-red-500">*</span>
                </label>
                <select name="pembimbing_utama_id" id="pembimbing_utama_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        required>
                    <option value="">-- Pilih Pembimbing Utama --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" 
                            {{ old('pembimbing_utama_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pembimbing Pendamping -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-medium">
                    Pembimbing Pendamping <span class="text-gray-500">(Opsional)</span>
                </label>
                <select name="pembimbing_pendamping_id" id="pembimbing_pendamping_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">-- Pilih Pembimbing Pendamping --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" 
                            {{ old('pembimbing_pendamping_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Upload Dokumen -->
            <div class="mb-6 border-t border-gray-200 pt-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4 text-lg">Upload Dokumen Pendukung</h3>
                <div id="dokumen-container" class="space-y-3 mb-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-700">Pilih kategori sidang terlebih dahulu untuk melihat dokumen yang wajib diunggah</p>
                    </div>
                </div>
                <button type="button" id="add-dokumen" style="display: none;" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Dokumen Lainnya
                </button>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    Simpan sebagai Draft
                </button>
                <a href="{{ route('mahasiswa.pendaftaran.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Daftar Pendaftaran Sebelumnya -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-gray-800 mb-4 text-lg">Pendaftaran Sidang Sebelumnya</h3>
        @forelse($pendaftarans as $p)
            <div class="border-b border-gray-200 py-3 last:border-0">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <a href="{{ route('mahasiswa.pendaftaran.show', $p) }}" 
                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                            {{ $p->judul }}
                        </a>
                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <span>{{ $p->kategoriSidang->nama ?? '-' }}</span>
                            <span>•</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($p->status == 'draft') bg-gray-100 text-gray-600
                                @elseif($p->status == 'submitted') bg-blue-100 text-blue-600
                                @elseif($p->status == 'approved') bg-green-100 text-green-600
                                @elseif($p->status == 'rejected') bg-red-100 text-red-600
                                @endif
                            ">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap">
                        {{ $p->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600">Belum ada pendaftaran sebelumnya.</p>
            </div>
        @endforelse

        @if($pendaftarans->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-200">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>
</div>

<script>
(function() {
    'use strict';
    
    let dokumenCounter = 0;
    let dokumenWajib = [];
    
    const kategoriSelect = document.getElementById('kategori_sidang_id');
    const dokumenContainer = document.getElementById('dokumen-container');
    const addDokumenBtn = document.getElementById('add-dokumen');
    const pembimbingUtama = document.getElementById('pembimbing_utama_id');
    const pembimbingPendamping = document.getElementById('pembimbing_pendamping_id');

    function createDokumenInput(jenisLabel, isRequired, index) {
        const id = index !== null && index !== undefined ? index : dokumenCounter++;
        
        const div = document.createElement('div');
        div.className = 'border border-gray-300 rounded-lg p-4 bg-white shadow-sm dokumen-item';
        
        const labelText = jenisLabel || 'Jenis Dokumen';
        const requiredMark = isRequired ? '<span class="text-red-500">*</span>' : '';
        const removeButton = !isRequired ? '<button type="button" class="text-red-600 hover:text-red-800 text-sm font-medium remove-dokumen">Hapus</button>' : '';
        
        div.innerHTML = `
            <div class="flex justify-between items-start mb-3">
                <label class="block text-sm font-medium text-gray-700">
                    ${labelText} ${requiredMark}
                </label>
                ${removeButton}
            </div>
            <input type="text" 
                   name="dokumen[${id}][jenis]" 
                   value="${jenisLabel || ''}" 
                   placeholder="Contoh: Transkrip Nilai, KRS, dll"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md mb-3 text-sm ${jenisLabel ? 'bg-gray-50' : ''} outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                   ${isRequired || jenisLabel ? 'readonly' : ''}
                   ${isRequired ? 'required' : ''}>
            <input type="file" 
                   name="dokumen[${id}][file]" 
                   accept=".pdf,.doc,.docx"
                   class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer"
                   ${isRequired ? 'required' : ''}>
            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Format: PDF, DOC, DOCX (Max 2MB)
            </p>
        `;
        
        const removeBtn = div.querySelector('.remove-dokumen');
        if (removeBtn) {
            removeBtn.onclick = function() {
                div.remove();
            };
        }
        
        return div;
    }

    function updateDokumenContainer() {
        const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
        dokumenWajib = [];
        
        if (selectedOption && selectedOption.value) {
            try {
                const dokumenData = selectedOption.getAttribute('data-dokumen');
                dokumenWajib = dokumenData ? JSON.parse(dokumenData) : [];
            } catch (e) {
                console.error('Error parsing dokumen:', e);
                dokumenWajib = [];
            }
        }
        
        dokumenContainer.innerHTML = '';
        dokumenCounter = 0;
        
        if (dokumenWajib.length > 0) {
            dokumenWajib.forEach(function(jenis) {
                dokumenContainer.appendChild(createDokumenInput(jenis, true, null));
            });
            addDokumenBtn.style.display = 'inline-flex';
        } else if (selectedOption && selectedOption.value) {
            dokumenContainer.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-sm text-yellow-700">Tidak ada dokumen wajib untuk kategori ini. Anda dapat menambahkan dokumen pendukung secara opsional.</p>
                </div>
            `;
            addDokumenBtn.style.display = 'inline-flex';
        } else {
            dokumenContainer.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-700">Pilih kategori sidang terlebih dahulu untuk melihat dokumen yang wajib diunggah</p>
                </div>
            `;
            addDokumenBtn.style.display = 'none';
        }
    }

    kategoriSelect.onchange = updateDokumenContainer;
    
    addDokumenBtn.onclick = function() {
        dokumenContainer.appendChild(createDokumenInput('', false, null));
    };

    pembimbingPendamping.onchange = function() {
        if (this.value && this.value === pembimbingUtama.value) {
            alert('Pembimbing pendamping tidak boleh sama dengan pembimbing utama!');
            this.value = '';
        }
    };

    pembimbingUtama.onchange = function() {
        if (pembimbingPendamping.value && this.value === pembimbingPendamping.value) {
            alert('Pembimbing utama tidak boleh sama dengan pembimbing pendamping!');
            pembimbingPendamping.value = '';
        }
    };

    // Trigger saat halaman load jika ada kategori terpilih
    if (kategoriSelect.value) {
        updateDokumenContainer();
    }
})();
</script>

@endsection