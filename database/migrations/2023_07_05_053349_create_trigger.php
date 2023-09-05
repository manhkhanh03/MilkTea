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
            CREATE TRIGGER tr_image_user_Default BEFORE INSERT ON `users` FOR EACH ROW
            BEGIN
                SET NEW.img_user = \'https://th.bing.com/th/id/OIP.zpcPLb79F6yFzCw4O1bgsQAAAA?pid=ImgDet&w=350&h=269&rs=1\';
                SET NEW.user_name = CONCAT(
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1),
                    SUBSTRING("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", FLOOR(1 + RAND() * 62), 1));    
            END
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
