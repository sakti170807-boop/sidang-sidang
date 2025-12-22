@extends('layouts.app')

@section('title', 'Detail Pendaftaran Sidang')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pendaftaran Sidang
            </h2>
            <p class="text-sm text-gray-500 mt-1">{{ $pendaftaran->nomor_pendaftaran }}</p>
        </div>
        <a href="{{ route('dosen.pendaftaran.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status & Actions -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-lg text-gray-800 mb-2">Status Pendaftaran</h3>
                <span class="px-4 py-2 rounded-full text-sm font-medium inline-flex items-center gap-2
                    @if($pendaftaran->status == 'draft') bg-gray-100 text-gray-700
                    @elseif($pendaftaran->status == 'submitted') bg-blue-100 text-blue-700
                    @elseif($pendaftaran->status == 'approved') bg-green-100 text-green-700
                    @elseif($pendaftaran->status == 'rejected') bg-red-100 text-red-700
                    @endif">
                    {{ ucfirst($pendaftaran->status) }}
                </span>
            </div>

            @if($pendaftaran->status == 'submitted' && $pembimbing)
                <div class="flex gap-2">
                    <form action="{{ route('dosen.pendaftaran.approve', $pendaftaran) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                            Setujui
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        Tolak
                    </button>
                </div>
            @endif
        </div>

        @if($pembimbing && $pembimbing->status == 'pending')
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Status Anda:</strong> Menunggu persetujuan Anda sebagai {{ ucfirst($pembimbing->jenis) }}
                </p>
            </div>
        @elseif($pembimbing && $pembimbing->status == 'approved')
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Status Anda:</strong> Sudah menyetujui sebagai {{ ucfirst($pembimbing->jenis) }}
                </p>
            </div>
        @endif
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Informasi Mahasiswa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-500">Nama Mahasiswa</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->mahasiswa->name ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">NIM</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->mahasiswa->nim ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Program Studi</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->programStudi->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Email</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->mahasiswa->email ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Informasi Sidang -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Informasi Sidang</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-500">Kategori Sidang</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->kategoriSidang->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Tanggal Pendaftaran</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Judul</label>
                <p class="font-medium text-gray-800">{{ $pendaftaran->judul }}</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-500">Abstrak</label>
                <p class="text-gray-800 text-justify">{{ $pendaftaran->abstrak }}</p>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Dokumen Pendukung</h3>
        <div class="space-y-2">
            @forelse($pendaftaran->dokumen ?? [] as $dokumen)
                <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $dokumen->jenis_dokumen }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $dokumen->nama_file }}
                                @if(isset($dokumen->ukuran) || isset($dokumen->ukuran_file))
                                    • {{ number_format(($dokumen->ukuran ?? $dokumen->ukuran_file) / 1024, 2) }} KB
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $dokumen->path) }}" 
                       target="_blank"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition">
                        Lihat
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-gray-600">Belum ada dokumen yang diunggah</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Daftar Pembimbing -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Dosen Pembimbing</h3>
        <div class="space-y-3">
            @forelse($pendaftaran->pembimbing ?? [] as $pmb)
                <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $pmb->dosen->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($pmb->jenis) }} • 
                                <span class="
                                    @if($pmb->status == 'pending') text-yellow-600
                                    @elseif($pmb->status == 'approved') text-green-600
                                    @elseif($pmb->status == 'rejected') text-red-600
                                    @endif
                                ">
                                    {{ ucfirst($pmb->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada pembimbing</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pendaftaran</h3>
            <form action="{{ route('dosen.pendaftaran.reject', $pendaftaran) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea name="catatan" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none"
                              placeholder="Masukkan alasan penolakan..."
                              required></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        Tolak
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection