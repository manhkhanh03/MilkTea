<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;
    protected $fillable = 
    ['shop_id', 'name_discount_code', 'code', 'discount_amount', 'total', 'type_discount_amount',
     'start_date', 'end_date', 'applies_to_all_products','type_code', 'status'];
}
