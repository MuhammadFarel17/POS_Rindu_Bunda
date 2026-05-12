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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();

            // Nama pembeli diinput manual saat transaksi
            $table->string('nama_pembeli')->nullable();

            // User/kasir yang melakukan transaksi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Nomor faktur
            $table->string('no_faktur')->unique();

            // Tanggal transaksi
            $table->dateTime('tanggal_penjualan');

            // Total transaksi
            $table->decimal('total_penjualan', 15, 2);

            // Total akhir
            $table->decimal('grand_total', 15, 2);

            // Metode pembayaran
            $table->enum('metode_pembayaran', ['Cash', 'QRIS', 'Transfer']);

            // Jumlah bayar
            $table->decimal('bayar', 15, 2);

            // Kembalian
            $table->decimal('kembalian', 15, 2);

            // Status transaksi
            $table->enum('status_penjualan', ['Selesai', 'Batal']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};