@extends('layouts.app')

@section('title', 'Hasil Penilaian Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-2xl text-gray-800">Hasil Penilaian Sidang</h2>
            <p class="text-gray-600 mt-1">Lihat hasil penilaian sidang yang telah Anda laksanakan</p>
        </div>
    </div>

    {{-- DEBUG INFO - HAPUS SETELAH SELESAI --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm font-mono">
            <strong>🔍 Debug Info:</strong><br>
            Total Jadwal: <strong>{{ $jadwals->total() }}</strong><br>
            Mahasiswa ID: <strong>{{ auth()->id() }}</strong><br>
            @php
                $totalJadwalCompleted = \App\Models\JadwalSidang::whereIn('status', ['completed', 'selesai'])->count();
                $totalPendaftaran = \App\Models\Pendaftaran::where('mahasiswa_id', auth()->id())->count();
                $jadwalMahasiswa = \App\Models\JadwalSidang::whereHas('pendaftaran', function($q) {
                    $q->where('mahasiswa_id', auth()->id());
                })->count();
                $jadwalMahasiswaCompleted = \App\Models\JadwalSidang::whereHas('pendaftaran', function($q) {
                    $q->where('mahasiswa_id', auth()->id());
                })->whereIn('status', ['completed', 'selesai'])->count();
            @endphp
            Total Jadwal Completed di DB: <strong>{{ $totalJadwalCompleted }}</strong><br>
            Total Pendaftaran Saya: <strong>{{ $totalPendaftaran }}</strong><br>
            Total Jadwal untuk Saya: <strong>{{ $jadwalMahasiswa }}</strong><br>
            Total Jadwal Completed untuk Saya: <strong>{{ $jadwalMahasiswaCompleted }}</strong>
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Sidang Selesai</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $jadwals->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sudah Ada Nilai</p>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ $jadwals->filter(function($j) {
                            return $j->penguji && $j->penguji->filter(fn($p) => $p->penilaian !== null)->count() > 0;
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Menunggu Nilai</p>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ $jadwals->filter(function($j) {
                            return !$j->penguji || $j->penguji->filter(fn($p) => $p->penilaian !== null)->count() === 0;
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- List Sidang -->
    <div class="space-y-4">
        @forelse($jadwals as $jadwal)
            @php
                $pengujiCollection = $jadwal->penguji ?? collect([]);
                $nilaiPenguji = $pengujiCollection->filter(fn($p) => $p->penilaian !== null);
                $totalPenguji = $pengujiCollection->count();
                $sudahDinilai = $nilaiPenguji->count();
                $nilaiAkhir = $sudahDinilai > 0 ? $nilaiPenguji->avg(fn($p) => $p->penilaian->nilai_akhir) : null;
            @endphp
            
            <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}
                                </h3>
                                @if($totalPenguji > 0)
                                    @if($sudahDinilai === $totalPenguji)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            ✓ Nilai Lengkap
                                        </span>
                                    @elseif($sudahDinilai > 0)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                            ⏱ Nilai Sebagian ({{ $sudahDinilai }}/{{ $totalPenguji }})
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                            ○ Belum Ada Nilai
                                        </span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        ⚠ Penguji Belum Ditentukan
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-800 mb-3">{{ Str::limit($jadwal->pendaftaran->judul ?? '-', 100) }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}
                                </div>
                                
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    {{ $jadwal->ruangSidang->nama ?? '-' }}
                                </div>
                            </div>

                            @if($nilaiAkhir)
                                <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Nilai Akhir (Rata-rata)</p>
                                            <p class="text-3xl font-bold text-green-600">{{ number_format($nilaiAkhir, 2) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600 mb-1">Status</p>
                                            <p class="text-lg font-semibold {{ $nilaiAkhir >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $nilaiAkhir >= 70 ? '✓ LULUS' : '✗ TIDAK LULUS' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('mahasiswa.penilaian.show', $jadwal) }}" 
                           class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition whitespace-nowrap flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow-md rounded-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium">Belum ada hasil penilaian</p>
                <p class="text-gray-500 text-sm mt-2">Hasil penilaian akan muncul setelah sidang selesai dilaksanakan</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jadwals->hasPages())
        <div class="mt-6">
            {{ $jadwals->links() }}
        </div>
    @endif
</div>
@endsection