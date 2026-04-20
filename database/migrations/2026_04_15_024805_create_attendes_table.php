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
        Schema::create('attendes', function (Blueprint $table) {
            $table->id('id_attendes');
            $table->foreignId('id_detail')->references('id_detail')->on('order_details');
            $table->string('kode_tiket')->unique();
            $table->enum('status', ['belum', 'sudah'])->default('belum');
            $table->dateTime('waktu_checkin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendes');
    }
};
