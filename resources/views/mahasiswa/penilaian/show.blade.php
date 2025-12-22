@extends('layouts.app')

@section('title', 'Detail Hasil Penilaian')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Detail Hasil Penilaian</h2>
                <p class="text-gray-600 mt-1">Hasil penilaian sidang Anda</p>
            </div>
            <a href="{{ route('mahasiswa.penilaian.index') }}" 
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Kembali
            </a>
        </div>

        <!-- Informasi Sidang -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Informasi Sidang</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kategori Sidang</label>
                        <p class="text-gray-900 font-medium">{{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Sidang</label>
                        <p class="text-gray-900">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }} WIB
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Ruang Sidang</label>
                        <p class="text-gray-900">{{ $jadwal->ruangSidang->nama ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Selesai
                        </span>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Judul</label>
                        <p class="text-gray-900">{{ $jadwal->pendaftaran->judul ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nilai Akhir Summary -->
        @if($nilaiAkhir)
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-8 mb-6 border-2 border-green-300">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Nilai Akhir Sidang</h3>
                    <p class="text-gray-600">Rata-rata dari seluruh penguji</p>
                </div>
                
                <div class="flex items-center justify-center gap-12">
                    <div class="text-center">
                        <p class="text-6xl font-bold text-green-600 mb-2">{{ number_format($nilaiAkhir, 2) }}</p>
                        <p class="text-gray-600 font-medium">Dari skala 100</p>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-3xl font-bold mb-2 {{ $nilaiAkhir >= 70 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $nilaiAkhir >= 70 ? '✓ LULUS' : '✗ TIDAK LULUS' }}
                        </p>
                        <p class="text-gray-600">Status Kelulusan</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Penilaian Per Penguji -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Penilaian dari Setiap Penguji</h3>
            </div>
            
            <div class="p-6 space-y-6">
                @forelse($jadwal->penguji as $index => $penguji)
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-300 transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $penguji->dosen->name ?? '-' }}</h4>
                                    <p class="text-sm text-gray-600">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                            {{ ucfirst(str_replace('_', ' ', $penguji->peran)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            @if($penguji->penilaian)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    ✓ Sudah Dinilai
                                </span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⏱ Belum Dinilai
                                </span>
                            @endif
                        </div>

                        @if($penguji->penilaian)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <p class="text-sm text-blue-700 font-medium mb-1">Presentasi</p>
                                    <p class="text-2xl font-bold text-blue-700">{{ number_format($penguji->penilaian->nilai_presentasi, 2) }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                                    <p class="text-sm text-yellow-700 font-medium mb-1">Materi</p>
                                    <p class="text-2xl font-bold text-yellow-700">{{ number_format($penguji->penilaian->nilai_materi, 2) }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                    <p class="text-sm text-purple-700 font-medium mb-1">Diskusi</p>
                                    <p class="text-2xl font-bold text-purple-700">{{ number_format($penguji->penilaian->nilai_diskusi, 2) }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 border-2 border-green-300">
                                    <p class="text-sm text-green-700 font-medium mb-1">Rata-rata</p>
                                    <p class="text-2xl font-bold text-green-700">{{ number_format($penguji->penilaian->nilai_akhir, 2) }}</p>
                                </div>
                            </div>

                            @if($penguji->penilaian->catatan)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">💬 Catatan dari Penguji:</p>
                                    <p class="text-gray-900 text-sm whitespace-pre-wrap">{{ $penguji->penilaian->catatan }}</p>
                                </div>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <p class="text-yellow-800 text-sm">
                                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Penilaian dari penguji ini masih dalam proses
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p>Data penguji tidak tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection