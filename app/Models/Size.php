<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        
    ];

    public function products() {
        return $this->belongstoMany(Product::class, 'product_size_flavors');
    }
}
