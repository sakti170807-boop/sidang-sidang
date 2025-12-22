<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Sidang</title>

    <!-- Load Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">

            <!-- Logo -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-600 to-purple-600 
                            rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 
                        3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                        C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 
                        4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Sistem Manajemen Sidang
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Silakan masuk dengan akun Anda
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white shadow-xl p-8 rounded-2xl">

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 px-4 py-3 rounded-lg text-green-700 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 px-4 py-3 rounded-lg text-red-700 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full py-3 px-4 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full py-3 px-4 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="••••••••">
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-700 gap-2">
                            <input type="checkbox" name="remember" class="h-4 w-4">
                            Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold shadow-lg hover:opacity-90 transition">
                        Masuk
                    </button>

                    <!-- Tombol/Link pindah ke Register -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" 
                               class="font-medium text-blue-600 hover:text-blue-500">
                                Daftar di sini
                            </a>
                        </p>
                    </div>

                </form>

                <!-- Demo Account -->
                <div class="mt-6 pt-6 border-t text-xs text-gray-500 text-center">
                    <p class="mb-2">Akun Demo</p>
                    <p><b>Admin:</b> admin@sidang.test</p>
                    <p><b>Password:</b> password123</p>
                </div>

            </div>

            <p class="text-center text-sm text-gray-500 mt-4">
                © {{ date('Y') }} Sistem Manajemen Sidang
            </p>
        </div>
    </div>
</body>
</html>
