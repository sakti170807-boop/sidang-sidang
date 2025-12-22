@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')

<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->full_name }}!</h3>
        <p class="text-blue-100">{{ Auth::user()->programStudi->nama ?? 'Dosen' }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Mahasiswa Bimbingan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['mahasiswa_bimbingan'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['menunggu_verifikasi'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Jadwal Menguji</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['jadwal_menguji'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Menunggu Penilaian</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['menunggu_penilaian'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Verifikasi -->
    @if($pendingVerifikasi->count() > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Menunggu Verifikasi Anda ({{ $pendingVerifikasi->count() }})
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($pendingVerifikasi as $pembimbing)
                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4 hover:shadow-md transition duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="bg-blue-100 rounded-full p-2">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $pembimbing->pendaftaran->mahasiswa->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $pembimbing->pendaftaran->mahasiswa->nim }}</p>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-700">{{ $pembimbing->pendaftaran->judul }}</p>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $pembimbing->pendaftaran->kategoriSidang->nama }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $pembimbing->created_at->diffForHumans() }}
                                    </span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-semibold">
                                        Pembimbing {{ ucfirst($pembimbing->jenis) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('dosen.pendaftaran.show', $pembimbing->pendaftaran_id) }}" 
                               class="ml-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-150 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Review
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('dosen.pendaftaran.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Semua Pendaftaran →
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Jadwal Mendatang -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Jadwal Sidang Mendatang ({{ $jadwalMendatang->count() }})
            </h3>
        </div>
        <div class="p-6">
            @if($jadwalMendatang->count() > 0)
                <div class="space-y-4">
                    @foreach($jadwalMendatang as $jadwal)
                        @php
                            $myPenguji = $jadwal->penguji->firstWhere('dosen_id', auth()->id());
                            $hasPenilaian = $myPenguji && $myPenguji->penilaian;
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-150 hover:border-green-300">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-3 flex-wrap">
                                        <!-- Tanggal & Waktu -->
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y, H:i') }} WIB
                                        </span>
                                        
                                        <!-- Ruangan -->
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                            {{ $jadwal->ruangSidang->nama }}
                                        </span>
                                        
                                        <!-- Peran Penguji -->
                                        @if($myPenguji)
                                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                                Penguji {{ ucfirst(str_replace('_', ' ', $myPenguji->peran)) }}
                                            </span>
                                        @endif

                                        <!-- Status Penilaian -->
                                        @if($hasPenilaian)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Sudah Dinilai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="bg-gray-100 rounded-full p-2">
                                            <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $jadwal->pendaftaran->mahasiswa->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $jadwal->pendaftaran->mahasiswa->nim }}</p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-700 font-medium mb-2">{{ Str::limit($jadwal->pendaftaran->judul, 100) }}</p>
                                    
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $jadwal->pendaftaran->kategoriSidang->nama }}
                                    </div>
                                </div>
                                
                                <div class="ml-4 flex flex-col gap-2">
                                    <a href="{{ route('dosen.jadwal.show', $jadwal) }}" 
                                       class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition duration-150 flex items-center whitespace-nowrap justify-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                    
                                    @if($myPenguji && !$hasPenilaian)
                                        <a href="{{ route('dosen.penilaian.show', $jadwal) }}" 
                                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-150 flex items-center whitespace-nowrap justify-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            Beri Nilai
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('dosen.jadwal.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                        Lihat Semua Jadwal →
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada jadwal sidang mendatang</p>
                    <p class="text-gray-400 text-sm mt-2">Jadwal akan muncul ketika admin membuat penjadwalan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('dosen.pendaftaran.index') }}" 
           class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-150 group">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 group-hover:bg-blue-200 transition duration-150">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="font-semibold text-gray-900">Verifikasi Pendaftaran</h4>
                    <p class="text-sm text-gray-500">Review pendaftaran mahasiswa</p>
                </div>
            </div>
        </a>

        <a href="{{ route('dosen.penilaian.index') }}" 
           class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-150 group">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 group-hover:bg-green-200 transition duration-150">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="font-semibold text-gray-900">Input Penilaian</h4>
                    <p class="text-sm text-gray-500">Berikan nilai sidang</p>
                </div>
            </div>
        </a>

        <a href="{{ route('dosen.riwayat.index') }}" 
           class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-150 group">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 group-hover:bg-purple-200 transition duration-150">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="font-semibold text-gray-900">Riwayat Sidang</h4>
                    <p class="text-sm text-gray-500">Lihat history sidang</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection