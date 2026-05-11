<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan';
    protected $primaryKey = 'id_retur_penjualan';
    protected $guarded = [];

    // Relasi tetap bisa dibuat agar Eloquent mempermudah pemanggilan data
   // public function penjualan()
    //{
        //return $this->belongsTo(Penjualan::class, 'id_penjualan');
    //}

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Logika Generate Kode Retur Otomatis (Gaya Dosen)
     */
    public static function getKodeRetur()
    {
        $sql = "SELECT IFNULL(MAX(id_retur_penjualan), 0) as id_terakhir FROM retur_penjualan";
        $hasil = DB::select($sql);
        $nextId = $hasil[0]->id_terakhir + 1;

        return 'RT' . str_pad($nextId, 3, "0", STR_PAD_LEFT);
    }
}