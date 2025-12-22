@extends('layouts.app')

@section('title', 'Jadwal Sidang Saya')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-2xl text-gray-800">Jadwal Sidang Saya</h2>
            <p class="text-gray-600 mt-1">Lihat jadwal sidang yang sudah terjadwal</p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Hanya menampilkan jadwal yang <strong>belum selesai</strong>. Jadwal yang sudah selesai dapat dilihat di menu <strong>Riwayat</strong> atau <strong>Hasil Sidang</strong>.
                </p>
            </div>
        </div>
    </div>

    <!-- List Jadwal -->
    <div class="space-y-4">
        @forelse($jadwals as $jadwal)
            @php
                $isOngoing = $jadwal->status === 'ongoing';
                $now = \Carbon\Carbon::now();
                $startTime = \Carbon\Carbon::parse($jadwal->tanggal_mulai);
                $endTime = \Carbon\Carbon::parse($jadwal->tanggal_selesai);
                $isUpcoming = $startTime->isFuture();
                $isPast = $endTime->isPast() && !$isOngoing;
            @endphp
            
            <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition
                {{ $isOngoing ? 'ring-2 ring-yellow-400' : '' }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center flex-wrap gap-2 mb-3">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}
                                </h3>
                                
                                <!-- Status Badge -->
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($jadwal->status === 'scheduled' || $jadwal->status === 'terjadwal') bg-blue-100 text-blue-800
                                    @elseif($jadwal->status === 'ongoing') bg-yellow-100 text-yellow-800
                                    @elseif($jadwal->status === 'completed' || $jadwal->status === 'selesai') bg-green-100 text-green-800
                                    @elseif($jadwal->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($jadwal->status) }}
                                </span>

                                <!-- Live Badge jika sedang berlangsung -->
                                @if($isOngoing)
                                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">
                                        🔴 LIVE
                                    </span>
                                @endif

                                <!-- Upcoming Badge -->
                                @if($isUpcoming && !$isOngoing)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                        📅 Akan Datang
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Judul -->
                            <p class="text-gray-800 font-medium mb-3">
                                {{ Str::limit($jadwal->pendaftaran->judul ?? '-', 120) }}
                            </p>
                            
                            <!-- Info Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm mb-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}</span>
                                </div>
                                
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }} WIB</span>
                                </div>
                                
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>{{ $jadwal->ruangSidang->nama ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Countdown or Time Info -->
                            @if($isUpcoming)
                                @php
                                    $diff = $now->diff($startTime);
                                    $days = $diff->days;
                                    $hours = $diff->h;
                                @endphp
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-900">
                                            Sidang akan dimulai dalam 
                                            @if($days > 0)
                                                <strong>{{ $days }} hari {{ $hours }} jam</strong>
                                            @elseif($hours > 0)
                                                <strong>{{ $hours }} jam</strong>
                                            @else
                                                <strong>kurang dari 1 jam</strong>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if($isOngoing)
                                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-300 rounded-lg p-3 mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-yellow-600 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                        <span class="text-sm font-bold text-yellow-900">
                                            Sidang sedang berlangsung!
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Pembimbing -->
                            @if($jadwal->pendaftaran->pembimbing && $jadwal->pendaftaran->pembimbing->count() > 0)
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-500 mb-2">Pembimbing:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($jadwal->pendaftaran->pembimbing as $pembimbing)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs">
                                                {{ $pembimbing->dosen->name ?? 'N/A' }}
                                                <span class="text-[10px]">({{ ucfirst($pembimbing->jenis) }})</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Penguji -->
                            @if($jadwal->penguji && $jadwal->penguji->count() > 0)
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-500 mb-2">Tim Penguji:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($jadwal->penguji as $penguji)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-md text-xs">
                                                {{ $penguji->dosen->name ?? 'N/A' }}
                                                <span class="text-[10px]">({{ ucfirst(str_replace('_', ' ', $penguji->peran)) }})</span>
                                                @if($penguji->penilaian)
                                                    <span class="ml-1">✓</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Progress Penilaian -->
                            @if($jadwal->penguji && $jadwal->penguji->count() > 0)
                                @php
                                    $totalPenguji = $jadwal->penguji->count();
                                    $sudahNilai = $jadwal->penguji->filter(fn($p) => $p->penilaian)->count();
                                    $progress = $totalPenguji > 0 ? round(($sudahNilai / $totalPenguji) * 100) : 0;
                                @endphp
                                <div class="mt-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-medium text-gray-600">Progress Penilaian</span>
                                        <span class="text-xs font-semibold text-gray-700">{{ $sudahNilai }}/{{ $totalPenguji }} Penguji</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-300
                                            {{ $progress == 100 ? 'bg-green-500' : ($progress >= 50 ? 'bg-blue-500' : 'bg-yellow-500') }}" 
                                            style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('mahasiswa.jadwal.show', $jadwal) }}" 
                           class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition whitespace-nowrap flex items-center shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow-md rounded-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-600 text-lg font-medium mb-2">Belum ada jadwal sidang</p>
                <p class="text-gray-500 text-sm">Jadwal akan muncul setelah admin menjadwalkan sidang Anda</p>
                <p class="text-gray-400 text-xs mt-2">Pastikan pendaftaran Anda sudah diverifikasi</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jadwals->hasPages())
        <div class="mt-6 bg-white rounded-xl shadow-md p-4">
            {{ $jadwals->links() }}
        </div>
    @endif
</div>
@endsection