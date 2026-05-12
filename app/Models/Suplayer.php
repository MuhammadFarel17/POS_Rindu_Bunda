<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplayer extends Model
{
    use HasFactory;

    // Nama tabel eksplisit (sudah benar jika tabelnya tidak jamak/suppliers)
    protected $table = 'suplayer'; 

    // Menggunakan guarded kosong berarti semua kolom mass-assignable
    protected $guarded = []; 

    /**
     * Logika Generate Kode Suplayer Otomatis
     * Format: S-00001
     */
    public static function getKodeSuplayer(): string
    {
        // Menggunakan Eloquent untuk mengambil kode terakhir secara bersih
        $lastRecord = self::orderBy('kode_suplayer', 'desc')->first();

        if (!$lastRecord) {
            return 'S-00001';
        }

        // Mengambil angka dari string (contoh 'S-00005' menjadi 5)
        // substr($string, 2) membuang 'S-'
        $lastNumber = (int) substr($lastRecord->kode_suplayer, 2);
        $nextNumber = $lastNumber + 1;

        // Mengembalikan string dengan padding nol 5 digit
        return 'S-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi ke tabel User (Admin yang menginput)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel Pembelian
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'suplayer_id');
    }
}