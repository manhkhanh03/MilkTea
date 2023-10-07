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
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('discount_id');
            $table->string('discount_type'); // Loại discount (vận chuyển hoặc sản phẩm)
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('discount_id')->references('id')->on('discount_codes');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_discounts');
    }
};
