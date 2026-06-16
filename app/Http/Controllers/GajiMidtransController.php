<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GajiPegawai;

class GajiMidtransController extends Controller
{
    // METHOD 1: Tampilkan halaman bayar + generate snap token
    public function bayar($id)
    {
        $gaji = GajiPegawai::with('pegawai.user')->findOrFail($id);

        // Buat order_id unik
        $order_id = $gaji->no_slip_gaji . '-' . date('YmdHis');

        // Cek apakah snap_token masih valid di Midtrans
        if ($gaji->snap_token) {

            $ch = curl_init();

            $login = env('MIDTRANS_SERVER_KEY');

            $URL = 'https://api.sandbox.midtrans.com/v2/' . $gaji->order_id . '/status';

            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:");

            $output = curl_exec($ch);

            curl_close($ch);

            $outputjson = json_decode($output, true);

            // Kalau masih valid pakai token lama
            if (
                !in_array($outputjson['status_code'] ?? '', ['404', '407']) &&
                !in_array($outputjson['transaction_status'] ?? '', ['expire', 'cancel', 'deny'])
            ) {

                return view('midtrans.bayar-gaji', [
                    'gaji'       => $gaji,
                    'snap_token' => $gaji->snap_token,
                ]);
            }
        }

        // Config Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $order_id,
                'gross_amount' => (int) $gaji->total_diterima,
            ],

            'item_details' => [
                [
                    'id'       => $gaji->no_slip_gaji,
                    'price'    => (int) $gaji->total_diterima,
                    'quantity' => 1,
                    'name'     => 'Gaji ' . ($gaji->pegawai->nama_pegawai ?? '-') . ' ' . $gaji->bulan . ' ' . $gaji->tahun,
                ],
            ],

            'customer_details' => [
                'first_name' => $gaji->pegawai->nama_pegawai ?? '-',
                'email'      => $gaji->pegawai->user->email ?? 'noreply@pos.com',
            ],

            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit'       => 'minutes',
                'duration'   => 60,
            ],
        ];

        // Generate snap token
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan ke database
        $gaji->update([
            'order_id'   => $order_id,
            'snap_token' => $snapToken,
        ]);

        return view('midtrans.bayar-gaji', [
            'gaji'       => $gaji,
            'snap_token' => $snapToken,
        ]);
    }

    // METHOD 2: Webhook / Notification Midtrans
    public function notificationHandler(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        $gaji = GajiPegawai::where('order_id', $order_id)->first();

        if (!$gaji) {

            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Kalau pembayaran sukses
        if (in_array($transaction, ['settlement', 'capture'])) {

            $gaji->update([
                'status' => 'lunas'
            ]);
        }

        // Kalau pembayaran gagal / expired
        if (in_array($transaction, ['expire', 'cancel', 'deny'])) {

            $gaji->update([
                'status'     => 'draft',
                'snap_token' => null,
                'order_id'   => null,
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    // METHOD 3: Cek status manual (optional)
    public function cekStatus()
    {
        $gajiPending = GajiPegawai::where('status', 'draft')
            ->whereNotNull('order_id')
            ->get();

        foreach ($gajiPending as $gaji) {

            $ch = curl_init();

            $login = env('MIDTRANS_SERVER_KEY');

            $URL = 'https://api.sandbox.midtrans.com/v2/' . $gaji->order_id . '/status';

            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:");

            $output = curl_exec($ch);

            curl_close($ch);

            $outputjson = json_decode($output, true);

            // DEBUG (boleh dihapus nanti)
            // dd($outputjson);

            if (
                ($outputjson['status_code'] ?? '') == '200' &&
                in_array(($outputjson['transaction_status'] ?? ''), ['settlement', 'capture'])
            ) {

                $gaji->update([
                    'status' => 'lunas'
                ]);
            }

            // kalau expired / gagal
            if (
                in_array(($outputjson['transaction_status'] ?? ''), ['expire', 'cancel', 'deny'])
            ) {

                $gaji->update([
                    'status' => 'draft',
                    'snap_token' => null,
                    'order_id' => null,
                ]);
            }
        }

        return redirect('/admin/gaji-pegawais')
            ->with('success', 'Status pembayaran berhasil diperbarui');
    }
}