<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengirimanEmailController;
use App\Http\Controllers\PenjualanMidtransController; // Import Controller Midtrans

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Proses pengiriman email
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);

// --- Rute Midtrans ---

// 1. Halaman untuk memicu pembayaran (Generate Snap Token)
Route::get('/penjualan/bayar/{id}', [PenjualanMidtransController::class, 'bayar'])
    ->name('penjualan.bayar');

// 2. Webhook: Midtrans akan mengirimkan data ke sini secara otomatis (POST)
// Penting: Daftarkan URL ini di Dashboard Midtrans (Settings > Integration > Notification URL)
Route::post('/midtrans/notification', [PenjualanMidtransController::class, 'notificationHandler'])
    ->name('midtrans.notification');

// 3. Opsi: Cek status manual jika diperlukan
Route::get('/penjualan/cek-status', [PenjualanMidtransController::class, 'cekStatus'])
    ->name('penjualan.cek-status');