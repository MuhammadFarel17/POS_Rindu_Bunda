<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    protected $table = 'retur_pembelians';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = static::latest('id')->first();

            if (! $latest) {
                $model->no_retur = 'RET-001';
            } else {
                $string = preg_replace('/[^0-9]/', '', $latest->no_retur);
                $model->no_retur = 'RET-' . sprintf('%03d', (int) $string + 1);
            }
        });
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function detailReturPembelian()
    {
        return $this->hasMany(DetailReturPembelian::class, 'id_retur_pembelian');
    }
}