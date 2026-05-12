<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanEmailReservasi extends Model
{
    use HasFactory;

    // nama tabel (sesuai migration)
    protected $table = 'pengiriman_email_reservasi';

    // semua kolom boleh diisi
    protected $guarded = [];

    // =========================
    // RELASI KE RESERVASI
    // =========================
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}