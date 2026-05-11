<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $guarded = [];

    // HAPUS fungsi booted() yang lama karena menyebabkan error Integrity constraint violation

    public static function getKodeFakturBeli()
    {
        $sql = "SELECT IFNULL(MAX(no_faktur), 'F-0000000') as no_faktur FROM pembelian";
        $kodefaktur = DB::select($sql);
        $kd = $kodefaktur[0]->no_faktur;
        $noawal = substr($kd, -7);
        $noakhir = (int)$noawal + 1;
        return 'F-' . str_pad($noakhir, 7, "0", STR_PAD_LEFT);
    }

    public function pembelianProduk()
    {
        return $this->hasMany(PembelianProduk::class, 'pembelian_id');
    }

    public function suplayer()
    {
        return $this->belongsTo(Suplayer::class, 'kode_suplayer');
    }
}