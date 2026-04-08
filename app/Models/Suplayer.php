<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suplayer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'Suplayer';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'city',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Produk
     * 1 supplier punya banyak produk
     */
    public function products()
    {
        return $this->hasMany(Produk::class, 'Suplayer_id');
    }
}