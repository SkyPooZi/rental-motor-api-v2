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
        Schema::create('motorcycle_lists', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->string('type');
            $table->string('brand');
            $table->integer('stock');
            $table->integer('price_per_day');
            $table->integer('price_per_week');
            $table->integer('delivery_price');
            $table->string('status');
            $table->datetime('unavailable_start_date')->nullable();
            $table->datetime('unavailable_end_date')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_lists');
    }
};
