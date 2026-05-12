<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retur_penjualan', function (Blueprint $table) {
            // Kolom utama ID
            $table->id('id_retur_penjualan'); 

            // Foreign Key (sesuaikan tipe data dengan tabel asalnya, biasanya bigInteger)
            $table->unsignedBigInteger('id_penjualan');
            $table->unsignedBigInteger('id_user');

            // Data Retur
            $table->date('tanggal_retur');
            $table->text('alasan_retur');
            $table->integer('total_retur'); // atau decimal jika ada nilai rupiah

            // Timestamps (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_penjualan');
    }
};