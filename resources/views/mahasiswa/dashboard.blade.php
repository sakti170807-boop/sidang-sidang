@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="container py-6 space-y-6">

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
        <p class="text-purple-100">
            {{ Auth::user()->nim }} - {{ Auth::user()->programStudi->nama ?? 'Mahasiswa' }}
        </p>
    </div>

    <!-- Statistics Cards -->
    @php $s = $stats; @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach([
            ['Total Pendaftaran', 'total_pendaftaran', 'blue'],
            ['Menunggu Verifikasi', 'menunggu_verifikasi', 'yellow'],
            ['Sidang Terjadwal', 'sidang_terjadwal', 'green'],
            ['Sidang Selesai', 'sidang_selesai', 'purple'],
        ] as [$label, $key, $color])
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-{{ $color }}-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">{{ $label }}</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $s[$key] }}</p>
                </div>
                <div class="bg-{{ $color }}-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-{{ $color }}-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M16.5 3.75H12l-2.25 2.25H7.5A2.25 2.25 0 005.25 8.25v9A2.25 2.25 0 007.5 19.5h9A2.25 2.25 0 0018.75 17.25v-9A2.25 2.25 0 0016.5 3.75z" />
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @include('mahasiswa.partials._pendaftaran_aktif')
    @include('mahasiswa.partials._jadwal_mendatang')
    @include('mahasiswa.partials._pengumuman')

    <!-- Quick Actions --> 
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

        <!-- Daftar Sidang → index dulu -->
        <a class="dashboard-action" href="{{ route('mahasiswa.pendaftaran.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <h4>Daftar Sidang</h4>
            <p>Buat pendaftaran baru</p>
        </a>

        <!-- Jadwal Sidang -->
        <a class="dashboard-action" href="{{ route('mahasiswa.jadwal.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M6.75 3v2.25m10.5-2.25V5.25M3.75 8.25h16.5" />
            </svg>
            <h4>Jadwal Sidang</h4>
            <p>Lihat jadwal</p>
        </a>

        <!-- TAMBAHAN: Hasil Penilaian -->
        <a class="dashboard-action" href="{{ route('mahasiswa.penilaian.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
            </svg>
            <h4>Hasil Penilaian</h4>
            <p>Lihat nilai sidang</p>
        </a>

        <!-- Hasil Sidang (Berita Acara) -->
        <a class="dashboard-action" href="{{ route('mahasiswa.hasil.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <h4>Berita Acara</h4>
            <p>Dokumen & BA</p>
        </a>

        <!-- Riwayat Sidang -->
        <a class="dashboard-action" href="{{ route('mahasiswa.riwayat.index') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4>Riwayat</h4>
            <p>History sidang</p>
        </a>

    </div>

</div>

<style>
.dashboard-action {
    @apply bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-150 group cursor-pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.dashboard-action svg {
    @apply text-blue-600 mb-3 group-hover:scale-110 transition-transform;
}

.dashboard-action h4 {
    @apply font-semibold text-gray-900 text-base mb-1;
}

.dashboard-action p {
    @apply text-sm text-gray-500;
}

.dashboard-action:hover {
    @apply bg-blue-50;
}
</style>
@endsection