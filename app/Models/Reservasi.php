<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi'; // nama tabel

    protected $guarded = []; // semua kolom boleh diisi

    // ========================
    // Generate kode reservasi
    // ========================
    public static function getKodeReservasi()
    {
        $sql = "SELECT IFNULL(MAX(kode_reservasi), 'RSV-0000') as kode_reservasi FROM reservasi";
        $kode = DB::select($sql);

        foreach ($kode as $kd) {
            $no = (int) substr($kd->kode_reservasi, -4);
            $no++;
            $char = "RSV-";
            $newKode = $char . sprintf("%04s", $no);
        }

        return $newKode;
    }

    // ========================
    // Relasi ke User
    // ========================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}