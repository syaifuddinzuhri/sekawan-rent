<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\KendaraanController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\PemesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest.web')->group(function () {
    Route::get('/login', [LoginController::class, 'loginPage'])->name('login.show');
    Route::post('/login', [LoginController::class, 'loginSubmit'])->name('login.submit');
});

Route::middleware('auth.web')->group(function () {
    Route::post('/logout', [LoginController::class, 'logoutSubmit'])->name('logout.submit');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/top-kendaraan', [DashboardController::class, 'getReport'])->name('dashboard.top-kendaraan');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [LaporanController::class, 'getReport'])->name('laporan.report');
    Route::post('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export');
    Route::get('/kendaraan-list', [KendaraanController::class, 'listKendaraan'])->name('kendaraan.list');
    Route::put('/update-approval/{id}', [PemesananController::class, 'updateApproval'])->name('update.approval');
    Route::resource('pemesanan', PemesananController::class);
    Route::resource('user', UserController::class);
    Route::resource('kendaraan', KendaraanController::class);
});
