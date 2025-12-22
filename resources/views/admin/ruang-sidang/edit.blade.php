@extends('layouts.app')

@section('title', 'Edit Ruang Sidang')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Ruang Sidang
        </h2>
        <a href="{{ route('admin.ruang-sidang.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white shadow-md rounded-xl p-6">
        <form action="{{ route('admin.ruang-sidang.update', $ruang->id ?? $id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Ruangan -->
                <div>
                    <label class="block text-gray-700 mb-2 font-medium">
                        Nama Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $ruang->nama ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                           placeholder="Contoh: Ruang Sidang 1"
                           required>
                </div>

                <!-- Kode Ruangan -->
                <div>
                    <label class="block text-gray-700 mb-2 font-medium">
                        Kode Ruangan <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <input type="text" name="kode" value="{{ old('kode', $ruang->kode ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                           placeholder="Contoh: RS-01">
                </div>

                <!-- Kapasitas -->
                <div>
                    <label class="block text-gray-700 mb-2 font-medium">
                        Kapasitas <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $ruang->kapasitas ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                           placeholder="Jumlah orang"
                           min="1"
                           required>
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-gray-700 mb-2 font-medium">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $ruang->lokasi ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                           placeholder="Contoh: Gedung A Lantai 2"
                           required>
                </div>

                <!-- Fasilitas -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2 font-medium">
                        Fasilitas <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <textarea name="fasilitas" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                              placeholder="Contoh: Proyektor, AC, Papan Tulis, Wifi">{{ old('fasilitas', $ruang->fasilitas ?? '') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Pisahkan fasilitas dengan koma</p>
                </div>

                <!-- Status Aktif -->
                <div class="md:col-span-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $ruang->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-gray-700 font-medium">Aktif</span>
                    </label>
                    <p class="text-sm text-gray-500 mt-1">Centang jika ruangan ini aktif dan dapat digunakan</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-6 pt-6 border-t">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    Update
                </button>
                <a href="{{ route('admin.ruang-sidang.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection