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
    Schema::create('reservasi', function (Blueprint $table) {
        $table->id();
        $table->string('kode_reservasi')->unique();
        $table->string('nama_pelanggan');
        $table->string('nama_petugas');
        $table->string('no_hp');
        $table->date('tanggal_reservasi');
        $table->time('jam_reservasi');
        $table->integer('jumlah_orang');
        $table->string('meja')->nullable();
        $table->enum('status', ['pending', 'confirmed', 'cancel'])->default('pending');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
