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
    public static function proses_kirim_email_pembelian()
    {
        // 1. Query data pembelian join ke suplayer
        // Pastikan kolom 'kode_suplayer' dan 'nama_suplayer' ditarik dari database
        $data = DB::table('pembelian')
                ->join('suplayer', 'pembelian.kode_suplayer', '=', 'suplayer.id')
                ->whereNotIn('pembelian.id', function ($query) {
                    $query->select('pembelian_id') 
                        ->from('pengiriman_email');
                })
                ->select(
                    'pembelian.id',
                    'pembelian.no_faktur',
                    'pembelian.tgl',
                    'pembelian.tagihan',
                    'pembelian.status',
                    'pembelian.kode_suplayer', // Diperlukan untuk PDF
                    'pembelian.updated_at',    // Diperlukan untuk PDF
                    'suplayer.name',  // Sesuai kolom tabel suplayer
                    'suplayer.email'           
                )
                ->first();

        if ($data) {
            $id_pembelian = $data->id;
            $email_tujuan = $data->email;

            // 2. Generate PDF Nota Pembelian
            // Semua key di bawah ini harus sama dengan variabel {{ $... }} di file blade
            $pdf = Pdf::loadView('pdf.pembelian', [
                'id'            => $data->id,
                'no_faktur'     => $data->no_faktur,
                'tgl'           => $data->tgl,           // Mengatasi error $tgl
                'tagihan'       => $data->tagihan,
                'status'        => $data->status,
                'kode_suplayer' => $data->kode_suplayer, // ✅ Mengatasi error $kode_suplayer
                'updated_at'    => $data->updated_at,    // Mengatasi error $updated_at
                'suplayer'      => $data->name,
            ]);

            // Data untuk isi/body email
            $dataAtribut = [
                'customer_name'  => $data->name, 
                'invoice_number' => $data->no_faktur
            ];
            // Di dalam PengirimanEmailController.phpS

            // 3. Kirim email
            Mail::to($email_tujuan)->send(new InvoiceMail($dataAtribut, $pdf->output()));

            // Jeda 5 detik
            sleep(5);

            // 4. Catat pengiriman di tabel log
            PengirimanEmail::create([
                'pembelian_id'         => $id_pembelian, 
                'status'               => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        // Kembali ke view autorefresh untuk looping pengiriman selanjutnya
        return view('pdf.autorefresh_email');
    }
    
}