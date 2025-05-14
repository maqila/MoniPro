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
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->string('kode'); // Manual input field for collaboration code
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('nama_proyek');
            $table->string('pic');
            $table->string('jabatan')->nullable();
            $table->string('contact')->nullable();
            $table->date('tanggal');
            $table->string('riwayat_komunikasi'); // Communication history
            $table->string('dokumen')->nullable();
            $table->string('pic_se')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
