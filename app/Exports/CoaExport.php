<?php

namespace App\Exports;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Coa::get()->map(function ($item) {
            return [
                $item->id,
                $item->header_akun,
                $item->kode_akun,
                $item->nama_akun,
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Header Akun',
            'Kode Akun',
            'Nama Akun',
            'Tanggal Dibuat',
        ];
    }
}
