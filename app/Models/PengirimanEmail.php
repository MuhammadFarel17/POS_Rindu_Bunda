<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanEmail extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_email';
    protected $guarded = [];

    public function gajiPegawai()
    {
        return $this->belongsTo(GajiPegawai::class, 'gaji_pegawai_id');
    }
}