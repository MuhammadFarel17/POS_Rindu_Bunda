<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory; // Disarankan tetap digunakan jika butuh testing

    // Karena nama tabel di database adalah 'coa' bukan 'coas'
    protected $table = 'coa';

    /**
     * Kolom yang dapat diisi secara massal.
     * Ini lebih aman daripada $guarded untuk memastikan data yang masuk terfilter.
     */
    protected $fillable = [
        'header_akun',
        'kode_akun',
        'nama_akun',
    ];

    // Jika kamu ingin tetap menggunakan guarded (seperti kodemu sebelumnya):
    // protected $guarded = []; 
}