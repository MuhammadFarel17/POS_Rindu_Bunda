<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Suplayer extends Model
{
    use HasFactory;

    protected $table = 'suplayer'; // Nama tabel eksplisit

    protected $guarded = []; //semua kolom boleh di isi

    public static function getKodeSuplayer()
    {
        // query kode perusahaan
        $sql = "SELECT IFNULL(MAX(kode_suplayer), 'S-00000') as kode_suplayer 
                FROM suplayer ";
        $kodesuplayer = DB::select($sql);

        // cacah hasilnya
        foreach ($kodesuplayer as $kdspl) {
            $kd = $kdspl->kode_suplayer;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-5);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        $noakhir = 'S-'.str_pad($noakhir,5,"0",STR_PAD_LEFT); //menyambung dengan string S-00001
        return $noakhir;

    }

    // relasi ke tabel pembeli
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
        // pastikan 'user_id' adalah nama kolom foreign key
    }

    // relasi ke tabel pembelian
    public function pembelian()
    {
       // return $this->hasMany(Pembelian::class, 'suplayer_id');
        return $this->hasMany(Pembelian::class, 'suplayer_id');
    }
}