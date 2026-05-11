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
        Schema::table('gaji_pegawai', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('status');
            $table->string('snap_token')->nullable()->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('gaji_pegawai', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token']);
        });
    }
};
