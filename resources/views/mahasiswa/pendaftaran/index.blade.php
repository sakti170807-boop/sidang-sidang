@extends('layouts.app')

@section('title', 'Daftar Pendaftaran Sidang')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 py-6 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Pendaftaran Sidang
        </h2>
        <a href="{{ route('mahasiswa.pendaftaran.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Daftar Sidang Baru
        </a>
    </div>

    <!-- Success/Error Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-md p-2">
        <div class="flex space-x-2 overflow-x-auto">
            @php
                $statuses = [
                    '' => 'Semua',
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'verified_pembimbing' => 'Verif Pembimbing',
                    'verified_admin' => 'Verif Admin',
                    'scheduled' => 'Terjadwal',
                    'completed' => 'Selesai',
                    'rejected' => 'Ditolak',
                ];
                $currentStatus = request('status', '');
            @endphp

            @foreach($statuses as $key => $label)
                <a href="{{ route('mahasiswa.pendaftaran.index', $key ? ['status' => $key] : []) }}"
                   class="flex-shrink-0 px-4 py-2 text-center rounded-lg font-medium transition
                   {{ $currentStatus == $key ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Pendaftaran Cards -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($pendaftarans as $pendaftaran)
        @php
            // Ambil jadwal terkait
            $jadwal = $pendaftaran->jadwal;
            
            // Determine effective status
            $effectiveStatus = $pendaftaran->status;
            
            // Hanya override status jika sudah melewati tahap verified_admin
            if ($jadwal && in_array($pendaftaran->status, ['verified_admin', 'scheduled', 'completed'])) {
                // Jika ada jadwal dan pendaftaran sudah verified_admin
                if ($jadwal->status == 'completed') {
                    $effectiveStatus = 'completed';
                } elseif (in_array($jadwal->status, ['scheduled', 'terjadwal', 'ongoing'])) {
                    $effectiveStatus = 'scheduled';
                }
            }
            // Jika belum verified_admin, gunakan status pendaftaran asli
            // (draft, submitted, verified_pembimbing, verified_admin)
            
            $statusColors = [
                'draft' => 'bg-gray-100 text-gray-800',
                'submitted' => 'bg-blue-100 text-blue-800',
                'verified_pembimbing' => 'bg-yellow-100 text-yellow-800',
                'verified_admin' => 'bg-green-100 text-green-800',
                'scheduled' => 'bg-purple-100 text-purple-800',
                'completed' => 'bg-teal-100 text-teal-800',
                'rejected' => 'bg-red-100 text-red-800',
            ];
            
            $statusLabels = [
                'draft' => 'Draft',
                'submitted' => 'Submitted',
                'verified_pembimbing' => 'Verif Pembimbing',
                'verified_admin' => 'Verif Admin',
                'scheduled' => 'Terjadwal',
                'completed' => 'Selesai',
                'rejected' => 'Ditolak',
            ];
        @endphp
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-150">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        
                        <!-- Header Badges -->
                        <div class="flex items-center flex-wrap gap-2 mb-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ $pendaftaran->kategoriSidang->nama ?? 'N/A' }}
                            </span>

                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$effectiveStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$effectiveStatus] ?? ucfirst(str_replace('_', ' ', $effectiveStatus)) }}
                            </span>

                            @if($pendaftaran->nomor_pendaftaran)
                                <span class="text-xs text-gray-500 font-mono">
                                    {{ $pendaftaran->nomor_pendaftaran }}
                                </span>
                            @endif
                            
                            <!-- Badge Jadwal Status jika ada -->
                            @if($jadwal && $jadwal->status != 'completed')
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    {{ $jadwal->status == 'ongoing' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ in_array($jadwal->status, ['scheduled', 'terjadwal']) ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                    {{ $jadwal->status == 'ongoing' ? '🔴 Sedang Berlangsung' : '' }}
                                    {{ in_array($jadwal->status, ['scheduled', 'terjadwal']) ? '📅 Sudah Dijadwalkan' : '' }}
                                </span>
                            @endif
                        </div>

                        <!-- Judul -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $pendaftaran->judul }}
                        </h3>

                        <!-- Abstrak -->
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit($pendaftaran->abstrak, 200) }}
                        </p>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3 text-gray-600 text-sm">
                            <!-- Pembimbing -->
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="truncate">{{ $pendaftaran->pembimbing->count() }} Pembimbing</span>
                            </div>

                            <!-- Dokumen -->
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="truncate">{{ $pendaftaran->dokumen->count() }} Dokumen</span>
                            </div>

                            <!-- Tanggal Dibuat -->
                            <div class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">{{ $pendaftaran->created_at->format('d/m/Y') }}</span>
                            </div>

                            <!-- Jadwal -->
                            @if($jadwal)
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y H:i') }}</span>
                                </div>
                            @else
                                <div class="flex items-center text-gray-400">
                                    <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="truncate">Belum dijadwalkan</span>
                                </div>
                            @endif
                        </div>

                        <!-- Jadwal Details (jika sudah dijadwalkan) -->
                        @if($jadwal)
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-3 mb-3">
                            <div class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex-1 text-xs">
                                    <p class="font-semibold text-purple-900 mb-1">Jadwal Sidang</p>
                                    <div class="space-y-1 text-purple-800">
                                        <p>📅 {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d F Y') }}</p>
                                        <p>🕐 {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('H:i') }} WIB</p>
                                        <p>🏢 {{ $jadwal->ruangSidang->nama ?? 'Ruang belum ditentukan' }}</p>
                                        @if($jadwal->penguji->count() > 0)
                                        <p>👥 {{ $jadwal->penguji->count() }} Penguji</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Pembimbing Details -->
                        @if($pendaftaran->pembimbing->isNotEmpty())
                        <div class="mb-3">
                            <div class="text-xs font-medium text-gray-500 mb-1">Pembimbing:</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($pendaftaran->pembimbing as $pembimbing)
                                    <span class="text-xs px-2 py-1 rounded-md
                                        {{ $pembimbing->jenis == 'utama' ? 'bg-indigo-100 text-indigo-800' : 'bg-cyan-100 text-cyan-800' }}">
                                        {{ $pembimbing->dosen->name ?? 'N/A' }}
                                        <span class="text-[10px]">({{ ucfirst($pembimbing->jenis) }})</span>
                                        @if($pembimbing->status)
                                            - 
                                            <span class="font-semibold
                                                {{ $pembimbing->status == 'pending' ? 'text-yellow-700' : '' }}
                                                {{ $pembimbing->status == 'approved' ? 'text-green-700' : '' }}
                                                {{ $pembimbing->status == 'rejected' ? 'text-red-700' : '' }}">
                                                {{ ucfirst($pembimbing->status) }}
                                            </span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Progress Bar -->
                        @php
                            $progressConfig = [
                                'draft' => ['width' => 'w-[16%]', 'color' => 'bg-gray-500'],
                                'submitted' => ['width' => 'w-[33%]', 'color' => 'bg-blue-500'],
                                'verified_pembimbing' => ['width' => 'w-[50%]', 'color' => 'bg-yellow-500'],
                                'verified_admin' => ['width' => 'w-[66%]', 'color' => 'bg-green-500'],
                                'scheduled' => ['width' => 'w-[83%]', 'color' => 'bg-purple-500'],
                                'completed' => ['width' => 'w-full', 'color' => 'bg-teal-500'],
                                'rejected' => ['width' => 'w-[16%]', 'color' => 'bg-red-500'],
                            ];
                            $currentProgress = $progressConfig[$effectiveStatus] ?? ['width' => 'w-[16%]', 'color' => 'bg-gray-500'];
                        @endphp

                        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $currentProgress['width'] }} {{ $currentProgress['color'] }}"></div>
                        </div>

                        <!-- Action Required Alert -->
                        @if($effectiveStatus == 'draft')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-yellow-800">Aksi Diperlukan</p>
                                    <p class="text-xs text-yellow-700 mt-1">Lengkapi dokumen dan submit untuk memulai proses verifikasi</p>
                                </div>
                            </div>
                        </div>
                        @elseif($effectiveStatus == 'submitted')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-blue-800">Menunggu Verifikasi</p>
                                    <p class="text-xs text-blue-700 mt-1">Pendaftaran sedang menunggu verifikasi dari pembimbing</p>
                                </div>
                            </div>
                        </div>
                        @elseif($effectiveStatus == 'scheduled')
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-purple-800">Sidang Terjadwal</p>
                                    <p class="text-xs text-purple-700 mt-1">Lihat detail jadwal di menu Jadwal Sidang</p>
                                </div>
                            </div>
                        </div>
                        @elseif($effectiveStatus == 'completed')
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-teal-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-teal-800">Sidang Selesai</p>
                                    <p class="text-xs text-teal-700 mt-1">Lihat hasil di menu Hasil Sidang atau Riwayat</p>
                                </div>
                            </div>
                        </div>
                        @elseif($effectiveStatus == 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-red-800">Ditolak</p>
                                    <p class="text-xs text-red-700 mt-1">Pendaftaran ditolak. Silakan periksa catatan penolakan</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="ml-4 flex flex-col gap-2">
                        <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaran) }}" 
                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 text-center whitespace-nowrap transition">
                            {{ $effectiveStatus == 'draft' ? 'Lengkapi' : 'Detail' }}
                        </a>
                        
                        @if($jadwal && in_array($effectiveStatus, ['scheduled', 'completed']))
                        <a href="{{ route('mahasiswa.jadwal.show', $jadwal) }}" 
                           class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 text-center whitespace-nowrap transition">
                            Lihat Jadwal
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Pendaftaran</h3>
            <p class="text-gray-600 mb-4">
                @if(request('status'))
                    Tidak ada pendaftaran dengan status "{{ $statuses[request('status')] ?? ucfirst(str_replace('_', ' ', request('status'))) }}"
                @else
                    Mulai daftar sidang terlebih dahulu untuk memulai proses sidang Anda.
                @endif
            </p>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Daftar Sidang Baru
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($pendaftarans->hasPages())
    <div class="bg-white rounded-xl shadow-md p-4">
        {{ $pendaftarans->links() }}
    </div>
    @endif

</div>
@endsection