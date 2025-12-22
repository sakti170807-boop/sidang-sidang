@extends('layouts.app')

@section('content')

   
    <div class="space-y-6">
        <!-- Quick Actions / Shortcuts -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Menu Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Shortcut Pendaftaran -->
                <a href="{{ route('admin.pendaftaran.index') }}" 
                   class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-blue-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Pendaftaran</span>
                </a>

                <!-- Shortcut Jadwal Sidang -->
                <a href="{{ route('admin.jadwal-sidang.index') }}" 
                   class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-green-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Jadwal Sidang</span>
                </a>

                <!-- Shortcut Ruang Sidang -->
                <a href="{{ route('admin.ruang-sidang.index') }}" 
                   class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-purple-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Ruang Sidang</span>
                </a>

                <!-- Shortcut Users -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-yellow-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Kelola User</span>
                </a>

                <!-- Shortcut Kategori Sidang -->
                <a href="{{ route('admin.kategori-sidang.index') }}" 
                   class="flex flex-col items-center p-4 bg-pink-50 hover:bg-pink-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-pink-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Kategori</span>
                </a>

                <!-- Shortcut Laporan -->
                <a href="{{ route('admin.laporan.index') }}" 
                   class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition group">
                    <svg class="w-10 h-10 text-red-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Laporan</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Mahasiswa</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_mahasiswa'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Dosen</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_dosen'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Pendaftaran Baru</div>
                <div class="text-3xl font-bold text-blue-600">{{ $stats['pendaftaran_baru'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Menunggu Verifikasi</div>
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['menunggu_verifikasi'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Sidang Terjadwal</div>
                <div class="text-3xl font-bold text-green-600">{{ $stats['sidang_terjadwal'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Sidang Selesai</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['sidang_selesai'] }}</div>
            </div>
        </div>

        <!-- Pendaftaran Terbaru -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Pendaftaran Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendaftaranTerbaru as $pendaftaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendaftaran->nomor_pendaftaran }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendaftaran->mahasiswa->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendaftaran->kategoriSidang->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($pendaftaran->status == 'submitted') bg-blue-100 text-blue-800
                                        @elseif($pendaftaran->status == 'verified_pembimbing') bg-yellow-100 text-yellow-800
                                        @elseif($pendaftaran->status == 'verified_admin') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($pendaftaran->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendaftaran->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.pendaftaran.show', $pendaftaran) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pendaftaran</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jadwal Mendatang -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Jadwal Sidang Mendatang</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jadwalMendatang as $jadwal)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $jadwal->pendaftaran->mahasiswa->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $jadwal->tanggal_waktu->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $jadwal->ruangSidang->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($jadwal->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.jadwal-sidang.show', $jadwal) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal mendatang</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection