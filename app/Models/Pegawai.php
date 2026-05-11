<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'kode_pegawai',
        'nama_pegawai',
        'jabatan',
        'alamat',
        'telepon',
        'status',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke slip gaji pegawai ini
    public function gajiPegawai(): HasMany
    {
        return $this->hasMany(Gajipegawai::class, 'pegawai_id');
    }
}