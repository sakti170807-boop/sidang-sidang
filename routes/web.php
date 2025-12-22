<?php

use Illuminate\Support\Facades\Route;

// =====================
// Auth Controllers
// =====================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// =====================
// Profile Controller
// =====================
use App\Http\Controllers\ProfileController;

// =====================
// Admin Controllers
// =====================
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\KategoriSidangController;
use App\Http\Controllers\Admin\RuangSidangController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaran;
use App\Http\Controllers\Admin\JadwalSidangController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\LaporanController;

// =====================
// Dosen Controllers
// =====================
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\PendaftaranController as DosenPendaftaran;
use App\Http\Controllers\Dosen\JadwalController as DosenJadwal;
use App\Http\Controllers\Dosen\PenilaianController;
use App\Http\Controllers\Dosen\RevisiController as DosenRevisi;
use App\Http\Controllers\Dosen\RiwayatController as DosenRiwayat;

// =====================
// Mahasiswa Controllers
// =====================
use App\Http\Controllers\Mahasiswa\DashboardController as MhsDashboard;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MhsPendaftaran;
use App\Http\Controllers\Mahasiswa\DokumenController;
use App\Http\Controllers\Mahasiswa\JadwalController as MhsJadwal;
use App\Http\Controllers\Mahasiswa\HasilController;
use App\Http\Controllers\Mahasiswa\RevisiController as MhsRevisi;
use App\Http\Controllers\Mahasiswa\RiwayatController as MhsRiwayat;
use App\Http\Controllers\Mahasiswa\PenilaianController as MhsPenilaian;

// =====================
// Public / Root Route
// =====================
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if (in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
            return redirect()->route($role . '.dashboard');
        }
        abort(403, 'Role tidak valid');
    }
    return view('welcome');
});

// =====================
// Guest Routes (Login, Register, Forgot Password)
// =====================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Register
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// =====================
// Auth Routes (Logout, Profile)
// =====================
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// =====================
// Admin Routes
// =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('fakultas', FakultasController::class);
    Route::resource('program-studi', ProgramStudiController::class);
    Route::resource('kategori-sidang', KategoriSidangController::class);
    Route::resource('ruang-sidang', RuangSidangController::class);
    Route::resource('pendaftaran', AdminPendaftaran::class);
    Route::post('pendaftaran/{pendaftaran}/verify', [AdminPendaftaran::class, 'verify'])->name('pendaftaran.verify');
    Route::post('pendaftaran/{pendaftaran}/reject', [AdminPendaftaran::class, 'reject'])->name('pendaftaran.reject');

    Route::resource('jadwal-sidang', JadwalSidangController::class);
    Route::post('jadwal-sidang/{jadwal}/assign-penguji', [JadwalSidangController::class, 'assignPenguji'])->name('jadwal.assign-penguji');
    Route::post('jadwal-sidang/check-conflict', [JadwalSidangController::class, 'checkConflict'])->name('jadwal-sidang.check-conflict');

    Route::resource('pengumuman', PengumumanController::class);

    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
});

// =====================
// Dosen Routes
// =====================
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('dashboard', [DosenDashboard::class, 'index'])->name('dashboard');

    Route::resource('pendaftaran', DosenPendaftaran::class)->only(['index', 'show']);
    Route::post('pendaftaran/{pendaftaran}/approve', [DosenPendaftaran::class, 'approve'])->name('pendaftaran.approve');
    Route::post('pendaftaran/{pendaftaran}/reject', [DosenPendaftaran::class, 'reject'])->name('pendaftaran.reject');

    Route::get('jadwal', [DosenJadwal::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/{jadwal}', [DosenJadwal::class, 'show'])->name('jadwal.show');
    Route::post('jadwal/{jadwal}/confirm', [DosenJadwal::class, 'confirm'])->name('jadwal.confirm');

    // Fixed penilaian routes with proper parameter names
    Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/{jadwal}', [PenilaianController::class, 'show'])->name('penilaian.show');
    Route::post('penilaian/{jadwal}', [PenilaianController::class, 'store'])->name('penilaian.store');

    Route::get('revisi', [DosenRevisi::class, 'index'])->name('revisi.index');
    Route::post('revisi/{revisi}', [DosenRevisi::class, 'store'])->name('revisi.store');
    Route::post('revisi/{revisi}/approve', [DosenRevisi::class, 'approve'])->name('revisi.approve');

    Route::get('riwayat', [DosenRiwayat::class, 'index'])->name('riwayat.index');
    Route::get('riwayat/export-pdf', [DosenRiwayat::class, 'exportPdf'])->name('riwayat.export-pdf');
    Route::get('riwayat/export-excel', [DosenRiwayat::class, 'exportExcel'])->name('riwayat.export-excel');
});

/// =====================
// Mahasiswa Routes (Final & Aman)
// =====================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

    // Dashboard
    Route::get('dashboard', [MhsDashboard::class, 'index'])->name('dashboard');

    // Pendaftaran (hanya index, create, store, show)
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [MhsPendaftaran::class, 'index'])->name('index');
        Route::get('create', [MhsPendaftaran::class, 'create'])->name('create');
        Route::post('/', [MhsPendaftaran::class, 'store'])->name('store');
        Route::get('{pendaftaran}', [MhsPendaftaran::class, 'show'])->name('show');
        Route::post('{pendaftaran}/submit', [MhsPendaftaran::class, 'submit'])->name('submit');
    });

    // Dokumen Mahasiswa
    Route::post('dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('dokumen/{dokumen}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::delete('dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    // Jadwal Sidang Mahasiswa
    Route::get('jadwal', [MhsJadwal::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/{jadwal}', [MhsJadwal::class, 'show'])->name('jadwal.show');

    // PENILAIAN MAHASISWA (BARU DITAMBAHKAN)
    Route::get('penilaian', [MhsPenilaian::class, 'index'])->name('penilaian.index');
    Route::get('penilaian/{jadwal}', [MhsPenilaian::class, 'show'])->name('penilaian.show');

    // Hasil Sidang Mahasiswa
    Route::get('hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('hasil/{jadwal}', [HasilController::class, 'show'])->name('hasil.show');
    Route::get('hasil/{jadwal}/download-ba', [HasilController::class, 'downloadBeritaAcara'])->name('hasil.download-ba');

    // Revisi Mahasiswa
    Route::get('revisi', [MhsRevisi::class, 'index'])->name('revisi.index');
    Route::post('revisi/{revisi}/submit', [MhsRevisi::class, 'submit'])->name('revisi.submit');

   // Riwayat Mahasiswa
    Route::get('riwayat', [MhsRiwayat::class, 'index'])->name('riwayat.index');
    Route::get('riwayat/{jadwal}', [MhsRiwayat::class, 'show'])->name('riwayat.show');
    Route::get('riwayat/export-pdf', [MhsRiwayat::class, 'exportPdf'])->name('riwayat.export-pdf');
    Route::get('riwayat/export-excel', [MhsRiwayat::class, 'exportExcel'])->name('riwayat.export-excel');
});