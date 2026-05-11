<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// untuk tambahan db
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $guarded = [];

    // generate nomor faktur otomatis
    public static function getKodeFaktur()
    {
        $sql = "SELECT IFNULL(MAX(no_faktur), 'F-0000000') as no_faktur 
                FROM penjualan";

        $kodefaktur = DB::select($sql);

        foreach ($kodefaktur as $kdpmbl) {
            $kd = $kdpmbl->no_faktur;
        }

        // ambil 7 digit terakhir
        $noawal = substr($kd, -7);

        // tambah 1
        $noakhir = $noawal + 1;

        // format F-0000001
        $noakhir = 'F-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT);

        return $noakhir;
    }

    // relasi ke tabel user/kasir
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(PenjualanProduk::class, 'penjualan_id');
    }

// Relasi ke Log Email (PENTING)
    public function pengirimanEmail()
    {
        return $this->hasOne(PengirimanEmailPenjualan::class, 'penjualan_id');
    }
}