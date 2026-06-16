<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengirimanEmailController;
use App\Http\Controllers\PenjualanMidtransController; // Import Controller Midtrans


use App\Http\Controllers\GajiMidtransController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


// untuk contoh pdf
use App\Http\Controllers\PDFController;
Route::get('/contohpdf', [PDFController::class, 'contohpdf']);
// proses pengiriman email
use App\Http\Controllers\PengirimanEmailController;

Route::get('/proses_kirim_email_reservasi', [PengirimanEmailController::class, 'proses_kirim_email_reservasi'])
    ->name('proses.email.reservasi');
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
// Jalankan ini di browser untuk memulai proses otomatis: localhost:8000/kirim-email-retur
Route::get('/proses_kirim_email_retur', [PengirimanEmailController::class, 'proses_kirim_email_retur']);
// proses pengiriman email
use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembelian', [PengirimanEmailController::class, 'proses_kirim_email_pembelian']);
Route::get('/bayar-gaji/{id}', [GajiMidtransController::class, 'bayar'])
    ->name('bayar.gaji');

Route::get('/cek-status-gaji', [GajiMidtransController::class, 'cekStatus'])
    ->name('cek.status.gaji');

Route::get('/proses_kirim_email_gaji', [PengirimanEmailController::class, 'proses_kirim_email_gaji']);

Route::get('/update-status-gaji/{id}', function ($id) {

    $gaji = \App\Models\GajiPegawai::find($id);

    if ($gaji) {

        $gaji->update([
            'status' => 'lunas'
        ]);
    }

    return redirect('/admin/gaji-pegawais')
        ->with('success', 'Pembayaran berhasil');
});
