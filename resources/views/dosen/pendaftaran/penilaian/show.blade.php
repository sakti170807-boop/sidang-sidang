<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Input Penilaian Sidang
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Info Mahasiswa -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-start space-x-4 mb-4">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">{{ $jadwal->pendaftaran->mahasiswa->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $jadwal->pendaftaran->mahasiswa->nim }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $jadwal->pendaftaran->kategoriSidang->nama }}</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    {{ ucfirst(str_replace('_', ' ', $penguji->posisi)) }}
                </span>
            </div>
            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-900 mb-2">{{ $jadwal->pendaftaran->judul }}</h4>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $jadwal->tanggal_waktu->format('d M Y, H:i') }}
                    </span>
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        {{ $jadwal->ruangSidang->nama }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Penilaian -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Form Penilaian</h3>
            
            <form action="{{ route('dosen.penilaian.store', $jadwal) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nilai Presentasi -->
                <div>
                    <label for="nilai_presentasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Presentasi (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="nilai_presentasi" name="nilai_presentasi" 
                        min="0" max="100" step="0.01" required
                        value="{{ old('nilai_presentasi', $penilaian->nilai_presentasi ?? '') }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Penilaian kemampuan presentasi dan komunikasi</p>
                </div>

                <!-- Nilai Materi -->
                <div>
                    <label for="nilai_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Materi (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="nilai_materi" name="nilai_materi" 
                        min="0" max="100" step="0.01" required
                        value="{{ old('nilai_materi', $penilaian->nilai_materi ?? '') }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Penilaian kualitas konten dan substansi materi</p>
                </div>

                <!-- Nilai Diskusi -->
                <div>
                    <label for="nilai_diskusi" class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Diskusi (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="nilai_diskusi" name="nilai_diskusi" 
                        min="0" max="100" step="0.01" required
                        value="{{ old('nilai_diskusi', $penilaian->nilai_diskusi ?? '') }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Penilaian kemampuan menjawab pertanyaan dan berdiskusi</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan dan Saran
                    </label>
                    <textarea id="catatan" name="catatan" rows="5"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tambahkan catatan atau saran untuk mahasiswa...">{{ old('catatan', $penilaian->catatan ?? '') }}</textarea>
                </div>

                <!-- Preview Nilai Akhir -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Info:</strong> Nilai akhir akan dihitung secara otomatis sebagai rata-rata dari ketiga aspek penilaian.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t">
                    <a href="{{ route('dosen.penilaian.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-150">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150 flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>

        @if($penilaian)
        <!-- Penilaian Tersimpan -->
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-lg">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-green-900 mb-2">Penilaian Telah Tersimpan</h4>
                    <div class="grid grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-green-700">Presentasi</p>
                            <p class="font-semibold text-green-900">{{ number_format($penilaian->nilai_presentasi, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-green-700">Materi</p>
                            <p class="font-semibold text-green-900">{{ number_format($penilaian->nilai_materi, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-green-700">Diskusi</p>
                            <p class="font-semibold text-green-900">{{ number_format($penilaian->nilai_diskusi, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-green-700">Nilai Akhir</p>
                            <p class="font-semibold text-green-900 text-lg">{{ number_format($penilaian->nilai_akhir, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>