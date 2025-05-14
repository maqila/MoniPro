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
        Schema::table('collaborations', function (Blueprint $table) {
            //
            $table->Integer('kepatuhan_pembayaran');
            $table->Integer('komitmen_kontrak');
            $table->Integer('respon_komunikasi');
            $table->Integer('pengambilan_keputusan');
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collaborations', function (Blueprint $table) {
            //
            $table->dropColumn([
                'kepatuhan_pembayaran',
                'komitmen_kontrak',
                'respon_komunikasi',
                'pengambilan_keputusan',
                'status'
            ]);
        });
    }
};
