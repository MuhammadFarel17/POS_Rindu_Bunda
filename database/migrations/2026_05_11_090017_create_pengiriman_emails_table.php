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
        // Tambahkan pengaman agar tidak error jika tabel sudah ada secara fisik
        if (!Schema::hasTable('pengiriman_email')) {
            Schema::create('pengiriman_email', function (Blueprint $table) {
                $table->id();
                // Relasi ke tabel pembelian (singular sesuai file migrasi sebelumnya)
                $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
                $table->string('status')->nullable();
                $table->dateTime('tgl_pengiriman_pesan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_email');
    }
};