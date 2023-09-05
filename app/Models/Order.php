<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'product_size_flavor_id', 'shipping_address', 
    'quantity', 'total', 'payment_method', 'payment_status'];

     /**
     * Get the productSizeFlavor that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productSizeFlavor()
    {
        return $this->belongsTo(ProductSizeFlavor::class, 'product_size_flavor_id');
    }

    /**
     * Get the shippingTracking associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippingTracking()
    {
        return $this->hasOne(ShippingTracking::class);
    }

    public function transactionHistory() {
        return $this->hasOne(TransactionHistory::class);
    }
}
