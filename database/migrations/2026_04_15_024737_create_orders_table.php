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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->foreignId('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreignId('id_voucher')->references('id_voucher')->on('vouchers')->cascadeOnDelete()->nullable();
            $table->date('tanggal_order');
            $table->integer('total_price');
            $table->integer('discount');
            $table->integer('final_price');
            $table->enum('status', ['pending', 'paid', 'cancel', 'confirm'])->default('pending');
            $table->dateTime('expired_at');
            $table->enum('cancel_reason', ['expired', 'user', 'admin'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
