<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Penting untuk rincian gaji

class GajiPegawai extends Model
{
    use HasFactory;

    /**
     * Nama tabel didefinisikan manual karena tidak menggunakan akhiran 's'.
     */
    protected $table = 'gaji_pegawai';
    protected $primaryKey = 'id';

    /**
     * Atribut yang dapat diisi secara massal.
     * Disesuaikan dengan kolom migrasi yang kita buat sebelumnya.
     */
    protected $fillable = [
        'pegawai_id',
        'no_slip_gaji',
        'tanggal_gaji',
        'bulan',
        'tahun',
        'gaji_pokok',
        'total_tunjangan',
        'total_potongan',
        'total_diterima',
        'status',
        'keterangan',
        'order_id',
        'snap_token',
    ];

    /**
     * Relasi ke model Pegawai (Setiap slip gaji dimiliki oleh satu pegawai).
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Relasi ke Detail_Gaji_Pegawai (Satu slip gaji memiliki banyak rincian komponen).
     * Ini kunci utama agar fitur REPEATER di Filament bisa berjalan[cite: 1].
     */
    public function details(): HasMany
    {
        // 'gaji_pegawai_id' adalah foreign key di tabel detail_gaji_pegawai[cite: 1]
        return $this->hasMany(DetailGajiPegawai::class, 'gaji_pegawai_id');
    }
}