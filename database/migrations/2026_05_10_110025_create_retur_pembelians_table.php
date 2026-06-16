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
        Schema::create('retur_pembelians', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel pembelian
            $table->unsignedBigInteger('pembelian_id');
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->onDelete('cascade');
            
            $table->string('no_retur')->unique();
            $table->date('tanggal');
            $table->decimal('total_retur', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        }); // Penutup Schema::create
    } // Penutup public function up

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};