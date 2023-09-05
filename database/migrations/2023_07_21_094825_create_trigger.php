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
            CREATE TRIGGER tr_status_shipping_Default BEFORE UPDATE ON `shipping_tracking` FOR EACH ROW
            BEGIN
                BEGIN

                    IF NEW.delivery_person_id IS NOT NULL AND (NEW.delivery_person_id <> OLD.delivery_person_id OR OLD.delivery_person_id IS NULL) THEN
                        SET NEW.status = "Waiting pickup";
                    END IF;
                END;
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
