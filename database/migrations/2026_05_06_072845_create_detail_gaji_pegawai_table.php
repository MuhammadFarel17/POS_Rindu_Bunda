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
        Schema::create('detail_gaji_pegawai', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel header (gaji_pegawai)
            // Pastikan tabel gaji_pegawai sudah dibuat bermigrasi sebelumnya
            $table->foreignId('gaji_pegawai_id')
                ->constrained('gaji_pegawai')
                ->cascadeOnDelete();
            
            // Kolom rincian komponen gaji
            $table->string('nama_komponen'); // Contoh: Tunjangan Makan, Bonus, Potongan BPJS
            $table->enum('jenis', ['tunjangan', 'potongan']); // Untuk membedakan penambah atau pengurang
            $table->decimal('nominal', 15, 2); // Menggunakan decimal untuk akurasi nilai uang
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_gaji_pegawai');
    }
};