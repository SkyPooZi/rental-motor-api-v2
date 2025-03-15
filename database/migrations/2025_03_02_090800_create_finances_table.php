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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->integer('total_motorcycle_price');
            $table->integer('total_overtime_fee')->default(0);
            $table->integer('total_delivery_fee')->default(0);
            $table->integer('total_point_deduction')->default(0);
            $table->integer('total_discount_fee');
            $table->integer('total_admin_fee');
            $table->integer('total_reschedule_fee')->nullable();
            $table->integer('total_payment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
