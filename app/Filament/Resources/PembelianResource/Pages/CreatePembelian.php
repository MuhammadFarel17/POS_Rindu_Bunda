<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    /**
     * Langkah 1: Pastikan data pendukung (tgl) terisi agar tidak error
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tanggalHeader = $data['tgl'];

        if (isset($data['pembelianProduk'])) {
            foreach ($data['pembelianProduk'] as $key => $item) {
                $data['pembelianProduk'][$key]['tgl'] = $tanggalHeader;
                // Harga di sini boleh dikosongkan atau diisi 0 karena akan di-update di afterCreate
            }
        }

        return $data;
    }

    /**
     * Langkah 2: OTOMATISASI HITUNG TAGIHAN
     * Fungsi ini berjalan otomatis tepat setelah tombol 'Create' ditekan
     */
    protected function afterCreate(): void
    {
        $record = $this->record; // Data pembelian yang baru disimpan

        // Jalankan SQL Update otomatis menggunakan harga dari master produk
        DB::statement("
            UPDATE pembelian p
            SET p.tagihan = (
                SELECT SUM(pp.jml * pr.harga)
                FROM pembelian_produk pp
                JOIN produk pr ON pp.produk_id = pr.id
                WHERE pp.pembelian_id = p.id
            )
            WHERE p.id = ?
        ", [$record->id]);

        // Segarkan data agar nominal di dashboard langsung muncul (tidak 0)
        $record->refresh();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}