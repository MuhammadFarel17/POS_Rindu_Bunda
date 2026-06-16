<?php

namespace App\Filament\Exports;

use App\Models\Topping;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ToppingExporter extends Exporter
{
    protected static ?string $model = Topping::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Nama Topping'),
            
            ExportColumn::make('price')
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('cost')
                ->label('Biaya')
                ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export topping telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
