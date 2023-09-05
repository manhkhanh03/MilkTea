<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRecipient extends Model
{
    use HasFactory;
    protected $fillable = ['notification_id', 'shipping_tracking_id', 'recipient_id', 'type'];
}
