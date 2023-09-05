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
            CREATE TRIGGER tr_update_quantity_product_Default AFTER INSERT ON `orders` FOR EACH ROW
            BEGIN
                DECLARE PRODUCT_ID INT;
                DECLARE OLD_QUANTITY INT;
                SET @PRODUCT_ID := NULL;
                SET @OLD_QUANTITY := NULL;

                SELECT product_id INTO PRODUCT_ID FROM `orders`
                JOIN `product_size_flavors` ON `orders`.`product_size_flavor_id` = `product_size_flavors`.`id` 
                WHERE `product_size_flavor_id` = NEW.product_size_flavor_id
                LIMIT 1;

                SELECT quantity INTO OLD_QUANTITY FROM `products` WHERE `id` = PRODUCT_ID
                LIMIT 1;

                IF OLD_QUANTITY > NEW.quantity THEN
                    UPDATE `products` SET `quantity` = `quantity` - NEW.quantity WHERE `id` = PRODUCT_ID;
                END IF;
            END;
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
