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
            CREATE TRIGGER tr_update_shop_Default BEFORE UPDATE ON `users` FOR EACH ROW
            BEGIN
                IF NEW.role_id = 2 OR NEW.role_id = 4 THEN
                    UPDATE `shops`
                    SET `url` = OLD.img_user, `address` = OLD.address
                    WHERE `user_id` = OLD.id;
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
