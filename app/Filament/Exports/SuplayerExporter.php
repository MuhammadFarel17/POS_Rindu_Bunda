<?php

namespace App\Filament\Exports;

use App\Models\Suplayer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SuplayerExporter extends Exporter
{
    protected static ?string $model = Suplayer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_suplayer')
                ->label('Kode Suplayer'),
            
            ExportColumn::make('name')
                ->label('Nama Suplayer'),

            ExportColumn::make('address')
                ->label('Alamat'),

            ExportColumn::make('city')
                ->label('Kota'),

            ExportColumn::make('phone')
                ->label('Telepon'),

            ExportColumn::make('email')
                ->label('Email'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export suplayer telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
