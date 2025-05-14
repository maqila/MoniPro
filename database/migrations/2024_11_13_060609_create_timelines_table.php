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
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('tempat')->nullable();
            $table->string('pic_se');
            $table->text('keterangan')->nullable();
            $table->string('perusahaan')->nullable();
            $table->string('nama');
            $table->string('contact');
            $table->date('deadline')->nullable(); // Calculated based on tanggal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};
