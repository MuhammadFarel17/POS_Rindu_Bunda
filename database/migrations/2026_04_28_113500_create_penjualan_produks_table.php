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
        Schema::create('penjualan_produk', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel penjualan
            $table->foreignId('penjualan_id')
                ->constrained('penjualan')
                ->onDelete('cascade');

            // relasi ke tabel produk
            $table->foreignId('produk_id')
                ->constrained('produk')
                ->onDelete('cascade');

            // relasi ke tabel masterdata_toping
            $table->foreignId('topping_id')
                ->nullable()
                ->constrained('toppings')
                ->onDelete('cascade');

            // harga jual produk
            $table->decimal('harga', 15, 2);

            // jumlah produk dibeli
            $table->integer('qty');

            // subtotal
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_produk');
    }
};