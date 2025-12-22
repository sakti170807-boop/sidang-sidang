@extends('layouts.app')

@section('title', 'Detail Riwayat Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('mahasiswa.riwayat.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>

    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-600 to-green-600 rounded-xl shadow-lg p-6 text-white mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-semibold">
                ✓ Selesai
            </span>
            <span class="text-teal-100">
                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}
            </span>
        </div>
        <h2 class="text-2xl font-bold mb-2">{{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}</h2>
        <p class="text-teal-100">{{ $jadwal->pendaftaran->judul }}</p>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Sidang -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sidang</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <div class="w-32 text-gray-600">Tanggal</div>
                        <div class="flex-1 font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-600">Waktu</div>
                        <div class="flex-1 font-medium">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }} WIB
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-600">Ruangan</div>
                        <div class="flex-1 font-medium">{{ $jadwal->ruangSidang->nama ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-32 text-gray-600">Status</div>
                        <div class="flex-1">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                Selesai
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim Penguji & Penilaian -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tim Penguji & Penilaian</h3>
                <div class="space-y-3">
                    @forelse($jadwal->penguji as $penguji)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $penguji->dosen->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $penguji->peran)) }}</p>
                                </div>
                                @if($penguji->penilaian)
                                    <div class="text-right">
                                        <div class="bg-blue-50 rounded-lg px-4 py-2">
                                            <p class="text-3xl font-bold text-blue-600">{{ $penguji->penilaian->nilai }}</p>
                                            <p class="text-xs text-blue-700 font-medium">Nilai</p>
                                        </div>
                                        @php
                                            $nilai = $penguji->penilaian->nilai;
                                            if ($nilai >= 85) {
                                                $predikat = 'A';
                                                $warna = 'text-green-600';
                                            } elseif ($nilai >= 70) {
                                                $predikat = 'B';
                                                $warna = 'text-blue-600';
                                            } elseif ($nilai >= 60) {
                                                $predikat = 'C';
                                                $warna = 'text-yellow-600';
                                            } elseif ($nilai >= 50) {
                                                $predikat = 'D';
                                                $warna = 'text-orange-600';
                                            } else {
                                                $predikat = 'E';
                                                $warna = 'text-red-600';
                                            }
                                        @endphp
                                        <p class="text-xs {{ $warna }} font-bold mt-1">Grade: {{ $predikat }}</p>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm">Belum dinilai</span>
                                @endif
                            </div>
                            @if($penguji->penilaian && $penguji->penilaian->catatan)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1 font-medium">Catatan Penguji:</p>
                                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $penguji->penilaian->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Tidak ada data penguji</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Nilai Rata-rata -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Nilai Akhir</p>
                        <p class="text-6xl font-bold leading-none">{{ $nilaiRataRata }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-200 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
                
                @php
                    $nilaiAkhir = $nilaiRataRata;
                    if ($nilaiAkhir >= 85) {
                        $grade = 'A';
                        $gradeLabel = 'Sangat Memuaskan';
                        $gradeBg = 'bg-green-500';
                    } elseif ($nilaiAkhir >= 70) {
                        $grade = 'B';
                        $gradeLabel = 'Memuaskan';
                        $gradeBg = 'bg-blue-500';
                    } elseif ($nilaiAkhir >= 60) {
                        $grade = 'C';
                        $gradeLabel = 'Cukup';
                        $gradeBg = 'bg-yellow-500';
                    } elseif ($nilaiAkhir >= 50) {
                        $grade = 'D';
                        $gradeLabel = 'Kurang';
                        $gradeBg = 'bg-orange-500';
                    } else {
                        $grade = 'E';
                        $gradeLabel = 'Tidak Lulus';
                        $gradeBg = 'bg-red-500';
                    }
                @endphp
                
                <div class="border-t border-blue-400 pt-3 mb-3">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100 text-sm">Grade</span>
                        <span class="text-2xl font-bold">{{ $grade }}</span>
                    </div>
                    <p class="text-blue-100 text-xs mt-1">{{ $gradeLabel }}</p>
                </div>
                
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <p class="text-blue-100 text-xs">Berdasarkan rata-rata dari</p>
                    <p class="text-white font-semibold">{{ $jadwal->penguji->count() }} Penguji</p>
                </div>
            </div>

            <!-- Detail Nilai per Penguji -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Nilai</h3>
                <div class="space-y-2">
                    @foreach($jadwal->penguji as $index => $penguji)
                        @if($penguji->penilaian)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Penguji {{ $index + 1 }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($penguji->dosen->name ?? 'N/A', 20) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-blue-600">{{ $penguji->penilaian->nilai }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    
                    @if($jadwal->penguji->filter(fn($p) => $p->penilaian)->count() > 0)
                        <div class="flex items-center justify-between py-2 border-t-2 border-gray-300 mt-2 pt-3">
                            <p class="text-sm font-bold text-gray-800">Rata-rata</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $nilaiRataRata }}</p>
                        </div>
                    @else
                        <p class="text-center text-gray-500 text-sm py-4">Belum ada penilaian</p>
                    @endif
                </div>
            </div>

            <!-- Pembimbing -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembimbing</h3>
                <div class="space-y-3">
                    @forelse($jadwal->pendaftaran->pembimbing as $pembimbing)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="bg-blue-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $pembimbing->dosen->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Pembimbing {{ ucfirst($pembimbing->jenis) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">Tidak ada data pembimbing</p>
                    @endforelse
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumen & Detail</h3>
                <div class="space-y-2">
                    <a href="{{ route('mahasiswa.hasil.show', $jadwal) }}" 
                       class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Lihat Berita Acara
                    </a>
                    @if($nilaiRataRata > 0)
                        <a href="{{ route('mahasiswa.penilaian.show', $jadwal) }}" 
                           class="flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Detail Penilaian Lengkap
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection 