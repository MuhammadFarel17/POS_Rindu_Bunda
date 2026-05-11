<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengirimanEmail;
use App\Models\GajiPegawai;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PengirimanEmailController extends Controller
{
    public static function proses_kirim_email_gaji()
    {
        date_default_timezone_set('Asia/Jakarta');

        // Ambil data gaji yang sudah paid tapi belum dikirim emailnya
        $data = GajiPegawai::with(['pegawai.user'])
            ->where('status', 'paid')
            ->whereNotIn('id', function ($query) {
                $query->select('gaji_pegawai_id')
                    ->from('pengiriman_email');
            })
            ->get();

        foreach ($data as $gaji) {
            $email = $gaji->pegawai->user->email ?? null;

            if (!$email) continue;

            $dataSlip = [
                'no_slip_gaji'   => $gaji->no_slip_gaji,
                'nama_pegawai'   => $gaji->pegawai->nama_pegawai,
                'jabatan'        => $gaji->pegawai->jabatan,
                'bulan'          => $gaji->bulan,
                'tahun'          => $gaji->tahun,
                'gaji_pokok'     => $gaji->gaji_pokok,
                'total_tunjangan'=> $gaji->total_tunjangan,
                'total_potongan' => $gaji->total_potongan,
                'total_diterima' => $gaji->total_diterima,
            ];

            // Generate PDF
            $pdf = Pdf::loadView('pdf.slip-gaji', ['data' => $dataSlip]);

            // Kirim email
            Mail::to($email)->send(new \App\Mail\SlipGajiMail($dataSlip, $pdf->output()));

            // Delay agar tidak kena limit mailtrap
            sleep(5);

            // Catat pengiriman
            PengirimanEmail::create([
                'gaji_pegawai_id'    => $gaji->id,
                'status'             => 'sudah terkirim',
                'tgl_pengiriman_pesan' => now(),
            ]);
        }

        return view('autorefresh_email');
    }
}