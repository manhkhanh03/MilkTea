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
        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('shipping_tracking_id');
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('type', 100);
            $table->timestamps();

            $table->foreign('notification_id')->references('id')->on('notifications');
            $table->foreign('shipping_tracking_id')->references('id')->on('shipping_tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_recipients');
    }
};
