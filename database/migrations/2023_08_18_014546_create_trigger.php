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
        DB::unprepared('
            CREATE TRIGGER tr_status_orders_Default BEFORE INSERT ON `transaction_history` FOR EACH ROW
            BEGIN
                IF NEW.type = "refund" THEN
                    UPDATE `orders`
                    SET `status` = "Refund"
                    WHERE `id` = NEW.order_id;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger');
    }
};
