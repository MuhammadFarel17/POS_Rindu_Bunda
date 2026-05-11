<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengirimanEmail; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\InvoiceMail; 
use Barryvdh\DomPDF\Facade\Pdf; 

class PengirimanEmailController extends Controller
{
    public static function proses_kirim_email_retur()
    {
        // 1. Query data retur_penjualan yang belum pernah dikirim emailnya
        // Kita join ke tabel penjualan untuk dapat id_user, lalu ke users untuk dapat email
        $data = DB::table('retur_penjualan')
                ->join('penjualan', 'retur_penjualan.id_penjualan', '=', 'penjualan.id')
                ->join('users', 'retur_penjualan.id_user', '=', 'users.id') // User yang melakukan retur (atau sesuaikan ke email pembeli)
                ->whereNotIn('retur_penjualan.id_retur_penjualan', function ($query) {
                    $query->select('retur_penjualan_id') // Kolom id retur di tabel pengiriman email kamu
                        ->from('pengiriman_emails');
                })
                ->select(
                    'retur_penjualan.id_retur_penjualan',
                    'retur_penjualan.tanggal_retur',
                    'retur_penjualan.alasan_retur',
                    'retur_penjualan.total_retur',
                    'users.email',
                    'users.name as nama_petugas',
                    'penjualan.no_faktur'
                )
                ->first();

        if ($data) {
            $id_retur = $data->id_retur_penjualan;
            $email_tujuan = $data->email;

            // 2. Generate PDF Bukti Retur
            // Menggunakan view pdf.retur (Pastikan kamu buat filenya nanti)
            $pdf = Pdf::loadView('pdf.retur', [
                'id_retur' => $id_retur,
                'no_faktur' => $data->no_faktur,
                'tanggal' => $data->tanggal_retur,
                'alasan' => $data->alasan_retur,
                'total' => $data->total_retur,
                'petugas' => $data->nama_petugas,
            ]);

            // Data untuk body email
            $dataAtributPelanggan = [
                'customer_name' => $data->nama_petugas, // Atau sesuaikan dengan nama pembeli
                'invoice_number' => $data->no_faktur
            ];

            // 3. Kirim email menggunakan Mailable InvoiceMail
            Mail::to($email_tujuan)->send(new InvoiceMail($dataAtributPelanggan, $pdf->output()));

            //delay 5 detik agar tidak terlalu cepat kirim email berikutnya
            sleep(5);

            // 4. Catat pengiriman email di tabel pengiriman_emails
            PengirimanEmail::create([
                'retur_penjualan_id' => $id_retur, // Pastikan kolom ini sudah ada di tabel pengiriman_emails
                'status' => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        // Return ke view autorefresh agar proses berjalan terus secara otomatis
        return view('autorefresh_email');
    }
}