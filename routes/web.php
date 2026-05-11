<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengirimanEmailController;
use App\Http\Controllers\GajiMidtransController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

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
