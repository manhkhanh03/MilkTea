<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'vendor_id',
        'url_video',
        'description',
        'limit_product',
        'quantity',
        'sold_out',
        'status',
    ];

    public function productImage() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function flavors() {
        return $this->belongsToMany(Flavor::class, 'product_size_flavors');
    }

    public function sizes() {
        return $this->belongsToMany(Size::class, 'product_size_flavors');
    }

    /**
     * The products that belong to the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsTo(Order::class, 'product_size_flavors');
    }

    public function shops() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
