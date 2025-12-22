@extends('layouts.app')

@section('title', 'Buat Jadwal Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Buat Jadwal Sidang Baru</h2>
            <p class="text-gray-600 mt-1">Lengkapi form di bawah untuk membuat jadwal sidang</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.jadwal-sidang.store') }}" method="POST" class="bg-white rounded-xl shadow-md p-6">
            @csrf

            <!-- Pendaftaran Sidang -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pendaftaran Sidang *</label>
                <select name="pendaftaran_sidang_id" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih Mahasiswa</option>
                    @foreach($pendaftarans as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}" {{ old('pendaftaran_sidang_id') == $pendaftaran->id ? 'selected' : '' }}>
                            {{ $pendaftaran->mahasiswa->name }} - {{ $pendaftaran->mahasiswa->nim }} 
                            ({{ $pendaftaran->kategoriSidang->nama }})
                        </option>
                    @endforeach
                </select>
                @error('pendaftaran_sidang_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal & Waktu -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai *</label>
                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('waktu_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai *</label>
                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('waktu_selesai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ruang Sidang -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ruang Sidang *</label>
                <select name="ruang_sidang_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Ruang</option>
                    @foreach($ruangans as $ruangan)
                        <option value="{{ $ruangan->id }}" {{ old('ruang_sidang_id') == $ruangan->id ? 'selected' : '' }}>
                            {{ $ruangan->nama }} - {{ $ruangan->gedung ?? 'Gedung Utama' }}
                        </option>
                    @endforeach
                </select>
                @error('ruang_sidang_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BAGIAN PENTING: Dosen Penguji -->
            <div class="mb-6 border-t pt-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Dosen Penguji *</h3>
                        <p class="text-sm text-gray-600">Minimal pilih 1 dosen penguji</p>
                    </div>
                    <button type="button" onclick="addPenguji()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                        + Tambah Penguji
                    </button>
                </div>

                <div id="penguji-container" class="space-y-3">
                    <!-- Template penguji akan ditambahkan di sini via JavaScript -->
                </div>

                @error('penguji')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('admin.jadwal-sidang.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let pengujiIndex = 0;
const dosenList = @json($dosens);

// Tambah penguji otomatis saat halaman load (minimal 1)
document.addEventListener('DOMContentLoaded', function() {
    addPenguji(); // Tambah 1 penguji default
});

function addPenguji() {
    const container = document.getElementById('penguji-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-3 bg-gray-50 p-4 rounded-lg penguji-item';
    div.id = `penguji-${pengujiIndex}`;
    
    let dosenOptions = '<option value="">Pilih Dosen</option>';
    dosenList.forEach(dosen => {
        dosenOptions += `<option value="${dosen.id}">${dosen.name}</option>`;
    });
    
    div.innerHTML = `
        <div class="flex-1">
            <label class="block text-xs text-gray-600 mb-1">Dosen</label>
            <select name="penguji[${pengujiIndex}][dosen_id]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                ${dosenOptions}
            </select>
        </div>
        <div class="w-48">
            <label class="block text-xs text-gray-600 mb-1">Peran</label>
            <select name="penguji[${pengujiIndex}][peran]" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="ketua">Ketua</option>
                <option value="anggota">Anggota</option>
                <option value="sekretaris">Sekretaris</option>
            </select>
        </div>
        <button type="button" onclick="removePenguji(${pengujiIndex})"
                class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 mt-5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    
    container.appendChild(div);
    pengujiIndex++;
}

function removePenguji(index) {
    const element = document.getElementById(`penguji-${index}`);
    const container = document.getElementById('penguji-container');
    
    // Jangan hapus jika hanya ada 1 penguji
    if (container.querySelectorAll('.penguji-item').length <= 1) {
        alert('Minimal harus ada 1 dosen penguji!');
        return;
    }
    
    if (element) {
        element.remove();
    }
}

// Validasi sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const container = document.getElementById('penguji-container');
    const pengujiCount = container.querySelectorAll('.penguji-item').length;
    
    if (pengujiCount === 0) {
        e.preventDefault();
        alert('Minimal harus menambahkan 1 dosen penguji!');
        return false;
    }
});
</script>
@endsection