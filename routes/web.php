<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal', [HomeController::class, 'jadwalLengkap'])->name('jadwal');
Route::get('/laporan', [LaporanController::class, 'harian'])->name('laporan.harian');
Route::get('/laporan-keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');

// AJAX API for public jimpitan status update
Route::post('/api/update-status', [HomeController::class, 'updateStatus'])->name('api.update_status');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Automated background cron reminder (does not require login)
Route::get('/cron/send-reminders', [NotificationController::class, 'cronJimpitan'])->name('cron.reminders');

// Admin routes (requires superuser access)
Route::prefix('admin')->middleware('superuser')->group(function () {
    // Citizen Management (Warga)
    Route::get('/warga', [WargaController::class, 'index'])->name('admin.warga');
    Route::post('/warga', [WargaController::class, 'store'])->name('admin.warga.store');
    Route::post('/warga/update/{id}', [WargaController::class, 'update'])->name('admin.warga.update');
    Route::get('/warga/delete/{id}', [WargaController::class, 'destroy'])->name('admin.warga.delete');

    // Schedule Master Management (Jadwal)
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::post('/jadwal/update/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::get('/jadwal/delete/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.delete');

    // Treasury / Expenses Management (Pengeluaran)
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('admin.pengeluaran');
    Route::post('/pengeluaran/saldo-awal', [PengeluaranController::class, 'updateSaldoAwal'])->name('admin.pengeluaran.saldo_awal');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('admin.pengeluaran.store');
    Route::get('/pengeluaran/delete/{id}', [PengeluaranController::class, 'destroy'])->name('admin.pengeluaran.delete');

    // WhatsApp Reminders (Pengingat)
    Route::get('/pengingat', [NotificationController::class, 'index'])->name('admin.pengingat');
    Route::get('/pengingat/manual', [NotificationController::class, 'kirimManual'])->name('admin.pengingat.manual');
    Route::get('/pengingat/gateway', [NotificationController::class, 'kirimGateway'])->name('admin.pengingat.gateway');
});
