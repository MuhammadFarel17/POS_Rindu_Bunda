<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengirimanEmailController;



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

// Jalankan ini di browser untuk memulai proses otomatis: localhost:8000/kirim-email-retur
Route::get('/proses_kirim_email_retur', [PengirimanEmailController::class, 'proses_kirim_email_retur']);