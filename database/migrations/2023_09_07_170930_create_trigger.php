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
            CREATE TRIGGER tr_insert_message_chatbot_Default AFTER INSERT ON `chatbot` FOR EACH ROW
            BEGIN
                INSERT INTO `message_chatbot` (`chatbot_id`, `content`, `type`)
                VALUES (NEW.id, "Hello, how i can help you?", "auto_chat");
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