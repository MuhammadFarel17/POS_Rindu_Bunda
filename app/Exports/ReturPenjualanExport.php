<?php

namespace App\Exports;

use App\Models\ReturPenjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturPenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ReturPenjualan::with('penjualan', 'user')->get()->map(function ($item) {
            return [
                $item->id_retur_penjualan,
                $item->penjualan?->no_faktur ?? '-',
                $item->tanggal_retur?->format('d-m-Y H:i'),
                'Rp ' . number_format($item->total_retur, 0, ',', '.'),
                $item->alasan_retur,
                $item->user?->name ?? '-',
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Retur',
            'No. Faktur Penjualan',
            'Tanggal Retur',
            'Total Retur',
            'Alasan Retur',
            'Admin',
            'Tanggal Dibuat',
        ];
    }
}
