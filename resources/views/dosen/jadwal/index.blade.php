@extends('layouts.app')

@section('title', 'Jadwal Sidang Saya')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jadwal Sidang Saya
        </h2>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter -->
    <div class="bg-white shadow-md rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('dosen.jadwal.index') }}" class="flex gap-3">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">Semua Status</option>
                <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                Filter
            </button>
            @if(request('status'))
                <a href="{{ route('dosen.jadwal.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Jadwal yang sudah selesai penilaian akan otomatis berpindah ke menu <strong>Riwayat</strong>.
                </p>
            </div>
        </div>
    </div>

    <!-- Jadwal Cards -->
    <div class="space-y-4">
        @forelse($jadwals as $jadwal)
            @php
                $myPenguji = $jadwal->penguji->firstWhere('dosen_id', auth()->id());
                // Check if has penilaian
                $hasPenilaian = $myPenguji && $myPenguji->penilaian;
                // Get progress
                $progress = $jadwal->getPenilaianProgress();
                $submitted = $jadwal->getSubmittedPenilaianCount();
                $total = $jadwal->getTotalPengujiCount();
            @endphp
            
            <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $jadwal->pendaftaran->mahasiswa->name ?? '-' }}
                            </h3>
                            
                            <!-- Status Badge -->
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($jadwal->status == 'terjadwal' || $jadwal->status == 'scheduled') bg-blue-100 text-blue-800
                                @elseif($jadwal->status == 'ongoing') bg-yellow-100 text-yellow-800
                                @elseif($jadwal->status == 'completed') bg-green-100 text-green-800
                                @elseif($jadwal->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $jadwal->status_label }}
                            </span>

                            <!-- Penilaian Status Badge -->
                            @if($hasPenilaian)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    ✓ Sudah Dinilai
                                </span>
                            @else
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                                    ⏳ Belum Dinilai
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $jadwal->pendaftaran->mahasiswa->nim ?? '-' }} • 
                            {{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}
                        </p>
                        
                        <p class="text-gray-800 mb-3">
                            {{ Str::limit($jadwal->pendaftaran->judul ?? '-', 100) }}
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

                        <!-- Progress Penilaian -->
                        @if($total > 0)
                            <div class="mt-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-600">Progress Penilaian</span>
                                    <span class="text-xs font-semibold text-gray-700">{{ $submitted }}/{{ $total }} Penguji</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        @if($progress == 100) bg-green-500
                                        @elseif($progress >= 50) bg-blue-500
                                        @else bg-yellow-500
                                        @endif" 
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Peran Saya -->
                        <div class="mt-3 flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-medium text-gray-600">Peran Anda:</span>
                            @if($myPenguji)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    Penguji {{ ucfirst(str_replace('_', ' ', $myPenguji->peran)) }}
                                </span>
                                @if(isset($myPenguji->status))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($myPenguji->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($myPenguji->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($myPenguji->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $myPenguji->status_label }}
                                    </span>
                                @endif
                            @else
                                <span class="text-sm text-gray-500 italic">Tidak ada peran sebagai penguji</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 ml-4">
                        <a href="{{ route('dosen.jadwal.show', $jadwal) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium text-center transition whitespace-nowrap">
                            Detail
                        </a>
                        
                        @if($myPenguji)
                            <!-- Button Nilai (jika belum dinilai) -->
                            @if(!$hasPenilaian)
                                <a href="{{ route('dosen.penilaian.show', $jadwal) }}" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium text-center transition whitespace-nowrap">
                                    Beri Nilai
                                </a>
                            @else
                                <a href="{{ route('dosen.penilaian.show', $jadwal) }}" 
                                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium text-center transition whitespace-nowrap">
                                    Lihat Nilai
                                </a>
                            @endif

                            <!-- Button Konfirmasi (jika pending) -->
                            @if(isset($myPenguji->status) && $myPenguji->status == 'pending')
                                <form action="{{ route('dosen.jadwal.konfirmasi', $jadwal) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin mengkonfirmasi jadwal ini?')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium transition">
                                        Konfirmasi Kehadiran
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow-md rounded-xl p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium">Belum ada jadwal sidang</p>
                <p class="text-gray-500 text-sm mt-2">Jadwal yang Anda ikuti sebagai penguji akan muncul di sini</p>
                <p class="text-gray-400 text-xs mt-2">Jadwal yang sudah selesai dinilai akan otomatis berpindah ke menu Riwayat</p>
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