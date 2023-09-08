<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\MessageChatbot;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (range(1, 22) as $index) {
            MessageChatbot::create([
                'chatbot_id' => rand(1, 23),
                'content' => 'Hello bro',
                'type' => 'quick_message',
            ]);
        }
    }
}