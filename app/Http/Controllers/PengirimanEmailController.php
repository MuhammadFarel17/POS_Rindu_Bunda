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

        // Return ke view autorefresh agar proses berjalan terus secara otomatis
        // Kembali ke view autorefresh untuk looping pengiriman selanjutnya
        return view('pdf.autorefresh_email');
    }
    
        return view('autorefresh_email');
    }
}