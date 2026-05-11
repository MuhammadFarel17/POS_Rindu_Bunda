<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuai migration tadi)
    protected $table = 'retur_penjualan';

    // Primary key custom karena kita tidak pakai 'id' standar
    protected $primaryKey = 'id_retur_penjualan';

    // Semua kolom boleh diisi (mass assignment)
    protected $guarded = [];

    /**
     * Relasi ke model Penjualan
     * Satu retur merujuk ke satu data penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    /**
     * Relasi ke model User
     * Mencatat siapa yang melakukan proses retur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}