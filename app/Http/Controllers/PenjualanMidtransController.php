<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;
use Illuminate\Support\Facades\Log;

class PenjualanMidtransController extends Controller
{
    /**
     * Inisialisasi Konfigurasi Midtrans
     */
    protected function initMidtrans()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * 1. Fungsi untuk Memicu Pembayaran (Tombol Bayar)
     */
    public function bayar($id)
    {
        $this->initMidtrans();

        // Ambil data penjualan beserta detail produknya
        $penjualan = Penjualan::with('detailPenjualan.produk')->findOrFail($id);

        // Siapkan Parameter Pembayaran
        $params = [
            'transaction_details' => [
                'order_id' => $penjualan->no_faktur . '-' . time(), // Harus unik setiap request
                'gross_amount' => (int) $penjualan->grand_total,
            ],
            'customer_details' => [
                'first_name' => $penjualan->nama_pembeli,
            ],
            'item_details' => $penjualan->detailPenjualan->map(function ($item) {
                return [
                    'id' => $item->produk_id,
                    'price' => (int) $item->harga,
                    'quantity' => (int) $item->qty,
                    'name' => $item->produk->nama_produk ?? 'Produk Rindu Bunda',
                ];
            })->toArray(),
        ];

        try {
            // Generate Snap Redirect URL
            $snapUrl = Snap::createTransaction($params)->redirect_url;
            
            // Redirect ke halaman pembayaran Midtrans
            return redirect()->away($snapUrl);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 2. Fungsi Webhook (Notification Handler)
     * Menangani laporan otomatis dari Midtrans ke Database
     */
    public function notificationHandler(Request $request)
    {
        $this->initMidtrans();
        
        $payload = $request->getContent();
        $notification = json_decode($payload);

        // Validasi Signature Key untuk keamanan agar data tidak dimanipulasi
        $validSignatureKey = hash("sha512", 
            $notification->order_id . 
            $notification->status_code . 
            $notification->gross_amount . 
            Config::$serverKey
        );

        if ($notification->signature_key !== $validSignatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ambil no_faktur asli (Menghapus timestamp di belakang order_id)
        // Contoh: F-0000006-1778501754 -> F-0000006
        $orderParts = explode('-', $notification->order_id);
        $noFaktur = $orderParts[0] . '-' . $orderParts[1];

        $penjualan = Penjualan::where('no_faktur', $noFaktur)->first();

        if ($penjualan) {
            $transactionStatus = $notification->transaction_status;

            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // UPDATE DATABASE: Pembayaran Berhasil
                $penjualan->update([
                    'status_penjualan' => 'Selesai',
                    'metode_pembayaran' => 'Midtrans (' . $notification->payment_type . ')',
                    'bayar' => $notification->gross_amount,
                    'kembalian' => 0, // Menggunakan 'kembalian' sesuai struktur tabel
                ]);
            } elseif ($transactionStatus == 'pending') {
                $penjualan->update(['status_penjualan' => 'Pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $penjualan->update(['status_penjualan' => 'Batal']);
            }
        }

        return response()->json(['message' => 'Notification Handled']);
    }
}