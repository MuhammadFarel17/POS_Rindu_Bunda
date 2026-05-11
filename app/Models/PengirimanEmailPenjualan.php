<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanEmailPenjualan extends Model
{
    use HasFactory;

    // Nama tabel sesuai dengan migrasi kamu
    protected $table = 'pengiriman_email_penjualan'; 

    // Menggunakan guarded kosong agar fleksibel saat proses insert data log
    protected $guarded = []; 

    /**
     * Relasi ke tabel penjualan.
     * Ini penting agar admin bisa melihat invoice mana yang sudah terkirim ke email mereka.
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    /**
     * Scope untuk mempermudah pengecekan log pengiriman (opsional)
     */
    public function scopeTerakhir($query)
    {
        return $query->orderBy('tgl_pengiriman_pesan', 'desc');
    }
}