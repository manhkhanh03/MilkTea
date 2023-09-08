<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    use HasFactory;
    protected $table = 'chatbot';
    protected $fillable = ['shop_id', 'auto_chat', 'quick_message'];
}