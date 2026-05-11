<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->id(); 
            
            // Tetap gunakan unsignedBigInteger agar sinkron dengan ID Laravel
             $table->foreignId('id_penjualan')->constrained('penjualan')->cascadeOnDelete();
             $table->foreignId('id_user')->constrained('user')->cascadeOnDelete();

            $table->dateTime('tanggal_retur');
            $table->text('alasan_retur');
            $table->decimal('total_retur', 15, 2);
            $table->timestamps();

            // Baris foreign key dihapus dulu sesuai permintaanmu
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_penjualan');
    }
};