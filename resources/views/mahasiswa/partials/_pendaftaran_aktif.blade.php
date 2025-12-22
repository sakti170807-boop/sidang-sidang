@if($pendaftaranAktif)
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Pendaftaran Aktif
        </h3>
    </div>
    <div class="p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <!-- Status Badges -->
                <div class="flex items-center space-x-2 mb-3">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        {{ $pendaftaranAktif->kategoriSidang->nama }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($pendaftaranAktif->status == 'draft') bg-gray-100 text-gray-800
                        @elseif($pendaftaranAktif->status == 'submitted') bg-blue-100 text-blue-800
                        @elseif($pendaftaranAktif->status == 'verified_pembimbing') bg-yellow-100 text-yellow-800
                        @elseif($pendaftaranAktif->status == 'verified_admin') bg-green-100 text-green-800
                        @elseif($pendaftaranAktif->status == 'scheduled') bg-purple-100 text-purple-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $pendaftaranAktif->status)) }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $pendaftaranAktif->nomor_pendaftaran }}
                    </span>
                </div>

                <!-- Judul & Abstrak -->
                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $pendaftaranAktif->judul }}</h4>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($pendaftaranAktif->abstrak, 200) }}</p>
                
                <!-- Pembimbing -->
                @if($pendaftaranAktif->pembimbing->count() > 0)
                <div class="border-t pt-3 mt-3">
                    <p class="text-sm font-medium text-gray-700 mb-2">Pembimbing:</p>
                    <div class="space-y-2">
                        @foreach($pendaftaranAktif->pembimbing as $pembimbing)
                            <div class="flex items-center space-x-2 text-sm">
                                <div class="bg-blue-100 rounded-full p-1">
                                    <svg class="h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                    {{ ucfirst($pembimbing->jenis) }}
                                </span>
                                <span class="text-gray-900 font-medium">{{ $pembimbing->dosen->full_name }}</span>
                                <span class="px-2 py-1 text-xs rounded font-semibold
                                    @if($pembimbing->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($pembimbing->status == 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($pembimbing->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Warning untuk Draft -->
                @if($pendaftaranAktif->status == 'draft')
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm text-yellow-800">
                            <strong>Perhatian:</strong> Upload semua dokumen persyaratan kemudian submit pendaftaran untuk verifikasi dosen.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Info Progress -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-700 font-medium">Progress:</span>
                        <div class="flex items-center space-x-2">
                            @if($pendaftaranAktif->dokumen->count() > 0)
                                <span class="text-green-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $pendaftaranAktif->dokumen->count() }} Dokumen
                                </span>
                            @else
                                <span class="text-yellow-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Belum ada dokumen
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Detail -->
            <a href="{{ route('mahasiswa.pendaftaran.show', $pendaftaranAktif) }}" 
               class="ml-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-150 flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Detail
            </a>
        </div>
    </div>
</div>
@else
<!-- Empty State -->
<div class="bg-white rounded-xl shadow-md p-8 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Pendaftaran Aktif</h3>
    <p class="text-gray-600 mb-4">Mulai daftar sidang sekarang untuk melanjutkan proses akademik Anda</p>
    <a href="{{ route('mahasiswa.pendaftaran.create') }}" 
       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Daftar Sidang Baru
    </a>
</div>
@endif