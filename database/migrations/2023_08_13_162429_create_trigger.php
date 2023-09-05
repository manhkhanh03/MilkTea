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
            CREATE TRIGGER tr_status_location_Default BEFORE UPDATE ON `shipping_tracking` FOR EACH ROW
            BEGIN
                DECLARE executor_id BIGINT;
                DECLARE recipient_id BIGINT;
                DECLARE amount DECIMAL(10,2);
                IF NEW.status = "Cancelled" THEN
                    INSERT INTO `locations` (`shipping_tracking_id`, `description`) VALUES  (OLD.id, NEW.status);
                    IF (SELECT payment_status FROM orders WHERE id = OLD.order_id) = "Paid" THEN
                        UPDATE `orders`
                        SET `payment_status` = "refund"
                        WHERE `id` = OLD.order_id;
                     END IF;
                END IF;
                IF NEW.status = "Delivered" THEN
                    SELECT customer_id, total - (total * 0.05) INTO executor_id, amount
                    FROM shipping_tracking
                    JOIN orders ON shipping_tracking.order_id = orders.id
                    WHERE order_id = OLD.order_id;

                    SELECT vendor_id INTO recipient_id
                    FROM orders
                    JOIN product_size_flavors ON orders.product_size_flavor_id = product_size_flavors.id
                    JOIN products ON products.id = product_size_flavors.product_id
                    WHERE orders.id = OLD.order_id;

                    INSERT INTO `transaction_history` (`executor_id`, `recipient_id`, `order_id`, `amount`, `type`, `status`, `description`, `payment_method`)
                    VALUES (executor_id, recipient_id, OLD.order_id, amount, "revenue", "Completed", "Sales revenue", "Direct Bank Transfer");
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
