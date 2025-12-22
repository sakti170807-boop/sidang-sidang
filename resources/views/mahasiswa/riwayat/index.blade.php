@extends('layouts.app')

@section('title', 'Riwayat Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Sidang Saya
        </h2>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Riwayat sidang yang sudah selesai dan semua penguji telah memberikan penilaian akan tampil di sini.
                </p>
            </div>
        </div>
    </div>

    <!-- Riwayat Cards -->
    <div class="space-y-4">
        @forelse($riwayats as $jadwal)
            @php
                $nilaiRataRata = $jadwal->getAverageNilai();
                $totalPenguji = $jadwal->penguji->count();
            @endphp
            
            <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $jadwal->pendaftaran->kategoriSidang->nama }}
                            </h3>
                            
                            <!-- Status Badge -->
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                ✓ Selesai
                            </span>

                            <!-- Nilai Badge -->
                            @if($nilaiRataRata)
                                @php
                                    $badgeColor = 'gray';
                                    if($nilaiRataRata >= 80) $badgeColor = 'green';
                                    elseif($nilaiRataRata >= 70) $badgeColor = 'blue';
                                    elseif($nilaiRataRata >= 60) $badgeColor = 'yellow';
                                    else $badgeColor = 'red';
                                @endphp
                                <span class="px-3 py-1 bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800 rounded-full text-xs font-semibold">
                                    Nilai: {{ number_format($nilaiRataRata, 2) }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-gray-800 mb-3 font-medium">
                            {{ $jadwal->pendaftaran->judul }}
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }} WIB
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $jadwal->ruangSidang->nama ?? '-' }}
                            </div>
                        </div>

                        <!-- Tim Penguji -->
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-600 mb-2">Tim Penguji ({{ $totalPenguji }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($jadwal->penguji as $penguji)
                                    <div class="flex items-center gap-1 px-3 py-1 bg-gray-100 rounded-full text-xs">
                                        <span class="font-medium">{{ $penguji->dosen->name }}</span>
                                        <span class="text-gray-500">- {{ ucfirst($penguji->peran) }}</span>
                                        @if($penguji->penilaian)
                                            <svg class="w-3 h-3 text-green-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 ml-4">
                        <a href="{{ route('mahasiswa.riwayat.show', $jadwal) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium text-center transition whitespace-nowrap">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow-md rounded-xl p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium">Belum ada riwayat sidang</p>
                <p class="text-gray-500 text-sm mt-2">Sidang yang sudah selesai akan muncul di sini</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($riwayats->hasPages())
        <div class="mt-6">
            {{ $riwayats->links() }}
        </div>
    @endif
</div>
@endsection