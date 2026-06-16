<?php

namespace App\Filament\Exports;

use App\Models\Pegawai;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PegawaiExporter extends Exporter
{
    protected static ?string $model = Pegawai::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_pegawai')
                ->label('Kode Pegawai'),
            
            ExportColumn::make('nama_pegawai')
                ->label('Nama Pegawai'),

            ExportColumn::make('jabatan')
                ->label('Jabatan'),

            ExportColumn::make('telepon')
                ->label('Telepon'),

            ExportColumn::make('alamat')
                ->label('Alamat'),

            ExportColumn::make('user.email')
                ->label('Email'),

            ExportColumn::make('status')
                ->label('Status'),

            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export pegawai telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
