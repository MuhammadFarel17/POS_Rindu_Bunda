<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini di atas

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $guarded = [];

    /**
     * Relasi ke tabel Kategori
     * Mengasumsikan nama kolom di tabel produk adalah 'kategori' 
     * dan merujuk ke 'id' di tabel kategori.
     */
    public function kategoriRelasi()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    /**
     * Logika Generate Kode Produk Otomatis (Contoh: AB001)
     */
    public static function getKodeProduk()
    {
        $sql = "SELECT IFNULL(MAX(id), 0) as id_terakhir FROM produk";
        $hasil = DB::select($sql);
        $nextId = $hasil[0]->id_terakhir + 1;

        return 'AB' . str_pad($nextId, 3, "0", STR_PAD_LEFT);
    }

    /**
     * Mutator Harga: Menghapus titik ribuan sebelum simpan ke DB
     */
    public function setHargaAttribute($value)
    {
        $this->attributes['harga'] = str_replace('.', '', $value);
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