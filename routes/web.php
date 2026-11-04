<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DokumenAkademikController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaPengajuanController;
use App\Http\Controllers\SiswaDokumenController;
use App\Http\Controllers\AdminPengajuanController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');
Route::view('/informasi', 'public.informasi')->name('informasi');
Route::view('/layanan', 'public.layanan')->name('layanan');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');
    
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/siswa', [SiswaController::class, 'index'])
        ->name('admin.siswa.index');

    Route::get('/admin/siswa/tambah', [SiswaController::class, 'create'])
        ->name('admin.siswa.create');

    Route::post('/admin/siswa', [SiswaController::class, 'store'])
        ->name('admin.siswa.store');

    Route::get('/admin/siswa/{siswa}/edit', [SiswaController::class, 'edit'])
    ->name('admin.siswa.edit');

    Route::put('/admin/siswa/{siswa}', [SiswaController::class, 'update'])
    ->name('admin.siswa.update');

    Route::delete('/admin/siswa/{siswa}', [SiswaController::class, 'destroy'])
    ->name('admin.siswa.destroy');

    Route::get('/admin/dokumen', [DokumenAkademikController::class, 'index'])
    ->name('admin.dokumen.index');

    Route::get('/admin/dokumen/tambah', [DokumenAkademikController::class, 'create'])
    ->name('admin.dokumen.create');

    Route::post('/admin/dokumen', [DokumenAkademikController::class, 'store'])
    ->name('admin.dokumen.store');

    Route::get('/admin/dokumen/{dokumen}/edit', [DokumenAkademikController::class, 'edit'])
    ->name('admin.dokumen.edit');

    Route::put('/admin/dokumen/{dokumen}', [DokumenAkademikController::class, 'update'])
    ->name('admin.dokumen.update');

    Route::delete('/admin/dokumen/{dokumen}', [DokumenAkademikController::class, 'destroy'])
    ->name('admin.dokumen.destroy');

    Route::get('/admin/pengajuan', [PengajuanController::class, 'index'])
    ->name('admin.pengajuan.index');

    Route::get('/admin/pengajuan/tambah', [PengajuanController::class, 'create'])
    ->name('admin.pengajuan.create');

    Route::post('/admin/pengajuan', [PengajuanController::class, 'store'])
    ->name('admin.pengajuan.store');

    Route::get('/admin/pengajuan/{pengajuan}/edit', [PengajuanController::class, 'edit'])
    ->name('admin.pengajuan.edit');

    Route::put('/admin/pengajuan/{pengajuan}', [PengajuanController::class, 'update'])
    ->name('admin.pengajuan.update');

    Route::delete('/admin/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])
    ->name('admin.pengajuan.destroy');

    Route::get('/admin/laporan', [PengajuanController::class, 'laporan'])
    ->name('admin.laporan');

    Route::get('/admin/profil', [ProfileController::class, 'index'])
    ->name('admin.profil');
    
    Route::get('/admin/konfirmasi-pengajuan', [
    AdminPengajuanController::class, 'index'
    ])->name('admin.pengajuan.konfirmasi');

    Route::put('/admin/konfirmasi-pengajuan/{pengajuan}', [
    AdminPengajuanController::class, 'update'
    ])->name('admin.pengajuan.konfirmasi.update');

    
    Route::get('/admin/notifikasi-pengajuan', function () {
    return response()->json([
        'jumlah' => \App\Models\Pengajuan::where('status', 'Pending')->count(),
    ]);
    })->name('admin.notifikasi.pengajuan');

});

/*
|--------------------------------------------------------------------------
| Dashboard Siswa
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', function () {
        $pengajuan = \App\Models\Pengajuan::with('siswa')
            ->whereHas('siswa', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('siswa.dashboard', compact('pengajuan'));
    })->name('siswa.dashboard');

    Route::get('/siswa/pengajuan', function () {
    return redirect()->route('siswa.dashboard', [
        'halaman' => 'riwayat'
    ]);
    })->name('siswa.pengajuan.index');

    Route::get('/siswa/pengajuan/tambah', [
    SiswaPengajuanController::class, 'create'
    ])->name('siswa.pengajuan.create');

    Route::get('/siswa/dokumen', [SiswaDokumenController::class, 'index'])
        ->name('siswa.dokumen');

    Route::post('/siswa/pengajuan', [
    SiswaPengajuanController::class, 'store'
    ])->name('siswa.pengajuan.store');
});
