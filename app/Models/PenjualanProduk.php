<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    use HasFactory;

    protected $table = 'penjualan_produk';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'topping_id',
        //'harga_beli',
        'harga',
        'qty',
        'subtotal'
    ];

    // relasi ke tabel penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    // relasi ke tabel produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // relasi ke tabel topping
    public function topping()
    {
        return $this->belongsTo(Topping::class, 'topping_id');
    }
}