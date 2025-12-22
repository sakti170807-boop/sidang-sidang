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
        <a href="{{ route('mahasiswa.pendaftaran.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status Badge -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-lg text-gray-800 mb-2">Status Pendaftaran</h3>
                <span class="px-4 py-2 rounded-full text-sm font-medium inline-flex items-center gap-2
                    @if($pendaftaran->status == 'draft') bg-gray-100 text-gray-700
                    @elseif($pendaftaran->status == 'submitted') bg-blue-100 text-blue-700
                    @elseif($pendaftaran->status == 'approved') bg-green-100 text-green-700
                    @elseif($pendaftaran->status == 'rejected') bg-red-100 text-red-700
                    @endif">
                    @if($pendaftaran->status == 'draft')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    @elseif($pendaftaran->status == 'submitted')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($pendaftaran->status == 'approved')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($pendaftaran->status == 'rejected')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ ucfirst($pendaftaran->status) }}
                </span>
            </div>
            
            @if($pendaftaran->status == 'draft')
                <form action="{{ route('mahasiswa.pendaftaran.submit', $pendaftaran) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin submit pendaftaran ini? Setelah disubmit, Anda tidak dapat mengubah data.')">
                    @csrf
                    @method('POST')
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                        Submit Pendaftaran
                    </button>
                </form>
            @endif
        </div>

        @if($pendaftaran->catatan_admin)
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm font-medium text-yellow-800 mb-1">Catatan Admin:</p>
                <p class="text-sm text-yellow-700">{{ $pendaftaran->catatan_admin }}</p>
            </div>
        @endif
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

    <!-- Pembimbing -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Dosen Pembimbing</h3>
        
        <div class="space-y-3">
            @forelse($pendaftaran->pembimbing as $pembimbing)
                <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $pembimbing->dosen->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($pembimbing->jenis) }} • 
                                <span class="
                                    @if($pembimbing->status == 'pending') text-yellow-600
                                    @elseif($pembimbing->status == 'approved') text-green-600
                                    @elseif($pembimbing->status == 'rejected') text-red-600
                                    @endif
                                ">
                                    {{ ucfirst($pembimbing->status) }}
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

    <!-- Dokumen -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Dokumen Pendukung</h3>
        
        <div class="space-y-2">
            @forelse($pendaftaran->dokumen as $dokumen)
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
                                @if(isset($dokumen->ukuran_file))
                                    • {{ number_format($dokumen->ukuran_file / 1024, 2) }} KB
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
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-600">Belum ada dokumen yang diunggah</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Jadwal Sidang (jika sudah ada) -->
    @if($pendaftaran->jadwal)
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4">Jadwal Sidang</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Tanggal & Waktu</label>
                    <p class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($pendaftaran->jadwal->tanggal)->format('d F Y') }} <br>
                        {{ $pendaftaran->jadwal->waktu_mulai }} - {{ $pendaftaran->jadwal->waktu_selesai }}
                    </p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-500">Ruangan</label>
                    <p class="font-medium text-gray-800">{{ $pendaftaran->jadwal->ruangSidang->nama ?? '-' }}</p>
                </div>
            </div>

            <!-- Daftar Penguji -->
            @if($pendaftaran->jadwal->penguji->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label class="text-sm text-gray-500 mb-2 block">Dosen Penguji</label>
                    <div class="space-y-2">
                        @foreach($pendaftaran->jadwal->penguji as $penguji)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-gray-800">{{ $penguji->dosen->name ?? '-' }} ({{ ucfirst($penguji->peran) }})</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>
@endsection