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
    Schema::create('toppings', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price', 15, 2)->default(0); // Harga jual
        $table->decimal('cost', 15, 2)->default(0);  // Harga modal
        $table->boolean('is_active')->default(true); // Status aktif
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toppings');
    }
};
