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
        // Nama tabel diubah menjadi 'pegawai' sesuai permintaan (tanpa 's')
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users seperti pada tabel pembeli di modul
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Kolom identitas pegawai mengikuti pola modul[cite: 1]
            $table->string('kode_pegawai'); 
            $table->string('nama_pegawai');
            $table->string('jabatan'); // Tambahan untuk membedakan dengan pembeli
            $table->string('alamat');
            $table->string('telepon');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};