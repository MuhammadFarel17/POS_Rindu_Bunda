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
        Schema::create('gaji_pegawai', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel pegawai yang sudah kita buat sebelumnya
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            
            // Info Penggajian
            $table->string('no_slip_gaji')->unique(); // Seperti no_faktur
            $table->date('tanggal_gaji');
            $table->string('bulan');
            $table->string('tahun');
            
            // Nominal
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('total_tunjangan', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('total_diterima', 15, 2); // Net Salary[cite: 1]
            
            $table->string('status')->default('draft'); // draft, paid[cite: 1]
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_pegawai');
    }
};