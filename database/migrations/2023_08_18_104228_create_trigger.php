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
            CREATE TRIGGER tr_insert_transaction_history_Default AFTER INSERT ON `orders` FOR EACH ROW
            BEGIN
                DECLARE recipient_id BIGINT;
                IF NEW.payment_status = "Paid" THEN
                    SELECT shop_id INTO recipient_id
                    FROM product_size_flavors JOIN products ON products.id = product_size_flavors.product_id
                    WHERE product_size_flavors.id = NEW.product_size_flavor_id;

                    INSERT INTO `transaction_history` (`executor_id`, `recipient_id`, `order_id`, `amount`, `type`, `status`, `description`, `payment_method`) 
                    VALUES (NEW.customer_id, recipient_id, NEW.id, NEW.total - (NEW.total * 0.05), "revenue", "Completed", "Sales revenue", "Direct Bank Transfer");
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
