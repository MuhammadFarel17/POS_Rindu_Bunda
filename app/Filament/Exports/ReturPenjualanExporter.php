<?php

namespace App\Filament\Exports;

use App\Models\ReturPenjualan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ReturPenjualanExporter extends Exporter
{
    protected static ?string $model = ReturPenjualan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_retur_penjualan')
                ->label('ID Retur'),
            
            ExportColumn::make('penjualan.no_faktur')
                ->label('No. Faktur Penjualan'),

            ExportColumn::make('tanggal_retur')
                ->label('Tanggal Retur'),

            ExportColumn::make('total_retur')
                ->label('Total Retur')
                ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('alasan_retur')
                ->label('Alasan Retur'),

            ExportColumn::make('user.name')
                ->label('Admin'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export retur penjualan telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
