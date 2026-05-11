<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class PenjualanMidtransController extends Controller
{
    public function bayar($id)
    {
        // 1. Ambil data penjualan beserta detail produknya
        $penjualan = Penjualan::with('detailPenjualan.produk')->findOrFail($id);

        // 2. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 3. Siapkan Parameter Pembayaran
        $params = [
            'transaction_details' => [
                'order_id' => $penjualan->no_faktur . '-' . time(), // Order ID harus unik
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
            // 4. Generate Snap Redirect URL
            $snapUrl = Snap::createTransaction($params)->redirect_url;
            
            // 5. Redirect ke halaman Midtrans
            return redirect()->away($snapUrl);
        } catch (Exception $e) {
            // Log error jika gagal
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}