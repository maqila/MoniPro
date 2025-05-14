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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis_customer'); // e.g., owner, contractor, consultant
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->date('aniversary')->nullable();
            $table->string('media_sosial')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->date('last_kerjasama')->nullable(); // Date of the latest collaboration
            $table->string('status')->nullable(); // Average rating for collaboration history
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
