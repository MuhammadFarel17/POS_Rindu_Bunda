<?php

namespace App\Filament\Exports;

use App\Models\Produk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProdukExporter extends Exporter
{
    // Pastikan model mengarah ke model Produk kamu
    protected static ?string $model = Produk::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Produk'),
            
            ExportColumn::make('id_kategori')
                ->label('ID Kategori'),

            // Jika kamu ingin menampilkan nama kategori (bukan ID), gunakan:
            // ExportColumn::make('kategori.nama_kategori')->label('Kategori'),

            ExportColumn::make('nama_produk')
                ->label('Nama Produk'),

            ExportColumn::make('harga')
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('stok')
                ->label('Stok'),

            ExportColumn::make('gambar')
                ->label('Path Gambar'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export produk telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}