<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'user_id', 'ip_address'];
    public function products () {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
