@extends('layouts.app')

@section('title', 'Detail Penilaian Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Penilaian Sidang
        </h2>
        <a href="{{ route('dosen.penilaian.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Student Info Card -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sidang</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Mahasiswa</label>
                <p class="text-gray-900">{{ $jadwal->pendaftaran->mahasiswa->name ?? '-' }}</p>
                <p class="text-gray-500 text-sm">{{ $jadwal->pendaftaran->mahasiswa->nim ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Program Studi</label>
                <p class="text-gray-900">{{ $jadwal->pendaftaran->mahasiswa->programStudi->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tanggal Sidang</label>
                <p class="text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Waktu</label>
                <p class="text-gray-900">
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Ruang</label>
                <p class="text-gray-900">{{ $jadwal->ruangSidang->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Kategori Sidang</label>
                <p class="text-gray-900">{{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Judul</label>
                <p class="text-gray-900">{{ $jadwal->pendaftaran->judul ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Peran Anda</label>
                @if($penguji)
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ ucfirst(str_replace('_', ' ', $penguji->peran)) }}
                    </span>
                @else
                    <p class="text-gray-900">-</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Penilaian -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Penilaian</h3>
        
        @if($penilaian)
            <!-- Already graded -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium">Nilai sudah diberikan</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nilai Presentasi</label>
                    <p class="text-2xl font-bold text-blue-700">{{ $penilaian->nilai_presentasi }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nilai Materi</label>
                    <p class="text-2xl font-bold text-yellow-700">{{ $penilaian->nilai_materi }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nilai Diskusi</label>
                    <p class="text-2xl font-bold text-purple-700">{{ $penilaian->nilai_diskusi }}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 mb-1">Nilai Akhir</label>
                <p class="text-3xl font-bold text-green-600">{{ $penilaian->nilai_akhir }}</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                <p class="text-gray-900">{{ $penilaian->catatan ?? '-' }}</p>
            </div>
        @else
            <!-- Form for grading -->
            <form action="{{ route('dosen.penilaian.store', $jadwal) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="nilai_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Nilai Presentasi</label>
                        <input type="number" step="0.01" min="0" max="100" name="nilai_presentasi" id="nilai_presentasi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        @error('nilai_presentasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nilai_materi" class="block text-sm font-medium text-gray-700 mb-1">Nilai Materi</label>
                        <input type="number" step="0.01" min="0" max="100" name="nilai_materi" id="nilai_materi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        @error('nilai_materi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nilai_diskusi" class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskusi</label>
                        <input type="number" step="0.01" min="0" max="100" name="nilai_diskusi" id="nilai_diskusi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        @error('nilai_diskusi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection