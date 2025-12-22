<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Sidang</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-3xl">

       <div class="mx-auto mb-10 flex  justify-center">
    <img src="{{ asset('asset/itb.png') }}" 
         alt="Logo ITB"
         class="h-40 w-40 object-contain drop-shadow-xl">
</div>

        <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
            Sistem Manajemen Sidang
        </h1>

        <p class="text-xl text-gray-600 mb-8">
            Platform untuk mengelola seluruh proses sidang mahasiswa secara digital
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg 
                    shadow-lg font-semibold transform hover:scale-105 transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg 
                    shadow-lg font-semibold transform hover:scale-105 transition">
                    Masuk
                </a>
            @endauth
        </div>

        <div class="grid md:grid-cols-3 gap-6 mt-12">

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="text-3xl mb-4">📄</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pendaftaran Online</h3>
                <p class="text-gray-600 text-sm">Daftar sidang secara online dengan mudah</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="text-3xl mb-4">🗓️</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Penjadwalan Otomatis</h3>
                <p class="text-gray-600 text-sm">Sistem cek bentrok dan atur jadwal otomatis</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="text-3xl mb-4">📊</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Lengkap</h3>
                <p class="text-gray-600 text-sm">Export data ke PDF & Excel dengan cepat</p>
            </div>

        </div>
    </div>
</body>
</html>
