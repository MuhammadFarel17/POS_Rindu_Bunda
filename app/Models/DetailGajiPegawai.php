<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailGajiPegawai extends Model  // <-- ubah nama class
{
    protected $table = 'detail_gaji_pegawai';

    protected $fillable = [
        'gaji_pegawai_id', 'nama_komponen', 'jenis', 'nominal',
    ];

    public function gajiPegawai(): BelongsTo
    {
        return $this->belongsTo(GajiPegawai::class, 'gaji_pegawai_id');
    }
}