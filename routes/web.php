<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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