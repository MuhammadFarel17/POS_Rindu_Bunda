<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PegawaiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pegawai::with('user')->get()->map(function ($item) {
            return [
                $item->kode_pegawai,
                $item->nama_pegawai,
                $item->jabatan,
                $item->telepon,
                $item->alamat,
                $item->user?->email ?? '-',
                $item->status,
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Pegawai',
            'Nama Pegawai',
            'Jabatan',
            'Telepon',
            'Alamat',
            'Email',
            'Status',
            'Tanggal Dibuat',
        ];
    }
}
