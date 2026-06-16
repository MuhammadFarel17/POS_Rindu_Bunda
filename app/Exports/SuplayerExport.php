<?php

namespace App\Exports;

use App\Models\Suplayer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuplayerExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Suplayer::get()->map(function ($item) {
            return [
                $item->kode_suplayer,
                $item->name,
                $item->address,
                $item->city,
                $item->phone,
                $item->email,
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Suplayer',
            'Nama Suplayer',
            'Alamat',
            'Kota',
            'Telepon',
            'Email',
            'Tanggal Dibuat',
        ];
    }
}
