<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = static::latest('id')->first();
            if (! $latest) {
                $model->id_kategori = 'KAT-001';
            } else {
                $string = preg_replace("/[^0-9\.]/", '', $latest->id_kategori);
                $model->id_kategori = 'KAT-' . sprintf('%03d', (int)$string + 1);
            }
        });
    }
}
