<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageChatbot extends Model
{
    use HasFactory;
    protected $table = 'message_chatbot';
    protected $fillable = ['chatbot_id', 'content', 'type'];
}
