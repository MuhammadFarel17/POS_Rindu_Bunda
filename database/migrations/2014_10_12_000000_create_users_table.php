<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Data utama
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // Tambahan penting
            $table->string('phone', 20)->nullable(); // lebih aman dibatasi
            $table->text('address')->nullable();

            // Optional
            $table->string('username')->unique()->nullable();
            $table->string('photo')->nullable();

            // Status user
            $table->boolean('is_active')->default(true)->index(); // index biar cepat query

            // Tracking tambahan (lebih profesional)
            $table->timestamp('last_login_at')->nullable();

            // Verifikasi email
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Index tambahan (biar performa bagus)
            $table->index('email');
            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
    
};