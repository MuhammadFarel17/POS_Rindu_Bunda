<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengirimanEmailReservasi; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\ReservasiMail; // <--- INI KUNCI AGAR TIDAK ERROR "NOT FOUND"
use Barryvdh\DomPDF\Facade\Pdf; 

class PengirimanEmailController extends Controller
{
    public static function proses_kirim_email_reservasi() {
        
        // Ambil 1 data reservasi confirmed yang belum ada di log pengiriman
        $data = DB::table('reservasi')
                ->where('status', 'confirmed')
                ->whereNotIn('id', function ($query) {
                    $query->select('reservasi_id') 
                    ->from('pengiriman_email_reservasi'); // <--- PASTIKAN TABEL INI ADA DI DB
                    set_time_limit(0);
                })

                ->select('id', 'kode_reservasi', 'nama_pelanggan')
                ->first();

        if ($data) {
            $id = $data->id;
            $kode_reservasi = $data->kode_reservasi;
            $nama_pelanggan = $data->nama_pelanggan;
            
            // Sementara manual, ganti $data->email jika ada kolom email di tabel reservasi
            $email = 'pelanggan@example.com'; 

            // Ambil data lengkap untuk PDF
            $reservasi = DB::table('reservasi')->where('id', $id)->get();

            // Generate PDF
            $pdf = Pdf::loadView('pdf.reservasi', [
                'kode_reservasi' => $kode_reservasi,
                'nama_pelanggan' => $nama_pelanggan,
                'reservasi' => $reservasi, // Variabel ini yang dipakai di foreach Blade
                'tanggal_cetak'  => now()->format('d-M-Y'),
            ]);
            sleep(60);

            $dataAtribut = [
                'customer_name'  => $nama_pelanggan,
                'invoice_number' => $kode_reservasi
            ];

            // Kirim Email
            Mail::to($email)->send(new ReservasiMail($dataAtribut, $pdf->output()));

            // Simpan Log agar tidak kirim ulang (PENTING!)
            PengirimanEmailReservasi::create([
                'reservasi_id' => $id,
                'status' => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        return view('autorefresh_email');
    }
}