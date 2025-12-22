@if($jadwalMendatang)
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Jadwal Sidang Mendatang
        </h3>
    </div>
    <div class="p-6">
        <!-- Main Info -->
        <div class="flex items-center space-x-4 mb-6">
            <div class="bg-green-100 rounded-lg p-4">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($jadwalMendatang->tanggal_mulai)->format('d F Y') }}</h4>
                <p class="text-lg text-gray-600">Pukul {{ \Carbon\Carbon::parse($jadwalMendatang->tanggal_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwalMendatang->tanggal_selesai)->format('H:i') }} WIB</p>
                <div class="flex items-center mt-2 text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    <span class="font-medium">{{ $jadwalMendatang->ruangSidang->nama }}</span>
                    @if($jadwalMendatang->ruangSidang->gedung)
                        <span class="mx-2">•</span>
                        <span>{{ $jadwalMendatang->ruangSidang->gedung }}</span>
                    @endif
                </div>
            </div>
            <!-- Countdown -->
            <div class="text-center bg-green-50 rounded-lg px-4 py-2">
                <p class="text-xs text-green-600 font-medium">Tersisa</p>
                <p class="text-lg font-bold text-green-700">{{ \Carbon\Carbon::parse($jadwalMendatang->tanggal_mulai)->diffForHumans(null, true) }}</p>
                <p class="text-xs text-green-600">lagi</p>
            </div>
        </div>
        
        {{-- COMMENT DULU: Bagian Dosen Penguji (tabel penguji_sidang belum ada)
        @if(isset($jadwalMendatang->penguji) && $jadwalMendatang->penguji->count() > 0)
        <div class="border-t pt-4 mb-4">
            <p class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <svg class="h-4 w-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Dosen Penguji:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($jadwalMendatang->penguji as $penguji)
                    <div class="flex items-center space-x-2 bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition">
                        <div class="bg-blue-100 rounded-full p-2 flex-shrink-0">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $penguji->peran)) }}</p>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $penguji->dosen->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        --}}

        <!-- Link Virtual -->
        @if(isset($jadwalMendatang->ruangSidang->link_virtual) && $jadwalMendatang->ruangSidang->link_virtual)
        <div class="border-t pt-4 mb-4">
            <p class="text-sm font-medium text-gray-700 mb-2">Link Meeting Virtual:</p>
            <a href="{{ $jadwalMendatang->ruangSidang->link_virtual }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Join Meeting
                <svg class="h-3 w-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>
        </div>
        @endif

        <!-- Info Tambahan -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Persiapan Sidang:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Siapkan presentasi dan materi sidang</li>
                        <li>Datang 15 menit sebelum jadwal</li>
                        <li>Bawa dokumen pendukung yang diperlukan</li>
                    </ul>
                </div>
            </div>
        </div> 

        <!-- Button Detail -->
        <a href="{{ route('mahasiswa.jadwal.show', $jadwalMendatang->id) }}" 
           class="block w-full text-center px-4 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-150">
            Lihat Detail Lengkap
        </a>
    </div>
</div>
@else
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-gray-400 to-gray-500 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Jadwal Sidang Mendatang
        </h3>
    </div>
    <div class="p-6 text-center">
        <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <p class="text-gray-500 text-sm">Belum ada jadwal sidang yang terjadwal</p>
    </div>
</div>
@endif