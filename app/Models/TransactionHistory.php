<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;
    protected $table = 'transaction_history';
    protected $fillable = ['executor_id', 'recipient_id', 'type', 'amount', 'status', 'description', 'payment_method'];
}
