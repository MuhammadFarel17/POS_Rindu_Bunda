<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PengirimanEmailPenjualan;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanEmailController extends Controller
{
    public static function proses_kirim_email_pembayaran()
    {
        // 1. Ambil data penjualan 'Selesai' yang BELUM dikirim email
        $penjualan = Penjualan::with(['user', 'detailPenjualan'])
            ->where('status_penjualan', 'Selesai')
            ->whereDoesntHave('pengirimanEmail')
            ->first();

        if ($penjualan) {
            // Email Admin diambil dari config .env (posrindubunda@gmail.com)
            $emailAdmin = config('mail.from.address'); 

            // 2. Generate PDF (Pastikan menggunakan number_format, bukan rupiah())
            $pdf = Pdf::loadView('pdf.invoice', [
                'no_faktur'    => $penjualan->no_faktur,
                'nama_pembeli' => $penjualan->nama_pembeli,
                'items'        => $penjualan->detailPenjualan,
                'total'        => $penjualan->grand_total,
                'tanggal'      => $penjualan->tanggal_penjualan,
            ]);

            // Data untuk body email
            $dataAtribut = [
                'customer_name'  => $penjualan->nama_pembeli,
                'invoice_number' => $penjualan->no_faktur
            ];

            try {
                // 3. Kirim Email ke Mailtrap
                Mail::to($emailAdmin)->send(new InvoiceMail($dataAtribut, $pdf->output()));

                // 4. Catat riwayat agar tidak terkirim double
                PengirimanEmailPenjualan::create([
                    'penjualan_id'         => $penjualan->id,
                    'status'               => 'terkirim ke admin',
                    'tgl_pengiriman_pesan' => now(),
                ]);

            } catch (\Exception $e) {
                return "Gagal kirim email: " . $e->getMessage();
            }
        }

        return view('autorefresh_email');
    }
}