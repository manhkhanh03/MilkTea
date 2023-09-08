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
        Schema::create('message_chatbot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id');
            $table->string('content', 500);
            $table->string('type', 255);
            $table->timestamps();

            $table->foreign('chatbot_id')->references('id')->on('chatbot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_chatbot');
    }
};
