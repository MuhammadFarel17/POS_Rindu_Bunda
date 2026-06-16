<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Produk::with('kategoriRelasi')->get()->map(function ($item) {
            return [
                $item->id,
                $item->nama_produk,
                $item->kategoriRelasi?->nama_kategori ?? '-',
                'Rp ' . number_format($item->harga, 0, ',', '.'),
                $item->stok,
                $item->created_at?->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Produk',
            'Kategori',
            'Harga',
            'Stok',
            'Tanggal Dibuat',
        ];
    }
}
