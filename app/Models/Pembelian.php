<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Since retur pembelians exist, let's add the relation
    public function returPembelians()
    {
        return $this->hasMany(ReturPembelian::class, 'pembelian_id');
    }
}
