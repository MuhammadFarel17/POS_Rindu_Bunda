<?php

namespace App\Exports;

use App\Models\Topping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToppingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Topping::get()->map(function ($item) {
            return [
                $item->name,
                'Rp ' . number_format($item->price, 0, ',', '.'),
                'Rp ' . number_format($item->cost, 0, ',', '.'),
                $item->is_active ? 'Aktif' : 'Tidak Aktif',
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Topping',
            'Harga',
            'Biaya',
            'Status',
            'Tanggal Dibuat',
        ];
    }
}
