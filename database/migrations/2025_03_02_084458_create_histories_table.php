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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number', 15);
            $table->string('social_media_account');
            $table->text('address');
            $table->string('renter');
            $table->unsignedBigInteger('motorcycle_id');
            $table->datetime('start_date');
            $table->integer('duration');
            $table->datetime('end_date');
            $table->string('renter_purpose');
            $table->string('motorcycle_receipt');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number', 15);
            $table->string('emergency_contact_relationship');
            $table->integer('point')->default(0);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('payment_method');
            $table->integer('total_payment');
            $table->string('history_status');
            $table->unsignedBigInteger('review_id')->nullable();
            $table->date('cancellation_date')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
