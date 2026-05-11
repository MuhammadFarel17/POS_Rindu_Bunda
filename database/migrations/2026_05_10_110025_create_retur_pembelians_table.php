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
        $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
        $table->string('no_retur')->unique();
        $table->date('tanggal');
        $table->decimal('total_retur', 15, 2)->default(0);
        $table->text('keterangan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};
