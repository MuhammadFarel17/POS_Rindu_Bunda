<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini di atas

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'gambar',
        'harga',
        'stok',
        'kategori',
    ];

    // TAMBAHKAN FUNGSI INI
    public function kategoriRelasi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
    // Pastikan foreign key 'kategori' sesuai dengan nama kolom di database
    return $this->belongsTo(Kategori::class, 'kategori', 'id');
    }
}