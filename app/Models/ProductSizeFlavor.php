<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeFlavor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'flavor_id',
        'size_id',
        'price',
    ];
    /**
     * Get all of the orders for the ProductSizeFlavor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
