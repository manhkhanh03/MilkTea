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
            CREATE TRIGGER tr_status_shipping_tracking_Default BEFORE UPDATE ON `locations` FOR EACH ROW
            BEGIN
                IF NEW.description = "Successful pickup" THEN
                    UPDATE `shipping_tracking`
                    SET `status` = "Delivering"
                    WHERE `id` = OLD.shipping_tracking_id;   
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
