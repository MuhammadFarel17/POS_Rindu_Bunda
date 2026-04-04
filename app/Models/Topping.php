<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    use HasFactory;

    // Tambahkan baris ini
    protected $fillable = [
        'name',
        'price',
        'cost',
        'is_active',
    ];
}