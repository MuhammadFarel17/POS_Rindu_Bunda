<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianProduk extends Model
{
    use HasFactory;

    protected $table = 'pembelian_produk';
    
    // KOREKSI: Sesuaikan dengan database kamu yang menggunakan nama 'harga'
    protected $fillable = ['pembelian_id', 'produk_id', 'harga', 'jml', 'tgl'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}