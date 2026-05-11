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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id(); // Sesuai database: id
            
            // Relasi ke supplier, karena di gambar ada kolom suplayer_id
            $table->foreignId('kode_suplayer')->constrained('suplayer')->onDelete('cascade');
            
            $table->string('no_faktur')->unique(); // Sesuai database: no_faktur
            $table->string('status'); // Sesuai database: status (pesan, pending, dll)
            $table->dateTime('tgl'); // Sesuai database: tgl (tipe datetime)
            
            // Sesuai database: tagihan (bukan total_biaya)
            // Menggunakan decimal(15,2) agar presisi untuk mata uang
            $table->decimal('tagihan', 15, 2)->default(0); 
            
            $table->timestamps(); // Sesuai database: created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};