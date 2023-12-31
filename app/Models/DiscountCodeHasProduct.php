<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCodeHasProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'discount_code_id'];   
}
