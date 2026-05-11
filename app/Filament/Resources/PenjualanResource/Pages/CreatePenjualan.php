<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Redirect;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    /**
     * TAHAP 1: Mencegah error "Field 'bayar'/'kembalian' doesn't have a default value"
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['metode_pembayaran'] === 'Midtrans') {
            $data['bayar'] = 0;
            $data['kembalian'] = 0;
            $data['status_penjualan'] = 'Pending';
        } else {
            $data['status_penjualan'] = 'Selesai';
        }

        return $data;
    }

    /**
     * TAHAP 2: Eksekusi Midtrans setelah data tersimpan di database
     */
    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->metode_pembayaran === 'Midtrans') {
            Config::$serverKey = config('services.midtrans.serverKey');
            Config::$isProduction = config('services.midtrans.isProduction');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $order_id = $record->no_faktur . '-' . time();

            // Mapping item belanja untuk rincian di halaman Midtrans
            $item_details = [];
            foreach ($record->detailPenjualan as $item) {
                $item_details[] = [
                    'id'       => $item->produk_id,
                    'price'    => (int) $item->harga,
                    'quantity' => (int) $item->qty,
                    'name'     => $item->produk->nama_produk ?? 'Produk Rindu Bunda',
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => $order_id,
                    'gross_amount' => (int) $record->grand_total,
                ],
                'item_details' => $item_details,
                'customer_details' => [
                    'first_name' => $record->nama_pembeli ?? 'Pelanggan',
                ],
            ];

            try {
                $paymentUrl = Snap::createTransaction($params)->redirect_url;
                
                // Update Order ID agar sinkron dengan notifikasi nanti
                $record->update(['order_id' => $order_id]);

                // Paksa redirect ke Midtrans
                Redirect::away($paymentUrl)->send();
                exit; 
            } catch (Exception $e) {
                \Filament\Notifications\Notification::make()
                    ->title('Gagal menghubungkan ke Midtrans')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}