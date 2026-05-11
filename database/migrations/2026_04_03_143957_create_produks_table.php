<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('produk', function (Blueprint $table) {
        $table->id(); // id
        $table->foreignId('id_kategori')->constrained('kategori')->cascadeOnDelete();
        $table->string('nama_produk'); // nama_produk
        $table->string('gambar')->nullable(); // gambar
        $table->integer('harga'); // harga
        $table->integer('stok'); // stok
        $table->timestamps(); // created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};