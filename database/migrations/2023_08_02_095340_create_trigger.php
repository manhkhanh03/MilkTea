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
            CREATE TRIGGER tr_shipping_tracking_product_Default AFTER INSERT ON `orders` FOR EACH ROW
            BEGIN
                INSERT INTO `shipping_tracking` (`order_id`, `status`, `created_at`) VALUES (NEW.id, "Waiting confirmation", NOW());
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
