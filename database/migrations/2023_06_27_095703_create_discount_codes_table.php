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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('name_discount_code', 100);
            $table->string('code', 100);
            $table->float('discount_amount');
            $table->bigInteger('total');
            $table->string('type_discount_amount', 100); // $, %
            $table->dateTime('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('end_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('type_code')->default('Shopwide Voucher'); // Shopwide Voucher, Conditional, One-time
            $table->string('status')->default('Expired'); // Active, Expired , Canceled,  Suspended
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
